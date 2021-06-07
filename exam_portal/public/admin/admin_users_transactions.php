<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
  if(isset($_POST['delete_user_transaction']) && isset($_POST['utid']))
  {
    $q_dt=query("DELETE FROM user_payments WHERE id='".$_POST['utid']."'");
    confirm($q_dt);
  }

  if(isset($_POST['set_transaction_status']) && isset($_POST['utid']))
  {
    $q_st=query("UPDATE user_payments SET payment_status='SUCCESS' WHERE id='".$_POST['utid']."'");
    confirm($q_st);
  }
?>
	<div class="bs-example4" data-example-id="contextual-table">
    <div class="row">
      <div class="col-12 text-center">
        <h3>User Transactions</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8"></div>
      <div class="col-xs-12 col-md-4">
        <div class="row">
          <div class="col-xs-10 col-md-10" style="padding-right:0px;">
            <input type="text" class="form-control1" placeholder="Search By Transaction Id" id="searchfield" style="height:35px;">
          </div>
          <div class="col-xs-2 col-md-1" style="padding-left:0px;">
            <button class="btn btn-secondary" name="search" type="button" id="search">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <?php 
        $query=query("SELECT 
                        up.*,c.category_name,sc.sub_category_name FROM user_payments up
                      LEFT JOIN 
                        course_category c ON up.ccid=c.ccid
                      LEFT JOIN 
                        sub_category sc ON up.scid=sc.scid
                      ORDER BY id DESC");
        confirm($query);
      ?>
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr> 
          <th>S.No.</th>
          <th>Transaction Id</th>
          <th>Payumoney Id</th>
          <th>Course</th>
          <th>Sub-Course</th>
          <th>Plan Type</th>
          <th>Amount</th>
          <th>Name</th>
          <th>Email</th>
          <th>Mobile</th>
          <th>Payment Mode</th>
          <th>Payment Status</th>
          <th>Payment Time</th>
          <th colspan="2">Action</th>
        </tr>
      </thead>
      <tbody id="change_txntable">
<?php 
    $i=1;
		while($row=fetch_array($query))
		{
      $payment_time=date('d F Y h:i A',strtotime($row['payment_time']));

      $sub_cat="";

      if(isset($row['sub_category_name']))
        $sub_cat=$row['sub_category_name'];

      $txns="";

			$txns.=<<< txns
      <tr class='active'>
          <td>{$i}</td>
          <td>{$row['txnid']}</td>
          <td>{$row['payumoney_id']}</td>
          <td>{$row['category_name']}</td>
          <td>{$sub_cat}</td>
          <td>{$row['plan_type']}</td>
          <td>{$row['amount']}</td>
          <td>{$row['name']}</td>
          <td>{$row['email']}</td>
          <td>{$row['mobile']}</td>
          <td>{$row['payment_mode']}</td>
          <td>{$row['payment_status']}</td>
          <td>{$payment_time}</td>
txns;

      if($row['payment_status']!="SUCCESS")
      {
        $txns.=<<< txns
        <td>
          <form action="" method="post">
              <input type="hidden" name="utid" value="{$row['id']}">
              <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
              <input type="submit" class="btn btn-info" name="set_transaction_status" value="Payment Received" onClick="return confirm('Only click on this if you got the user payment. This will set the user transaction status to SUCCESS !')">
          </form>
        </td>
        <td>
          <form action="" method="post">
              <input type="hidden" name="utid" value="{$row['id']}">
              <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
              <input type="submit" class="btn btn-danger" name="delete_user_transaction" value="Remove Transaction" onClick="return confirm('Do you really want to remove this transaction ?')">
          </form>
        </td>
      </tr>
txns;
      }

      echo $txns;
      $i++;
		}

?>
      </tbody>
    </table>
    <div class="row">
      <div class="col-md-12 col-xs-12" style="overflow-x:auto;"></div>
    </div>
  </div>
  </div>

<script>
  $('#searchfield').keypress(
    function(event){
        if(event.which==13)
        {
            $('#search').click();
        }
    });
    
  $("#search").on("click",
    function()
    {
      txnid=$("#searchfield").val();

      $.get("ajax_show_transactions.php?txnid="+txnid,
        function(data,status)
        {
          $("#change_txntable").html(data);
        });
    });
</script>

</div>

<?php
  }
  else
    echo '<div class="bs-example4" data-example-id="contextual-table"><div class="row">
            <div class="col-12 text-center text-danger">
              <h4 style="margin-top:5em;">You Don\'t Have Permission To Access This Page</h4>
            </div>
          </div></div>';
?>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 
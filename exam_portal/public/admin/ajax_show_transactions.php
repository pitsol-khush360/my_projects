<?php include("../../resources/config.php"); ?>

<?php 
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
  if(isset($_GET['txnid'])) 
  {
      $query=query("SELECT 
                        up.*,c.category_name,sc.sub_category_name FROM user_payments up
                      LEFT JOIN 
                        course_category c ON up.ccid=c.ccid
                      LEFT JOIN 
                        sub_category sc ON up.scid=sc.scid
                      WHERE txnid='".$_GET['txnid']."' 
                      ORDER BY id DESC");
      confirm($query);

      $txns="";

      $i=1;
      while($row=fetch_array($query))
      {
        $payment_time=date('d F Y h:i A',strtotime($row['payment_time']));
        $sub_cat="";

        if(isset($row['sub_category_name']))
          $sub_cat=$row['sub_category_name'];

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
        $i++;
      }
      echo $txns;
  }
}
?>
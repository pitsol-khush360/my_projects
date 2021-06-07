<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2 && isset($_GET['search']))
	{
 		$sid=$_SESSION['user_id'];

		$data['sid']=$sid;
		$data['name']=$_GET['search'];

		$url=DOMAIN.'/rest/seller/getSellerPromosRest.php';

		$output=getRestApiResponse($url,$data);

		if(isset($output['promos']) && count($output['promos'])>2)
		{
			if(isset($output['promos']['rows']) && $output['promos']['rows']!=0)
			{
				$record="";
				for($i=0;$i<$output['promos']['rows'];$i++)
				{
		          $current=date("Y-m-d");
		          $status="";
		          if(new DateTime($output['promos'][$i]['expiry_date']) >= new DateTime($current))
		            $status="<p class='text-success'>Active</p>";
		          else
		            $status="<p class='text-danger'>Expired</p>";

		          $record.=<<< record
		          <tr>
		            <td>{$output['promos'][$i]['promo_code']}</td>
		            <td>{$output['promos'][$i]['expiry_date']}</td>
		            <td><i class="fa fa-inr"></i>&nbsp; {$output['promos'][$i]['minimum_order_amount']}</td>
		            <td>{$output['promos'][$i]['discount_type']}</td>
		            <td>{$output['promos'][$i]['discount_value']}</td>
		            <td>{$status}</td>

record;
		            if($output['promos'][$i]['is_active'] == "Yes")
		            {
		              $record.=<<< record
		              <td>
		                <input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" checked class="setstock" value="{$output['promos'][$i]['id']}">
		                </td>
record;
		            }
		            else
		            {
		              $record.=<<< record
		              <td>
		                <input type="checkbox" data-toggle="toggle" data-style="ios" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="secondary" class="setstock" value="{$output['promos'][$i]['id']}">
		                 </td>
record;
		            }
		              $record.=<<< record
		            <td>{$output['promos'][$i]['promos_applied']} Products</td>
		            <td>
		              <form action="displaySellerPromocode.php" method="post">
		                <input type="hidden" name="pid" value="{$output['promos'][$i]['id']}">
		                <button type="submit" name="edit_promocode_form" class="btn btn-primary">Edit</button>
		              </form>
		            </td>
		            <td>
		              <button type="button" class="btn btn-danger enabledeletepopup" pid="{$output['promos'][$i]['id']}" pname="{$output['promos'][$i]['promo_code']}"><i class="fa fa-trash"></i></button>
		            </td>

		          </tr>
record;
				}

				$record.='
				<script>$(function(){ $(".setstock").bootstrapToggle(); })</script>
				<script>
		          $(".enabledeletepopup").on("click",
		            function(){
		              pid=$(this).attr("pid");
		              $("#setpid").val(pid);
		              $("#delete-confirmation-modal").modal("show");
		          });

		          $(".setstock").change(
		            function(){
		              
		              value="No";
		              pid=$(this).val();

		              if($(this).prop("checked"))
		               value="Yes";
		              else
		               value="No";

		              var tobesend = "pid="+pid+"&value="+value;

		              $.ajax({
		                    type: "POST",
		                    url: "setSellerPromoInStockHelper.php",
		                    data: tobesend,
		                    dataType: "json",
		                    success: function(response)
		                    {
		                        if(response.status == 1)
		                        {
		                          $("#information").html("<p class=\"text-success\">Promocode state updated</p>");
		                          $("#information-modal").modal("show");
		                        }
		                        else
		                        {
		                          $("#information").html("<p class=\"text-danger\">Unable to update promocode state!</p>");
		                          $("#information-modal").modal("show");
		                        }
		                    }
		              });
		          });
		        </script>';

				echo $record;
			}
		}
	}
?>

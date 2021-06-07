<?php 
  require_once("../../config/config.php"); 
  require_once("../../config/".ENV."_config.php");
?>

<?php
if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2)
{
	if(isset($_GET['start']) && isset($_GET['end']))
	{
    $data['user_id']=$_SESSION['user_id'];
    $data['start']=$_GET['start'];
    $data['end']=$_GET['end'];

    $url=DOMAIN.'/rest/seller/getSellerOrderdetailsRest.php';

    $output=getRestApiResponse($url,$data);

    if(isset($output['getorders']) && count($output['getorders'])>2)
    {
      if(isset($output['getorders']['rows']) && $output['getorders']['rows']!=0)
      {
        $record="";

        for($i=0;$i<$output['getorders']['rows'];$i++)
        {
          $record.=<<< record
          <tr>
          <td>{$output['getorders'][$i]['basket_order_id']}</td>
          <td>{$output['getorders'][$i]['order_type']}</td>
          <td>{$output['getorders'][$i]['customer_name']}</td>
          <td>{$output['getorders'][$i]['total_items']}</td>
          <td>{$output['getorders'][$i]['net_amount']}</td>
          <td>{$output['getorders'][$i]['order_date']}</td>
record;

          if($output['getorders'][$i]['order_status']=="Pending")
          {
            $record.=<<< record
            <td><p class="text text-warning">{$output['getorders'][$i]['order_status']}</p></td>
            <td>
              <form action="displaySellerOrders.php" method="post">
                <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
                <input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
                <button type="submit" name="orderitems" class="btn btn-primary">View</button>
              </form>
            </td>
            <td>
              <button class="btn btn-success enable-order-confirmation-modal" order_id="{$output['getorders'][$i]['basket_order_id']}" order_type="{$output['getorders'][$i]['order_type']}" order_status="Accepted" order_info="Do you really want to accept this order?" order_button="Accept">Accept</button>
            </td>
            <td>
              <button class="btn btn-danger enable-order-confirmation-modal" order_id="{$output['getorders'][$i]['basket_order_id']}" order_type="{$output['getorders'][$i]['order_type']}" order_status="Declined" order_info="Do you really want to decline this order?" order_button="Decline">Decline</button>
            </td>
record;
          }
          else if($output['getorders'][$i]['order_status']=="Accepted")
          {
            $record.=<<< record
            <td><p class="text text-success">{$output['getorders'][$i]['order_status']}</p></td>
            <td>
            <form action="displaySellerOrders.php" method="post">
              <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
              <input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
              <button type="submit" name="orderitems" class="btn btn-primary">View</button>
            </form>
            </td>
            <td>
              <button class="btn btn-info enable-order-confirmation-modal" order_id="{$output['getorders'][$i]['basket_order_id']}" order_type="{$output['getorders'][$i]['order_type']}" order_status="Shipped" order_info="Do you really want to ship this order?" order_button="Ship">Ship</button>
            </td>
            <td>
              <button class="btn btn-danger enable-order-confirmation-modal" order_id="{$output['getorders'][$i]['basket_order_id']}" order_type="{$output['getorders'][$i]['order_type']}" order_status="Declined" order_info="Do you really want to decline this order?" order_button="Decline">Decline</button>
            </td>
record;
          }
          else if($output['getorders'][$i]['order_status']=="Declined")
          {
            $record.=<<< record
            <td><p class="text text-danger">{$output['getorders'][$i]['order_status']}</p></td>
            <td>
            <form action="displaySellerOrders.php" method="post">
              <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
              <input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
              <button type="submit" name="orderitems" class="btn btn-primary">View</button>
            </form>
            </td>
            <td></td><td></td>
record;
          }
          else if($output['getorders'][$i]['order_status']=="Shipped")
          {
            $record.=<<< record
            <td><p class="text text-info">{$output['getorders'][$i]['order_status']}</p></td>
            <td>
            <form action="displaySellerOrders.php" method="post">
              <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
              <input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
              <button type="submit" name="orderitems" class="btn btn-primary">View</button>
            </form>
            </td>
            <td>
              <button class="btn btn-success enable-order-confirmation-modal" order_id="{$output['getorders'][$i]['basket_order_id']}" order_type="{$output['getorders'][$i]['order_type']}" order_status="Delivered" order_info="Do you really want to deliver this order?" order_button="Deliver">Deliver</button>
            </td>
            <td>
              <button class="btn btn-danger enable-order-confirmation-modal" order_id="{$output['getorders'][$i]['basket_order_id']}" order_type="{$output['getorders'][$i]['order_type']}" order_status="Returned" order_info="Do you really want to return this order?" order_button="Return">Return</button>
            </td>
record;
          }
          else if($output['getorders'][$i]['order_status']=="Delivered")
          {
            $record.=<<< record
            <td><p class="text text-success">{$output['getorders'][$i]['order_status']}</p></td>
            <td>
            <form action="displaySellerOrders.php" method="post">
              <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
              <input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
              <button type="submit" name="orderitems" class="btn btn-primary">View</button>
            </form>
            </td>
            <td></td><td></td>
record;
          }
          else
          {
            $record.=<<< record
            <td><p class="text text-danger">{$output['getorders'][$i]['order_status']}</p></td>
            <td>
            <form action="displaySellerOrders.php" method="post">
              <input type="hidden" name="oid" value="{$output['getorders'][$i]['basket_order_id']}">
              <input type="hidden" name="orderdate" value="{$output['getorders'][$i]['order_date']}">
              <button type="submit" name="orderitems" class="btn btn-primary">View</button>
            </form>
            </td>
            <td></td><td></td>
record;
          }

          $record.=<<< record
        </tr>
record;
        }

        $record.='
        <script>
          $(".enable-order-confirmation-modal").on("click",
            function(){
              oid=$(this).attr("order_id");
              otype=$(this).attr("order_type");
              ostatus=$(this).attr("order_status");
              oinfo=$(this).attr("order_info");
              obutton=$(this).attr("order_button");

              $("#setoid").val(oid);
              $("#setotype").val(otype);
              $("#setostatus").val(ostatus);

              $("#order-info").html(oinfo);
              $("#order-button").text(obutton);
              $("#order-confirmation-modal").modal("show");
          });
        </script>';
        
        echo $record;
      }
    }
  }
}
?>
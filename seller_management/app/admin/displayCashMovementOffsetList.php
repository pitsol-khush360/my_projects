 
<?php 
  require_once("../../config/config.php"); 
  require_once("../../config/".ENV."_config.php");
?>
<?php include("navigation.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <title>cash movement-offset</title>
 <style>
        table.table-bordered>tbody>tr>td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
<form action="displayCashMovementOffsetList.php" method="post">
<select id="pages" class="btn btn-primary" placeholder="">
      <option value="1">1</option>
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </select>
    Show Entries
  <div class="input-group w-50" style = "float:right">
        <input type="text" class="form-control" placeholder="Search" name="searchfield" style="border: medium solid black;">
          <div class="input-group-append">
            <button class="btn btn-secondary" type="submit" id="search" style="border: 1px solid black;">
              <i class="fa fa-search"></i>
            </button>
          </div>
          <select name="key" id="keyword" placeholder="choose one" class = "btn btn-primary" style = "margin-left: 10px;">
            <option value="cash_movement_id">Transaction Id</option>
            <option value="seller_id">Seller Id</option>
            <option value="order_id">Order Id</option>
            <option value="movement_status">Status</option> 
          </select>
  </div>
   
</form>



<!--Starting of the table-->
<div class="pt-3 pl-3">
  <table class="table table-hover table-responsive-lg table-sm table-bordered">
  <thead style='background-color : #36404e;color : white'>
    <tr>
      <th>Id</th>
      <th style="text-align: right;">Opening Balance</th>
      <th style="text-align: right;">Amount</th>
      <th style="text-align: right;">Closing Balance</th>
      <th>Movement Date</th>
      <th>Movement Type</th>
      <th>Description</th>
      <th>Seller Id</th>
      <th>Order Id</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <!-- Connecting with RestApi-->

 <?php
  $data=array();
  if(isset($_POST['key']) && isset($_POST['searchfield'])){
    $data[$_POST['key']]=$_POST['searchfield'];
}

else{

$data='';

}
$url=DOMAIN.'/rest/admin/getCashMovementScreenOffsetRest.php';
 $output=getRestApiResponse($url,$data);

 $record =''; 
  if(isset($output['getcashmovementoffset']) && $output['getcashmovementoffset']['response_code']==200)
  {
    for($i=0;$i<$output['getcashmovementoffset']['rows'];$i++)
    {
     $record.=<<< record
      <tr>
        <td>{$output['getcashmovementoffset'][$i]['cash_movement_id']}</td>
        <td style="text-align: right;">{$output['getcashmovementoffset'][$i]['opening_balance']}</td>
        <td style="text-align: right;">{$output['getcashmovementoffset'][$i]['amount']}</td>
        <td style="text-align: right;">{$output['getcashmovementoffset'][$i]['closing_balance']}</td>
        <td>{$output['getcashmovementoffset'][$i]['movement_date']}</td>
        <td>{$output['getcashmovementoffset'][$i]['movement_type']}</td>
        <td>{$output['getcashmovementoffset'][$i]['movement_description']}</td>
        <td>{$output['getcashmovementoffset'][$i]['seller_id']}</td>
        <td>{$output['getcashmovementoffset'][$i]['order_id']}</td>
        <td>{$output['getcashmovementoffset'][$i]['movement_status']}</td>
      </tr>      
record;
  }
echo $record;
}
else{
    if(isset($output['getcashmovementoffset']) && $output['getcashmovementoffset']['response_code']==405){
      $record.=<<< record
        <h3>{$output['getcashmovementoffset']['response_desc']}</h3>
      
record;
      }

  echo $record;
    }
?>
  
  </tbody>
</table>
</div>
<?php include("footer.php"); ?>
 <script>
    $(document).ready(() => {
            if ( window.history.replaceState ) {
              window.history.replaceState( null, null, window.location.href );
            }
        });
 </script>
</body>
</html>

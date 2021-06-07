 
<?php 
  require_once("../../config/config.php"); 
  require_once("../../config/".ENV."_config.php");
?>
<?php include("navigation.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Collection list</title>
  <!--CSS-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
   <!--font Awesome-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
        table.table-bordered>tbody>tr>td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
<form action="displayPromocodeList.php" method="post">
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
          <select name="key" id="keyword" placeholder="choose one" class = "btn btn-primary" style = "margin-left: 10px">
            <option value="seller_id">Seller Id</option>
            <option value="catalogue_id">Callection Id</option>
            <option value="product_id">product Id</option>
            <option value="promo_code">Promocode</option>
            <option value="is_active">Status</option> 
          </select>
  </div>
   
</form>
 


<!--Starting of the table-->
<div class="pt-3 pl-3">
  <table class="table table-hover table-responsive-lg table-sm table-bordered">
  <thead style='background-color : #36404e;color : white'>
    <tr>
      <th>Seller Id</th>
      <th>Collection Id</th>
      <th>Product Id</th>
      <th>Promocode</th>
      <th style="text-align : right;">Min Oder Amount</th>
      <th>Discount Type</th>
      <th style="text-align : right;">Discount Value</th>
      <th>Status</th>
      <th>Expiray Date</th>
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
$url=DOMAIN.'/rest/admin/getPromocodeListScreenRest.php';
 $output=getRestApiResponse($url,$data);

 $record =''; 
  if(isset($output['getpromocodedetails']) && $output['getpromocodedetails']['response_code']==200)
  {
    for($i=0;$i<$output['getpromocodedetails']['rows'];$i++)
    {
     $record.=<<< record
      <tr>
        <td>{$output['getpromocodedetails'][$i]['seller_id']}</td>
        <td>{$output['getpromocodedetails'][$i]['catalogue_id']}</td>
        <td>{$output['getpromocodedetails'][$i]['product_id']}</td>
        <td>{$output['getpromocodedetails'][$i]['promo_code']}</td>
        <td style="text-align : right;">{$output['getpromocodedetails'][$i]['minimum_order_amount']}</td>
        <td>{$output['getpromocodedetails'][$i]['discount_type']}</td>
        <td style="text-align : right;">{$output['getpromocodedetails'][$i]['discount_value']}</td>
        <td>{$output['getpromocodedetails'][$i]['is_active']}</td>
        <td>{$output['getpromocodedetails'][$i]['expiry_date']}</td>
      </tr>      
record;
  }
echo $record;
}
else{
    if(isset($output['getpromocodedetails']) && $output['getpromocodedetails']['response_code']==405){
      $record.=<<< record
        <h3>{$output['getpromocodedetails']['response_desc']}</h3>
      
record;
      }

  echo $record;
    }
?>
  
  </tbody>
</table>
</div>
<?php include("footer.php"); ?>
<!--jQuery-->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 
 <!--Popper.js-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
 
 <!--JavaScript plugins-->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
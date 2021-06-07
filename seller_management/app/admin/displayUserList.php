 
<?php 
  require_once("../../config/config.php"); 
  require_once("../../config/".ENV."_config.php");
?>
<!DOCTYPE html>
<html>
<head>
  <title>user-list</title>
  <!--CSS-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
   <!--font Awesome-->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  
</head>
<body>
<form action="displayUserList.php" method="post">
  <div class="row mt-5 pl-3">
    <div class="col-5 col-md-6">
      Show
      <select id="pages" placeholder="">
        <option value="1">1</option>
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
      Entries
    </div>
    
    <select name="key" id="keyword" placeholder="choose one">
        <option value="user_id">User Id</option>
        <option value="username">User Name</option>
        <option value="mobile">Mobile No</option>
        <option value="status">Status</option>
    </select>
    <div class="col-7 offset-md-2 col-md-4 pr-5">
      <div class="input-group">
          <input type="text" class="form-control" placeholder="Search" name="searchfield" style="border: medium solid black;">
          <div class="input-group-append">
            <button class="btn btn-secondary" type="submit" id="search" style="border: 1px solid black;">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
    </div>
  </div>
</form>


<!--Starting of the table-->
<div class="pt-3 pl-3">
  <table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">User Id</th>
      <th scope="col">Role</th>
      <th scope="col">User Name</th>
      <th scope="col">Mobile No</th>
      <th scope="col">Business/store Name</th>
      <th scope="col">Status</th>
      <th scope="col">Mobile verified</th>
      <th scope="col">Date</th>
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
$url=DOMAIN.'/rest/admin/getUserListScreenRest.php';
 $output=getRestApiResponse($url,$data);

 $record =''; 
  if(isset($output['getuserdetails']) && $output['getuserdetails']['response_code']==200)
  {
    for($i=0;$i<$output['getuserdetails']['rows'];$i++)
    {
      if($output['getuserdetails'][$i]['mobile_verified'] != 'Yes'){
            $output['getuserdetails'][$i]['mobile_verified'] = 'No';
      }
      $record.=<<< record
      <tr>
        <td>{$output['getuserdetails'][$i]['user_id']}</td>
        <td>{$output['getuserdetails'][$i]['role']}</td>
        <td>{$output['getuserdetails'][$i]['username']}</td>
        <td>{$output['getuserdetails'][$i]['mobile']}</td>
        <td>{$output['getuserdetails'][$i]['business_name']}</td>
        <td>{$output['getuserdetails'][$i]['status']}</td>
        <td>{$output['getuserdetails'][$i]['mobile_verified']}</td>
        <td>{$output['getuserdetails'][$i]['created_datetime']}</td>
      </tr>      
     record;
    }
  }
  echo $record;

  ?>
  
  </tbody>
</table>
</div>

<!--jQuery-->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 
 <!--Popper.js-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
 
 <!--JavaScript plugins-->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>
</html>
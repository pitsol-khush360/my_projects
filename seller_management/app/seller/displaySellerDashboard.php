<?php include("navigation.php"); ?>

<?php
  if(!(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2))
    redirect("login.php");
?>

<div class="container-fluid">
    <!-- Journey -->
<?php
    $data['user_id']=$_SESSION['user_id'];
    if(isset($_POST['submitdate']))
        $data['date']=$_POST['searchdate'];

    $url=DOMAIN.'/rest/seller/getSellerDashBoardCountRest.php';
    $output=getRestApiResponse($url,$data);
?>
<div class="row mt-2">
    <div class="col-12 col-md-5 text-center">
        <div class="row mt-3">
            <div class="col-12">
                <h4>Your Journey</h4>
            </div>
            <div class="col-12 mt-5">
                <div class="row" style="font-size:13px;">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-3 text-center">
                                <b>Register</b>
                            </div>
                            <div class="col-3 text-center">
                                <b>Add Collections</b>
                            </div>
                            <div class="col-3 text-center">
                                <b>Add Products</b>
                            </div>
                            <div class="col-3 text-center">
                                <b>Share Store</b>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-3 text-center d-flex justify-content-center">
                                <?php
                                    if(isset($_SESSION['username']))
                                        echo '<div class="rounded-circle border border-dark" style="width:50px;height:50px;"><i class="fas fa-check fa-2x text-success mt-2"></i></div>';
                                    else
                                        echo '<div class="rounded-circle border border-dark" style="width:50px;height:50px;"><i class="fas fa-times fa-2x text-danger mt-2"></i></div>';
                                ?>
                            </div>
                            <div class="col-3 text-center d-flex justify-content-center">
                                <?php
                                    if(isset($output['getdashboard']['catalogues']) && $output['getdashboard']['catalogues']!=0)
                                        echo '<div class="rounded-circle border border-dark" style="width:50px;height:50px;"><i class="fas fa-check fa-2x text-success mt-2"></i></div>';
                                    else
                                        echo '<div class="rounded-circle border border-dark" style="width:50px;height:50px;"><i class="fas fa-times fa-2x text-danger mt-2"></i></div>';
                                ?>
                            </div>
                            <div class="col-3 text-center d-flex justify-content-center">
                                <?php
                                    if(isset($output['getdashboard']['products']) && $output['getdashboard']['products']!=0)
                                        echo '<div class="rounded-circle border border-dark" style="width:50px;height:50px;"><i class="fas fa-check fa-2x text-success mt-2"></i></div>';
                                    else
                                        echo '<div class="rounded-circle border border-dark" style="width:50px;height:50px;"><i class="fas fa-times fa-2x text-danger mt-2"></i></div>';
                                ?>
                            </div>
                            <div class="col-3 text-center d-flex justify-content-center">
                                <?php
                                    if(isset($_SESSION['username']))
                                        echo '<div class="rounded-circle border border-dark" style="width:50px;height:50px;"><i class="fas fa-check fa-2x text-success mt-2"></i></div>';
                                    else
                                        echo '<div class="rounded-circle border border-dark" style="width:50px;height:50px;"><i class="fas fa-times fa-2x text-danger mt-2"></i></div>';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sale Graph -->
    <?php
        $data1['user_id']=$_SESSION['user_id'];
        $url=DOMAIN.'/rest/seller/getSellerOrdersReportsRest.php';
        $output1=getRestApiResponse($url,$data1);

        if(isset($output1['getdashboard']) && $output1['getdashboard']['response_code']==200)
        {
    ?>
    <div class="col-12 col-md-7 mt-5 mt-md-0">
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
          <script type="text/javascript">
            google.charts.load("current", {packages:['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
              var data = google.visualization.arrayToDataTable([
                ["Element", "Total Sales", { role: "style" } ],
                <?php
                    if(isset($output1['getdashboard']))
                    {
                        $sum=0;
                        $count=0;
                        foreach($output1['getdashboard'] as $v)
                        {
                            $count++;

                            if($count==7)
                                break;
                            $sum+=$v;
                        }
                        $avg=$sum/7;

                        $count=0;
                        foreach($output1['getdashboard'] as $k => $v)
                        {
                            $count++;

                            if($count!=7)
                            {
                                if($v>$avg)
                                    echo '["'.$k.'",'.$v.',"green"],';
                                else
                                if($v<$avg)
                                    echo '["'.$k.'",'.$v.',"red"],';
                                else
                                    echo '["'.$k.'",'.$v.',"orange"],';
                            }
                            else
                            {
                                if($v>$avg)
                                    echo '["'.$k.'",'.$v.',"green"]';
                                else
                                if($v<$avg)
                                    echo '["'.$k.'",'.$v.',"red"]';
                                else
                                    echo '["'.$k.'",'.$v.',"orange"]';

                                break;
                            }
                        }
                    }
                ?>
              ]);

              var view = new google.visualization.DataView(data);
              view.setColumns([0, 1,
                               { calc: "stringify",
                                 sourceColumn: 1,
                                 type: "string",
                                 role: "annotation" },
                               2]);

              var options = {
                title: "Your Total Sales For Last 7 Days",
                width: 500,
                height: 300,
                bar: {groupWidth: "70%"},
                legend: { position: "none" },
              };
              var chart = new google.visualization.ColumnChart(document.getElementById("sale_graph"));
              chart.draw(view, options);
          }
          </script>
        <div id="sale_graph"></div>

    </div>
    <?php
        }
    ?>
    <!-- sale graph ends -->
</div>
    <hr>

    <div class="row mt-3">
        <div class="col-9 col-md-3">
            <form action="" method="post">
              <div class="input-group">
                <?php
                    $old="";

                    if(isset($_POST['submitdate']))
                        $current=$_POST['searchdate'];
                    else
                        $current = date("Y-m-d");
                ?>
                <input type="date" name="searchdate" class="form-control" value="<?php echo $current; ?>">
                <div class="input-group-append">
                  <button class="btn btn-secondary" type="submit" name="submitdate">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
                </div>
            </form>
        </div>
        <div class="col-12 offset-md-3 col-md-6 text-md-right mt-2 mt-md-0">
                <?php
                    $storelink=DOMAIN."/app/?s=".$_SESSION['username'];
                    
                    $text="Check out ".$_SESSION['business_name']."'s latest product collection %0DOrder 24x7 - Click on the link to place an order%0D%0D".$storelink.".\n%20%0D%0D";
                ?>
              <a href="<?php echo $storelink; ?>" target="_blank"><?php echo $storelink; ?></a>&nbsp;<a href="https://api.whatsapp.com/send?text=<?php echo $text; ?>" target="_blank" title="Share Your Store Link On Whatsapp"><i class="fab fa-whatsapp fa-2x text-success ml-2"></i></a>
        </div>
    </div>

      <div class="row mt-3">
         <div class="col-6 col-md-3 mt-3">
             <div class="card">
                 <div class="card-body bg-warning">
                     <div class="row text-dark">
                         <div class="col-12 text-center">
                             <i class="fas fa-question fa-3x"></i>
                         </div>
                         <div class="col-12">
                            <div class="row">
                              <div class="col-7 col-md-9 mt-3">
                                <p>Pending Orders</p>
                              </div>
                              <div class="col-5 col-md-3 mt-4 mt-md-3 text-right">
                                <h6>
                                    <?php
                                        if(isset($output['getdashboard']['pending']))
                                            echo $output['getdashboard']['pending'];
                                    ?>
                                </h6>
                              </div>
                            </div>
                         </div>
                     </div>
                 </div>
                 <a href="displaySellerOrders.php?orderstatus=Pending">
                     <div class="card-footer">
                         <span class="pull-left">View Details</span>
                         <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                         <div class="clearfix"></div>
                     </div>
                 </a>
             </div>
          </div>
          <div class="col-6 col-md-3 mt-3">
             <div class="card">
                 <div class="card-body bg-success">
                     <div class="row text-dark">
                         <div class="col-12 text-center">
                             <i class="fas fa-check fa-3x"></i>
                         </div>
                         <div class="col-12">
                            <div class="row">
                              <div class="col-7 col-md-9 mt-3">
                                <p>Accepted Orders</p>
                              </div>
                              <div class="col-5 col-md-3 mt-4 mt-md-3 text-right">
                                <h6>
                                    <?php
                                        if(isset($output['getdashboard']['accepted']))
                                            echo $output['getdashboard']['accepted'];
                                    ?>
                                </h6>
                              </div>
                            </div>
                         </div>
                     </div>
                 </div>
                 <a href="displaySellerOrders.php?orderstatus=Accepted">
                     <div class="card-footer">
                         <span class="pull-left">View Details</span>
                         <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                         <div class="clearfix"></div>
                     </div>
                 </a>
             </div>
           </div>
           <div class="col-6 col-md-3 mt-3">
             <div class="card">
                 <div class="card-body bg-info">
                     <div class="row text-dark">
                         <div class="col-12 text-center">
                             <i class="fas fa-gift fa-3x"></i>
                         </div>
                         <div class="col-12">
                            <div class="row">
                              <div class="col-7 col-md-9 mt-3">
                                <p>Shipped Orders</p>
                              </div>
                              <div class="col-5 col-md-3 mt-4 mt-md-3 text-right">
                                <h6>
                                    <?php
                                        if(isset($output['getdashboard']['shipped']))
                                            echo $output['getdashboard']['shipped'];
                                    ?>
                                </h6>
                              </div>
                            </div>
                         </div>
                     </div>
                 </div>
                 <a href="displaySellerOrders.php?orderstatus=Shipped">
                     <div class="card-footer">
                         <span class="pull-left">View Details</span>
                         <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                         <div class="clearfix"></div>
                     </div>
                 </a>
             </div>
            </div>
            <div class="col-6 col-md-3 mt-3">
             <div class="card">
                 <div class="card-body bg-success">
                     <div class="row text-dark">
                         <div class="col-12 text-center">
                             <i class="fas fa-truck fa-3x"></i>
                         </div>
                         <div class="col-12">
                            <div class="row">
                              <div class="col-7 col-md-9 mt-3">
                                <p>Delivered Orders</p>
                              </div>
                              <div class="col-5 col-md-3 mt-4 mt-md-3 text-right">
                                <h6>
                                    <?php
                                        if(isset($output['getdashboard']['delivered']))
                                            echo $output['getdashboard']['delivered'];
                                    ?>
                                </h6>
                              </div>
                            </div>
                         </div>
                     </div>
                 </div>
                 <a href="displaySellerOrders.php?orderstatus=Delivered">
                     <div class="card-footer">
                         <span class="pull-left">View Details</span>
                         <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                         <div class="clearfix"></div>
                     </div>
                 </a>
             </div>
            </div>
          <div class="col-6 col-md-3 mt-3">
             <div class="card">
                 <div class="card-body bg-danger">
                     <div class="row text-dark">
                         <div class="col-12 text-center">
                             <i class="fas fa-times fa-3x"></i>
                         </div>
                         <div class="col-12">
                            <div class="row">
                              <div class="col-7 col-md-9 mt-3">
                                <p>Cancelled Orders</p>
                              </div>
                              <div class="col-5 col-md-3 mt-4 mt-md-3 text-right">
                                <h6>
                                    <?php
                                        if(isset($output['getdashboard']['cancelled']))
                                            echo $output['getdashboard']['cancelled'];
                                    ?>
                                </h6>
                              </div>
                            </div>
                         </div>
                     </div>
                 </div>
                 <a href="displaySellerOrders.php?orderstatus=Cancelled">
                     <div class="card-footer">
                         <span class="pull-left">View Details</span>
                         <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                         <div class="clearfix"></div>
                     </div>
                 </a>
             </div>
            </div>
            <div class="col-6 col-md-3 mt-3">
             <div class="card">
                 <div class="card-body bg-primary">
                     <div class="row text-dark">
                         <div class="col-12 text-center">
                             <i class="fas fa-shopping-cart fa-3x"></i>
                         </div>
                         <div class="col-12">
                            <div class="row">
                              <div class="col-6 col-md-6 mt-3">
                                <p>Total Sales</p>
                              </div>
                              <div class="col-6 col-md-6 mt-4 mt-md-3 text-right">
                                <h6>
                                    <?php
                                        if(isset($output['getdashboard']['total_sales']))
                                            echo $output['getdashboard']['total_sales'];
                                    ?>
                                </h6>
                              </div>
                            </div>
                         </div>
                     </div>
                 </div>
                 <a href="#">
                     <div class="card-footer">
                         <span class="pull-left">View Details</span>
                         <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                         <div class="clearfix"></div>
                     </div>
                 </a>
             </div>
            </div>
            <div class="col-6 col-md-3 mt-3">
             <div class="card">
                 <div class="card-body bg-warning">
                     <div class="row text-dark">
                         <div class="col-12 text-center">
                             <i class="fas fa-layer-group fa-3x"></i>
                         </div>
                         <div class="col-12">
                            <div class="row">
                              <div class="col-7 col-md-9 mt-3">
                                <p>Total Collections</p>
                              </div>
                              <div class="col-5 col-md-3 mt-4 mt-md-3 text-right">
                                <h6>
                                    <?php
                                        if(isset($output['getdashboard']['catalogues']))
                                            echo $output['getdashboard']['catalogues'];
                                    ?>
                                </h6>
                              </div>
                            </div>
                         </div>
                     </div>
                 </div>
                 <a href="displaySellerCatalogues.php">
                     <div class="card-footer">
                         <span class="pull-left">View Details</span>
                         <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                         <div class="clearfix"></div>
                     </div>
                 </a>
             </div>
            </div>
            <div class="col-6 col-md-3 mt-3">
             <div class="card">
                 <div class="card-body bg-success">
                     <div class="row text-dark">
                         <div class="col-12 text-center">
                             <i class="fas fa-list-alt fa-3x"></i>
                         </div>
                         <div class="col-12">
                            <div class="row">
                              <div class="col-7 col-md-9 mt-3">
                                <p>Total Products</p>
                              </div>
                              <div class="col-5 col-md-3 mt-4 mt-md-3 text-right">
                                <h6>
                                    <?php
                                        if(isset($output['getdashboard']['products']))
                                            echo $output['getdashboard']['products'];
                                    ?>
                                </h6>
                              </div>
                            </div>
                         </div>
                     </div>
                 </div>
                 <a href="displaySellerProducts.php">
                     <div class="card-footer">
                         <span class="pull-left">View Details</span>
                         <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                         <div class="clearfix"></div>
                     </div>
                 </a>
             </div>
         </div>
         <div class="col-6 col-md-3 mt-3">
             <div class="card">
                 <div class="card-body bg-info">
                     <div class="row text-dark">
                         <div class="col-12 text-center">
                             <i class="fas fa-ticket-alt fa-3x"></i>
                         </div>
                         <div class="col-12">
                            <div class="row">
                              <div class="col-6 col-md-9 mt-3">
                                <p>Open Tickets</p>
                              </div>
                              <div class="col-6 col-md-3 mt-4 mt-md-3 text-right">
                                <h6>
                                    <?php
                                        if(isset($output['getdashboard']['open']))
                                            echo $output['getdashboard']['open'];
                                    ?>
                                </h6>
                              </div>
                            </div>
                         </div>
                     </div>
                 </div>
                 <a href="displayContactUsForSeller.php">
                     <div class="card-footer">
                         <span class="pull-left">View Details</span>
                         <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                         <div class="clearfix"></div>
                     </div>
                 </a>
             </div>
            </div>
            <div class="col-6 col-md-3 mt-3">
             <div class="card">
                 <div class="card-body bg-info">
                     <div class="row text-dark">
                         <div class="col-12 text-center">
                             <i class="fas fa-ticket-alt fa-3x"></i>
                         </div>
                         <div class="col-12">
                            <div class="row">
                              <div class="col-9 col-md-9 mt-3">
                                <p>Reopen Tickets</p>
                              </div>
                              <div class="col-3 col-md-3 mt-4 mt-md-3 text-right">
                                <h6>
                                    <?php
                                        if(isset($output['getdashboard']['reopen']))
                                            echo $output['getdashboard']['reopen'];
                                    ?>
                                </h6>
                              </div>
                            </div>
                         </div>
                     </div>
                 </div>
                 <a href="displayContactUsForSeller.php">
                     <div class="card-footer">
                         <span class="pull-left">View Details</span>
                         <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                         <div class="clearfix"></div>
                     </div>
                 </a>
             </div>
            </div>
       </div>
       <hr>

    <!-- orders -->
       <div class="row">
        <div class="col-12">
            <h5>Orders</h5>
        </div>
        </div>
        <hr>
       <div class="row">
        <div class="col-12">
            <!-- Tab list -->
            <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Pending</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="accepted-tab" data-toggle="tab" href="#accepted" role="tab" aria-controls="accepted" aria-selected="false">Accepted</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="shipped-tab" data-toggle="tab" href="#shipped" role="tab" aria-controls="shipped" aria-selected="false">Shipped</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="delivered-tab" data-toggle="tab" href="#delivered" role="tab" aria-controls="delivered" aria-selected="false">Delivered</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="returned-tab" data-toggle="tab" href="#returned" role="tab" aria-controls="returned" aria-selected="false">Returned</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="cancelled-tab" data-toggle="tab" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="declined-tab" data-toggle="tab" href="#declined" role="tab" aria-controls="declined" aria-selected="false">Declined</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                <?php
                    $data2['user_id']=$_SESSION['user_id'];
                    $data2['order_status']="Pending";
                    $url=DOMAIN.'/rest/seller/getSellerOrderdetailsRest.php';
                    $output2=getRestApiResponse($url,$data2);

                    if(isset($output2['getorders']) && $output2['getorders']['rows']!=0)
                        displayOrdersInDashboard($output2);
                    else
                        echo "<h5 class='text-center text-danger mt-5'>No Pending Orders</h5>";
                ?>
              </div>
              <div class="tab-pane fade" id="accepted" role="tabpanel" aria-labelledby="accepted-tab">
                <?php
                    $data2['user_id']=$_SESSION['user_id'];
                    $data2['order_status']="Accepted";
                    $url=DOMAIN.'/rest/seller/getSellerOrderdetailsRest.php';
                    $output2=getRestApiResponse($url,$data2);
                    
                    if(isset($output2['getorders']) && $output2['getorders']['rows']!=0)
                        displayOrdersInDashboard($output2);
                    else
                        echo "<h5 class='text-center text-danger mt-5'>No Accepted Orders</h5>";
                ?>
              </div>
              <div class="tab-pane fade" id="shipped" role="tabpanel" aria-labelledby="shipped-tab">
                <?php
                    $data2['user_id']=$_SESSION['user_id'];
                    $data2['order_status']="Shipped";
                    $url=DOMAIN.'/rest/seller/getSellerOrderdetailsRest.php';
                    $output2=getRestApiResponse($url,$data2);
                    
                    if(isset($output2['getorders']) && $output2['getorders']['rows']!=0)
                        displayOrdersInDashboard($output2);
                    else
                        echo "<h5 class='text-center text-danger mt-5'>No Shipped Orders</h5>";
                ?>
              </div>
              <div class="tab-pane fade" id="delivered" role="tabpanel" aria-labelledby="delivered-tab">
                <?php
                    $data2['user_id']=$_SESSION['user_id'];
                    $data2['order_status']="Delivered";
                    $url=DOMAIN.'/rest/seller/getSellerOrderdetailsRest.php';
                    $output2=getRestApiResponse($url,$data2);
                    
                    if(isset($output2['getorders']) && $output2['getorders']['rows']!=0)
                        displayOrdersInDashboard($output2);
                    else
                        echo "<h5 class='text-center text-danger mt-5'>No Delivered Orders</h5>";
                ?>
              </div>
              <div class="tab-pane fade" id="returned" role="tabpanel" aria-labelledby="returned-tab">
                <?php
                    $data2['user_id']=$_SESSION['user_id'];
                    $data2['order_status']="Returned";
                    $url=DOMAIN.'/rest/seller/getSellerOrderdetailsRest.php';
                    $output2=getRestApiResponse($url,$data2);
                    
                    if(isset($output2['getorders']) && $output2['getorders']['rows']!=0)
                        displayOrdersInDashboard($output2);
                    else
                        echo "<h5 class='text-center text-danger mt-5'>No Returned Orders</h5>";
                ?>
              </div>
              <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                <?php
                    $data2['user_id']=$_SESSION['user_id'];
                    $data2['order_status']="Cancelled";
                    $url=DOMAIN.'/rest/seller/getSellerOrderdetailsRest.php';
                    $output2=getRestApiResponse($url,$data2);
                    
                    if(isset($output2['getorders']) && $output2['getorders']['rows']!=0)
                        displayOrdersInDashboard($output2);
                    else
                        echo "<h5 class='text-center text-danger mt-5'>No Cancelled Orders</h5>";
                ?>
              </div>
              <div class="tab-pane fade" id="declined" role="tabpanel" aria-labelledby="declined-tab">
                <?php
                    $data2['user_id']=$_SESSION['user_id'];
                    $data2['order_status']="Declined";
                    $url=DOMAIN.'/rest/seller/getSellerOrderdetailsRest.php';
                    $output2=getRestApiResponse($url,$data2);
                    
                    if(isset($output2['getorders']) && $output2['getorders']['rows']!=0)
                        displayOrdersInDashboard($output);
                    else
                        echo "<h5 class='text-center text-danger mt-5'>No Declined Orders</h5>";
                ?>
              </div>
            </div>
        </div>
       </div>
  </div>    <!-- container ends -->

        </div>
        <!-- content end -->
    </div>
    <!-- page content -->
</div>
<!-- page wrapper end -->

<?php include("footer.php"); ?>

<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>

  </body>
</html>

 <?php 
        if(isset($_SESSION['user_name']))
        {
            $query=query("select userid from users where username like '".escape_string($_SESSION['user_name'])."'");
            confirm($query);
            $row=fetch_array($query);
            $userid=$row['userid'];
        }
?>


 <!-- FIRST ROW WITH PANELS -->

                <!-- /.row -->
                <div class="row">
                            <div class="col-lg-4 col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-shopping-cart fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">
                                <?php count_for_user_panel("orders",$userid); ?>
                                        </div>
                                        <div>My Orders</div>
                                    </div>
                                </div>
                            </div>
                            <a href="#">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
        
                
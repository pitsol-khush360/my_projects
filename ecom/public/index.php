<?php require_once("../resources/config.php");  ?>
<?php include(TEMPLATE_FRONT.DS."header.php");  ?>

<div id="search_result_container" style="position:fixed;margin-left:49%;margin-top:-20px;width:250px;display:none;background-color:yellow;z-index: 1;">
    <ul id="search_result_items" type="none">
    </ul>
</div>

<script type="text/javascript">
    $("#search").keyup(function()
    {
        $keywords=$("input:text").val();

        $.get("search_results.php?search_keywords="+$keywords,
            function(data,message)
            {
                $("#search_result_container").css("display","inline-block");
                $("#search_result_items").html(data);
            }
        );
    }
    );
</script>
<!-- Page Content -->
<div class="container">

        <div class="row">
        <!-- include categories here -->
        <?php include(TEMPLATE_FRONT.DS."side_nav.php"); ?>

            <div class="col-md-9">

                <div class="row carousel-holder">
                    <div class="col-md-12">
                        <!-- include carousel -->
                        <?php include(TEMPLATE_FRONT.DS."slider.php");  ?>
                    </div>
                </div>

                <div class="row" >
                    <?php
                      getproducts();
                    ?>
                </div>

            </div>
        </div>
    </div>
    <!-- /.container -->
<?php include(TEMPLATE_FRONT.DS."footer.php");  ?>

<?php 
    if(isset($_SESSION['user_name']))
    {
        $userid=logged_in_userid($_SESSION['user_name']);
    }
?>
<script type="text/javascript">
    $("span").on("click",
            function()
            {
                // if($(this).attr('style').color=='grey')
                // if($(this).css('color')=='grey')

                if($(this).hasClass("heart_color_grey"))
                {
                    $(this).removeClass("heart_color_grey");
                    $(this).addClass("heart_color_red");
                }
                else
                    if($(this).hasClass("heart_color_red"))
                    {
                        $(this).removeClass("heart_color_red")
                        $(this).addClass("heart_color_grey");
                    }

                productid=$(this).attr("id");

                //alert($productid+" "+<?php //echo $userid; ?>);

                $.get("update_saved_items.php?userid="+<?php echo $userid; ?>+"&saveitemid="+productid,
                    function(data,message)
                    {
                        
                    });
            }
        );
</script>

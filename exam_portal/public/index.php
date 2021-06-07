<?php include("../resources/config.php"); ?>
<?php 
  $title="Home";
  include(TEMPLATE_FRONT.DS."header.php"); 
?>

<?php
  if(isset($_POST['post_query']))
  {
    if(isset($_POST['contact_user']) && $_POST['contact_user']!="" && isset($_POST['contact_email']) && $_POST['contact_email']!="" && isset($_POST['contact_mobile']) && $_POST['contact_mobile']!="" && isset($_POST['contact_query']) && $_POST['contact_query']!="")
    {
      $username=escape_string(trim($_POST['contact_user']));
      $email=escape_string(trim($_POST['contact_email']));
      $mobile=escape_string(trim($_POST['contact_mobile']));
      $user_query=escape_string(trim($_POST['contact_query']));

      $query=query("insert into user_query (uq_name,uq_email,uq_mobile,uq_query) values('{$username}','{$email}','{$mobile}','{$user_query}')");
      confirm($query);

      $mess="<p class='text-success text-center'>Your Query Is Posted Successfully</p>";
    }
    else
    {
      $mess="<p class='text-danger text-center'>Your Query Is Not Posted Successfully.Please Fill All The Fields</p>";
    }
    // enabling $message_modal variable to show modal for status of query.
    $message_modal='<script>$("#contactus_confirmation_modal").modal("show");</script>';
  }
?>

<span data-toggle="modal" data-target="#show_colors" id="theme_button" style="font-size:30px;">
<i class="fa fa-cog" style="color:<?php echo $_SESSION['theme_color']; ?>;" data-toggle="popover" data-content="Choose Theme Color"></i>
</span>

<!-- modal if theme button is clicked -->
<div class="modal" id="show_colors">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Pick Theme Color</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<div class="row">
        	<div class="col-sm-1 color_changer" id="green" style="background-color:green;border-radius:50%;width:30px;height:30px;">
        	</div>
        	<div class="col-sm-1 color_changer" id="yellow" style="background-color:yellow;border-radius:50%;width:30px;height:30px;">
        	</div>
        	<div class="col-sm-1 color_changer" id="red" style="background-color:red;border-radius:50%;width:30px;height:30px;">
        	</div>
        	<div class="col-sm-1 color_changer" id="cyan" style="background-color:cyan;border-radius:50%;width:30px;height:30px;">
          </div>
        	<div class="col-sm-1 color_changer" id="orange" style="background-color:orange;border-radius:50%;width:30px;height:30px;">
          </div>
        	<div class="col-sm-1 color_changer" id="grey" style="background-color:grey;border-radius:50%;width:30px;height:30px;">
          </div>
        	<div class="col-sm-1 color_changer" id="pink" style="background-color:pink;border-radius:50%;width:30px;height:30px;">
          </div>
        	<div class="col-sm-1 color_changer" id="blue" style="background-color:blue;border-radius:50%;width:30px;height:30px;">
          </div>
        	<div class="col-sm-1 color_changer" id="magenta" style="background-color:magenta;border-radius:50%;width:30px;height:30px;">
          </div>
        	<div class="col-sm-1 color_changer" id="lightblue" style="background-color:lightblue;border-radius:50%;width:30px;height:30px;">
          </div>
        	<div class="col-sm-1 color_changer" id="darkgreen" style="background-color:darkgreen;border-radius:50%;width:30px;height:30px;">
          </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!-- modal ends -->

<!-- contactus confirmation modal -->
<div class="modal" id="contactus_confirmation_modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Your Query Status</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12 col-md-12">
            <?php 
              if(isset($mess))
                echo $mess; 
            ?>
          </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
      </div>

    </div>
  </div>
</div>
<!-- contactus confirmation modal ends -->

<script type="text/javascript">
	$(document).ready(function(){
    	$('[data-toggle="popover"]').popover({
        	placement : 'top',
        	trigger : 'hover'
    	});
	});

	$('.color_changer').click(
		function() 
		{
        color=$(this).attr("id");

        // to send value on (setting index value by php) page
        $.get('set_sessionindex_values.php?value='+color, function (data, textStatus, jqXHR)
                        {  // success callback
                          
                         }
              );
    		$('#show_colors').modal('hide');
        location.reload(true);             // to refresh the page and change effect.
		});
</script>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-md-12">
			<?php
				if($_SERVER['REQUEST_URI']=="/exam_portal/public/index.php" || $_SERVER['REQUEST_URI']=="/exam_portal/public/")
                            {
                                include(TEMPLATE_FRONT.DS."home_content.php");
                            }  
			?>
		</div>
	</div>
</div>
<?php include(TEMPLATE_FRONT.DS."footer.php"); ?>

<?php
  if(isset($message_modal)) {
    echo $message_modal;
  }
?>
 <?php
  require_once("../../config/config.php");
  require_once("../../config/" . ENV . "_config.php");
  ?>

 <?php
  if (!(isset($_SESSION['current_user'])))
    redirect('login.php');
  ?>
   <?php include("navigation.php"); ?>
 <?php

  $access = premissionScreen('TICKETS', $_SESSION['current_user']);

  $global = $access['global'];
  $input = $access['input'];
  $button = $access['button'];

  if ($global != 0) {
  ?>
  
   <?php
    $showinformation = 0;
    $message = "";
    ?>
   <!DOCTYPE html>
   <html>

   <head>
     <title>ticket-list</title>
     <style>
       .form-control {
         height: 30px;
         border-radius: 0px;
       }

       .form-control :focus {
         background-color: #00000000;
       }
     </style>
   </head>

   <body>
     <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">
     <!-- Connecting with RestApi-->

     <?php
      $data = array();
      if (isset($_POST['seller_id'])) {
        $data['seller_id'] = $_POST['seller_id'];
      } else  if (isset($_POST['ticket_id'])) {
        $data['ticket_id'] = $_POST['ticket_id'];
      } else  if (isset($_POST['status'])) {
        $data['status'] = $_POST['status'];
      } 
      $url = DOMAIN . '/rest/admin/GetUserlistScreenTicketRest.php';
      $output = getRestApiResponse($url, $data);

      $record = '';
      if (isset($output['getticketdetails']) && $output['getticketdetails']['response_code'] == 200) {
        $record .= <<< record
              <div class="container-fluid pt-3">
              <div class="row">
                <div class="col">
                  <form action="displayAdminTicketList.php" method="post">
                    <div class="input-group ">
                      <input type="text" class="form-control" placeholder="Seller Id" name="seller_id" style="border: medium solid black;margin-top:16px;height : 44px">
                      <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
       
                <div class="col">
                  <form action="displayAdminTicketList.php" method="post">
                    <div class="input-group ">
                      <input type="text" class="form-control" placeholder="Ticket Id" name="ticket_id" style="border: medium solid black;margin-top:16px;height : 44px">
                      <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
       
                <div class="col">
                  <form action="displayAdminTicketList.php" method="post">
                    <div class="input-group ">
                      <input type="text" class="form-control" placeholder="Status" name="status" style="border: medium solid black;margin-top:16px;height : 44px">
                      <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
       
       
              </div>
            </div>
       
           
       
       
            <!--Starting of the table-->
            <div class="mt-3">
              <table class="table table-hover table-responsive-lg table-sm table-bordered text-center"
              data-toggle="table"  
                    data-pagination-h-align="center"
                    data-pagination-detail-h-align="right"
                    data-side-pagination = "client"
                    data-page-size= "10"
                    data-pagination="true">
                <thead style='background-color : #36404e;color : white'>
                  <tr>
                    <th>Ticket Id</th>
                    <th>Seller Id</th>
                    <th>Mobile</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Resolution Remarks</th>
                    <th>Status</th>
                    <th style="width: 10%;">Creation DateTime</th>
                    <th>View</th>
                  </tr>
                </thead>
                <tbody>
       
record;
        for ($i = 0; $i < $output['getticketdetails']['rows']; $i++) {
          $subject = substr($output['getticketdetails'][$i]['subject'], 0, 30);
          $description = substr($output['getticketdetails'][$i]['description'], 0, 30);
          $resolution = substr($output['getticketdetails'][$i]['resolution_remarks'], 0, 30);

          $record .= <<< record
      <tr>
        <td>{$output['getticketdetails'][$i]['ticket_id']}</td>
        <td>{$output['getticketdetails'][$i]['seller_id']}</td>
        <td>{$output['getticketdetails'][$i]['mobile']}</td>
        <td>{$subject}</td>
        <td>{$description}</td>
        <td>{$resolution}</td>
record;
          if ($output['getticketdetails'][$i]['status'] == '1') {
            $record .= <<< record
          <td>Open</td>
record;
          } else if ($output['getticketdetails'][$i]['status'] == '2') {
            $record .= <<< record
          <td>Resolved</td>
record;
          } else if ($output['getticketdetails'][$i]['status'] == '3') {
            $record .= <<< record
          <td>Cancelled</td>
record;
          } else if ($output['getticketdetails'][$i]['status'] == '4') {
            $record .= <<< record
          <td>Reopened</td>
record;
          } else if ($output['getticketdetails'][$i]['status'] == '5') {
            $record .= <<< record
          <td>Closed</td>
record;
          }
          $record .= <<< record
        <td>{$output['getticketdetails'][$i]['created_date']}</td>
record;
          if ($button != 'disabled') {
            $record .= <<< record
  <td><button type = "button" data-toggle="modal" data-target="#ticketModal" id = "{$output['getticketdetails'][$i]['ticket_id']}" onclick="ticketpopupFun(this.id)" class = "btn btn-primary">View</button></td>
</tr>      
record;
          } else {
            $record .= <<< record
  <td><button disabled type = "button"  class = "btn btn-secondary">View</button></td>
</tr>      
record;
          }
        }
        echo $record;
      } else {
        if (isset($output['getticketdetails']) && $output['getticketdetails']['response_code'] == 405) {
          $record .= <<< record
        <h3 class="text-center pt-5" style ="color:red">{$output['getticketdetails']['response_desc']}</h3>
      
record;
        }

        echo $record;
      }
      ?>

     </tbody>
     </table>
     </div>

     <!-- View Ticket Detail Modal-->
     <div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title" id="exampleModalLabel">View Ticket Details</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <form action="displayAdminTicketList.php" method="POST">
               <div class="form-group row">
                 <label class="col-sm-4 col-form-label">Ticket Id</label>
                 <div class="col-sm-8">
                   <input id="viewticketid" class="form-control" readonly type="text" style="background-color : rgba(128, 128, 128, 0.555);float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                 </div>

               </div>

               <div class="form-group row">
                 <label class="col-sm-4 col-form-label">Seller Id</label>
                 <div class="col-sm-8">
                   <input id="viewsellerid" class="form-control" readonly type="text" style="background-color : rgba(128, 128, 128, 0.555);float : right; border : 1px solid #549eff; margin-left : 5px " <?php echo $input; ?>>
                 </div>

               </div>
               <div class="form-group row">
                 <label class="col-sm-4 col-form-label">Subject</label>
                 <div class="col-sm-8">
                   <input id="viewsubject" class="form-control" readonly type="text" style="background-color : rgba(128, 128, 128, 0.555);float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
                 </div>

               </div>

               <div class="form-group">
                 <label>Description</label>
                 <div class="col-sm-12">
                   <textarea id="viewdescription" class="form-control" readonly type="text" style="resize : none;background-color : rgba(128, 128, 128, 0.555);float : right; border : 1px solid #549eff; margin-left : 5px " <?php echo $input; ?>></textarea>
                 </div>
               </div>
               <div class="form-group">
                 <label>Resolution Remark</label>
                 <div class="col-sm-12">
                   <textarea id="viewresolutionremark" class="form-control" type="text" style="float : right; border : 1px solid #549eff; margin-left : 5px " <?php echo $input; ?>></textarea>
                 </div>
               </div>

           </div>
           <div class="modal-footer" style="margin: auto;">
             <?php
              if ($button != 'disabled') {
              ?>
               <button type="button" class="btn btn-primary resticket">Resolve Ticket</button>
               <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
             <?php
              } else {
              ?>
               <button type="button" class="btn btn-secondary resticket" disabled>Resolve Ticket</button>
               <button class="btn btn-danger" data-dismiss="modal" disabled>Cancel</button>
             <?php
              }
              ?>
           </div>
           </form>
         </div>
       </div>
     </div>
     <!--End of View Ticket Detail Modal-->
    

     <script>
       function ticketpopupFun(ticketid) {

         var domain = $("#getDOMAIN").val();
         $.post(domain + '/rest/admin/GetUserlistScreenTicketRest.php?ticket_id=' + ticketid + '&key=<?php echo md5(VALIDATION_KEY);?>',

           function(data, status) {

             $("#viewticketid").val(data['getticketdetails'][0]['ticket_id']);
             $("#viewsellerid").val(data['getticketdetails'][0]['seller_id']);
             $("#viewsubject").val(data['getticketdetails'][0]['subject']);
             $("#viewdescription").val(data['getticketdetails'][0]['description']);
             $("#viewresolutionremark").val(data['getticketdetails'][0]['resolution_remarks']);
             if (data['getticketdetails'][0]['status'] == '1' || data['getticketdetails'][0]['status'] == '4') {
               $("#viewresolutionremark").prop("readonly", false);
               $(".resticket").prop("disabled", false);
               
             } else if (data['getticketdetails'][0]['status'] == '2' || data['getticketdetails'][0]['status'] == '3' || data['getticketdetails'][0]['status'] == '5') {
               $("#viewresolutionremark").attr("readonly", true);
               $(".resticket").attr("disabled", true);
               $(".resticket").removeClass("btn-primary");
               $(".resticket").addClass("btn-secondary");
             }
           });
       }

       $(".resticket").click(() => {
         var domain = $("#getDOMAIN").val();

         $.post(domain + '/rest/admin/ResolveTicketRest.php?seller_id=' + $("#viewsellerid").val() + "&ticket_id=" + $("#viewticketid").val() + "&resolution_remarks=" + $("#viewresolutionremark").val() + '&key=<?php echo md5(VALIDATION_KEY);?>',
           function(data, status) {
             $("#ticketModal").click();
             if (data['getticketdetails']['response_code'] == 200) {
               $("#response").modal("show");
               $("#restext").text("Resolved Successfully");
               $("#resdesc").text("");
             } else {
               $("#response").modal("show");
               $("#restext").text("Operation Unsuccessful");
               $("#resdesc").text("ERROR : " + data['getticketdetails']['response_desc']);
             }
           });
         //location.reload(true);
       });
       $(document).ready(() => {
         if (window.history.replaceState) {
           window.history.replaceState(null, null, window.location.href);
         }
       });
     </script>

     <!--jQuery-->


   </body>

   </html>
 <?php
  } else {
    echo '<h2 class="pt-5" style="text-align:center;color:red;margin-top:4rem;">Permission Denied!</h2>';
  }
  ?>
   <?php include("footer.php"); ?>

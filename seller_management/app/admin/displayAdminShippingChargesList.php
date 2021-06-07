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

$access = premissionScreen('SHIPPING_CHARGES', $_SESSION['current_user']);

$global = $access['global'];
$input = $access['input'];
$button = $access['button'];

if ($global != 0) {
?>
 
  <?php
  $showinformation = 0;
  $message = "";
  ?>
  <!Doctype html>
  <html>

  <head>
     
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/js/bootstrap.bundle.min.js"></script>

    <style>
      .dropdown {
        margin: 20px;
      }

      .dropdown-menu {
        max-height: 20rem;
        overflow-y: auto;
      }
    </style>

  </head>

  <body>
    <div class="container-fluid pt-3">
      <?php
      if ($button != "disabled") {
      ?>
        <button class='btn btn-primary mt-3 mb-3' style='float : right;' data-toggle="modal" data-target="#addModal">Add</button>
      <?php
      } else {
      ?><button class='btn btn-secondary mt-3 mb-3' disabled style='float : right;'>Add</button>
      <?php
      }
      ?>

    </div>


    <!-- Connecting with RestApi-->
    <input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">
    <?php

    $data = array();
    if (isset($_POST['seller_id'])) {
      $data['seller_id'] = $_POST['seller_id'];
    }
    $url = DOMAIN . '/rest/admin/ShippingChargesListAdminRest.php';
    $output = getRestApiResponse($url, $data);

    $record = '';

    if (isset($output['getshippingchargesdetails']) && $output['getshippingchargesdetails']['response_code'] == 200) {
      $record .= <<< record
         
          <form action="displayAdminShippingChargesList.php" method="post">
             
       
            <div class="input-group" style = "width:20%;">
              <input type="text" class="form-control" placeholder="Seller Id" name="seller_id" style="border: medium solid black;margin-top:16px;">
              <div class="input-group-append">
                <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
                  <i class="fa fa-search"></i>
                </button>
              </div>
            </div>
          </form>
          <table class="table table-hover table-responsive-lg table-sm table-bordered table-fixed text-center"
          data-toggle="table"  
                    data-pagination-h-align="center"
                    data-pagination-detail-h-align="right"
                    data-side-pagination = "client"
                    data-page-size= "10"
                    data-pagination="true">

          <thead style='background-color : #36404e;color : white'>
            <tr>
              <th>Seller Id</th>
              <th>Shipping Charge</th>
              <th>Seller Status</th>
              <th>Last Modifed</th>
              <th>Last Modifed By</th>
              <th>Modify</th>
            </tr>
          </thead>
     
          <tbody>
record;
      for ($i = 0; $i < $output['getshippingchargesdetails']['rows']; $i++) {
        $status;
        if ($output['getshippingchargesdetails'][$i]['status'] == "A") {
          $status = "Active";
        } else {
          $status = "InActive";
        }
        $record .= <<< record
                    <tr>
                        <td>{$output['getshippingchargesdetails'][$i]['seller_id']}</td>
                        <td>{$output['getshippingchargesdetails'][$i]['shipping_amount']}</td>
                        <td>{$status}</td>
                        <td>{$output['getshippingchargesdetails'][$i]['last_modified_datetime']}</td>
                        <td>{$output['getshippingchargesdetails'][$i]['last_modified_by']}</td>
record;
        if ($button != 'disabled') {
          $record .= <<< record
                        <td><button type = "button" id = "{$output['getshippingchargesdetails'][$i]['seller_id']} + {$output['getshippingchargesdetails'][$i]['shipping_amount']}" onclick = "setSellerIDFun(this.id)" data-toggle="modal" data-target="#modifyModal" class = "btn btn-success apbtn">Modify</button></td>
                    </tr>      
record;
        } else {
          $record .= <<< record
                        <td><button disabled type = "button" class = "btn btn-secondary">Modify</button></td>
                    </tr>      
record;
        }
      }

      echo $record;
    } else {
      if (isset($output['getshippingchargesdetails']) && $output['getshippingchargesdetails']['response_code'] == 405) {
        $record .= <<< record
              <h3 class="pt-3" style="text-align : center;color:red;">{$output['getshippingchargesdetails']['response_desc']}</h3>
record;
      }
      echo $record;
    }
    ?>
    </tbody>
    </table>



    <!--Add User Modal-->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Seller Shipping Charges</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action='displayAdminShippingChargesList.php' method='POST'>
              <div class="form-group">
                <label>Seller Id</label>
                <!-- <input type="text" id="addSellerId" style="float : right; border : 1px solid #549eff; margin-left : 5px"> -->
                <div class="dropdown" style="display: inline;">
                  <button class="btn   dropdown-toggle" type="button" id="dropdown_coins" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="float: right;">
                    Search Seller
                  </button>
                  <div id="menu" class="dropdown-menu" aria-labelledby="dropdown_coins">
                    <form class="px-4 py-2">
                      <input type="search" class="form-control" id="searchCoin" placeholder="Seller" autofocus="autofocus" <?php echo $input; ?>>
                    </form>
                    <div id="menuItems"></div>
                    <div id="empty" class="dropdown-header">No Active Seller</div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Shipping Charge</label>
                <input type="text" id="addShippingCharges" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
              </div>
              <!--Confirm Modal-->
              <div class="modal" id="confirmAddModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
                <div class="modal-dialog modal-dialog-centered " role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h3 class="modal-title"><i class="fa fa-check-circle" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Save Confirmation
                        </strong></h3>

                    </div>
                    <div class="modal-footer">
                      <input type="button" class="btn btn-success w-50 addrow" value='Yes'>
                      <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End of Confirm Modal -->

            </form>
            <hr>
            <div class=" text-center">
              <button class="btn btn-primary" style="width : 35%" data-toggle="modal" data-target="#confirmAddModal">Save</button>
              <button type="button" class="btn btn-danger" style="width : 35%" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--End of add User Modal-->






    <!-- View Detail Modal-->
    <div class="modal fade" id="modifyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modify Seller Shipping Charges</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="displayAdminShippingChargesList.php" method="POST">
              <div class="form-group">
                <label>Seller Id</label>
                <input id="viewSellerId" type="text" readonly style="background-color : rgba(128, 128, 128, 0.555);float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
              </div>

              <div class="form-group">
                <label>Shipping Charge</label>
                <input id="viewShippingCharges" type="text" style="float : right; border : 1px solid #549eff; margin-left : 5px " <?php echo $input; ?>>
              </div>


              <!--Update Confirm Modal-->
              <div class="modal" id="confirmUpdateModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
                <div class="modal-dialog modal-dialog-centered " role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h3 class="modal-title"><i class="fas fa-check-circle" style="color:green" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Update Confirmation
                        </strong></h3>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-footer">
                      <input type="button" class="btn btn-success w-50 uptbtn" value='Yes'>
                      <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End of Update Confirm Modal -->


              <!--Delete Confirm Modal-->
              <div class="modal" id="confirmDeleteModal" tabindex="-1" role="dialog" style="background-color : rgba(128, 128, 128, 0.555);">
                <div class="modal-dialog modal-dialog-centered " role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h3 class="modal-title"><i class="fa fa-trash" style="color:red" aria-hidden="true"></i>&nbsp;&nbsp;<strong>Delete Confirmation
                        </strong></h3>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-footer">
                      <input type="button" class="btn btn-success w-50 dltbtn" value='Yes'>
                      <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End of Delete Confirm Modal -->

            </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary" style="width : 35%" data-toggle="modal" data-target="#confirmUpdateModal">Update</button>


            <button class="btn btn-primary" style="width : 35%" data-toggle="modal" data-target="#confirmDeleteModal" id='dltbtn'>Delete</button>
            <button type="button" class="btn btn-danger" style="width : 35%" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <!--End of View Detail Modal-->





    
    <script>
      $(".addrow").click(() => {
        var domain = $("#getDOMAIN").val();
        //  var sellerid =  $('#dropdown_coins').text();
        let prepare = $('#dropdown_coins').text().indexOf("-");
        var sellerid = $('#dropdown_coins').text().substring(prepare + 1);

        var shippingCharges = $("#addShippingCharges").val();

        $.post(domain + '/rest/admin/ShippingChargesAddAdminRest.php?seller_id=' + sellerid + '&shipping_amount=' + shippingCharges + '&current_user=' + '<?php echo $_SESSION['current_user']; ?>' + '&key=<?php echo md5(VALIDATION_KEY);?>',
          function(data, status) {

            $("#confirmAddModal").click();
            $("#addModal").click();
            if (data['addshippingcharge']['response_code'] == 200) {
              $("#response").modal("show");
              $("#restext").text("Updation Successful");
              $("#resdesc").text("");
            } else {
              $("#response").modal("show");
              $("#restext").text("UpdationUnsuccessful");
              $("#resdesc").text("ERROR : " + data['addshippingcharge']['response_desc']);
            }
          });
        // location.reload(true);
      });

      $(".uptbtn").click(() => {
        var domain = $("#getDOMAIN").val();
        var sellerid = $('#viewSellerId').val();

        var shippingCharges = $('#viewShippingCharges').val();

        console.log("<?php echo $_SESSION['current_user']; ?>");
        $.post(domain + '/rest/admin/ShippingChargesModifyAdminRest.php?seller_id=' + sellerid + '&shipping_amount=' + shippingCharges + '&current_user=' + '<?php echo $_SESSION['current_user']; ?>' + '&key=<?php echo md5(VALIDATION_KEY);?>',
          function(data, status) {
            $("#confirmUpdateModal").click();
            $("#modifyModal").click();
            if (data['modifyshippingcharge']['response_code'] == 200) {
              $("#response").modal("show");
              $("#restext").text("Updation Successful");
              $("#resdesc").text("");
            } else {
              $("#response").modal("show");
              $("#restext").text("UpdationUnsuccessful");
              $("#resdesc").text("ERROR : " + data['modifyshippingcharge']['response_desc']);
            }
          });
        // location.reload(true);  
      });

      $(".dltbtn").click(() => {
        var domain = $("#getDOMAIN").val();
        var sellerid = $('#viewSellerId').val();

        $.post(domain + '/rest/admin/ShippingChargesDeleteAdminRest.php?seller_id=' + sellerid + '&key=<?php echo md5(VALIDATION_KEY);?>',
          function(data, status) {
            $("#confirmDeleteModal").click();
            $("#modifyModal").click();
            if (data['deleteshippingcharge']['response_code'] == 200) {
              $("#response").modal("show");
              $("#restext").text("Deletion Successful");
              $("#resdesc").text("");
            } else {
              $("#response").modal("show");
              $("#restext").text("Deletion Unsuccessful");
              $("#resdesc").text("ERROR : " + data['deleteshippingcharge']['response_desc']);
            }
          });
        //location.reload(true);  
      });

      $('#response').on('hidden.bs.modal', function() {



        location.reload(true);
      });

      function setSellerIDFun(data) {
        var temp = new Array();
        temp = data.split("+");
        $("#viewSellerId").val(temp[0].trim());
        $("#viewShippingCharges").val(temp[1].trim());
      }
    </script>

    <script>
      var domain = $("#getDOMAIN").val();
      let names = new Array();
      $.post(domain + '/rest/admin/GetActiveSellerRest.php?' + '&key=<?php echo md5(VALIDATION_KEY);?>',
        function(data, status) {

          for (let i = 0; i < data['getactiveseller']['rows']; i++) {
            names.push(data['getactiveseller'][i]['business_name'] + "-" + data['getactiveseller'][i]['user_id']);
          }
          let search = document.getElementById("searchCoin")

          //Find every item inside the dropdown
          let items = document.getElementsByClassName("dropdown-item")

          function buildDropDown(values) {
            let contents = []
            for (let name of values) {
              contents.push('<input type="button" class="dropdown-item" type="button" value="' + name + '"/>')
            }
            $('#menuItems').append(contents.join(""))

            //Hide the row that shows no items were found
            $('#empty').hide()
          }

          //Capture the event when user types into the search box
          window.addEventListener('input', function() {
            filter(search.value.trim().toLowerCase())
          })

          //For every word entered by the user, check if the symbol starts with that word
          //If it does show the symbol, else hide it
          function filter(word) {
            let length = items.length
            let collection = []
            let hidden = 0
            for (let i = 0; i < length; i++) {
              if (items[i].value.toLowerCase().startsWith(word)) {
                $(items[i]).show()
              } else {
                $(items[i]).hide()
                hidden++
              }
            }

            //If all items are hidden, show the empty view
            if (hidden === length) {
              $('#empty').show()
            } else {
              $('#empty').hide()
            }
          }

          //If the user clicks on any item, set the title of the button as the text of the item
          $('#menuItems').on('click', '.dropdown-item', function() {
            $('#dropdown_coins').text($(this)[0].value)
            $("#dropdown_coins").dropdown('toggle');
          })

          buildDropDown(names)

        });
      $(document).ready(() => {
        if (window.history.replaceState) {
          window.history.replaceState(null, null, window.location.href);
        }
      });
      //Find the input search box
    </script>
  </body>

  </html>
<?php
} else {
  echo '<h2 class="pt-5" style="text-align:center;color:red;margin-top:4rem;">Permission Denied!</h2>';
}
?>
<?php include("footer.php"); ?>

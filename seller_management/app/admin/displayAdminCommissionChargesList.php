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

$access = premissionScreen('COMMISSION_CHARGES', $_SESSION['current_user']);

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
<input type="hidden" value="<?php echo DOMAIN; ?>" id="getDOMAIN">
 
  <div class="container-fluid pt-3">
    <?php
    if($button != 'disabled') {
    ?>
    <button class='btn btn-primary mt-3 mb-3' style='float : right;' data-toggle="modal" data-target="#addModal">Add</button>
    <?php
    }else {
    ?>
    <button class='btn btn-secondary mt-3 mb-3' disabled style='float : right;'>Add</button>
    <?php
    }
    ?>
  </div>
 
 
      <!-- Connecting with RestApi-->

      <?php

      $data = array();
      // if ( isset($_POST['seller_id'])) {
      //   $data['seller_id'] = $_POST['seller_id'];
      // } else {

      //   $data = '';
      // }
      
      $url = DOMAIN . '/rest/admin/CommissionChargesListAdminRest.php';
      $output = getRestApiResponse($url, $data);

      $record = '';
    //   <form action="displayAdminCommissionChargesList.php" method="post" style="width: 25%;">
     

    //   <div class="input-group" >
    //     <input type="text" class="form-control" placeholder="Seller Id" name="seller_id" style="border: medium solid black;margin-top:16px;">
    //     <div class="input-group-append">
    //       <button class="btn btn-secondary" type="submit" id="search" style="border: medium solid black;margin-top:16px;">
    //         <i class="fa fa-search"></i>
    //       </button>
    //     </div>
    //   </div>
    // </form>
      if (isset($output['getcommisionchargesdetails']) && $output['getcommisionchargesdetails']['response_code'] == 200) {
        $record .= <<< record
        
      <table class="table table-hover table-responsive-lg table-sm table-bordered table-fixed text-center"
      data-toggle="table"  
                    data-pagination-h-align="center"
                    data-pagination-detail-h-align="right"
                    data-side-pagination = "client"
                    data-page-size= "10"
                    data-pagination="true">
    
        <thead style='background-color : #36404e;color : white'>
          <tr>
            <th>Commission Type</th>
            <th>Commission %</th>
            <th>Tax On Commission</th>
            <th>Last Modifed</th>
            <th>Last Modifed By</th>
            <th>Modify</th>
          </tr>
        </thead>
    
        <tbody>
record;
        for ($i = 0; $i < $output['getcommisionchargesdetails']['rows']; $i++) {

          $record .= <<< record
                    <tr>
                        <td>{$output['getcommisionchargesdetails'][$i]['comission_type']}</td>
                        <td>{$output['getcommisionchargesdetails'][$i]['comission_percentage']}</td>
                        <td>{$output['getcommisionchargesdetails'][$i]['tax_on_commission']}</td>
                        <td>{$output['getcommisionchargesdetails'][$i]['last_modified_datetime']}</td>
                        <td>{$output['getcommisionchargesdetails'][$i]['last_modified_by']}</td>
record;
          if(1 != 'disabled') {
            $record .= <<< record
            <td><button type = "button" id = "{$output['getcommisionchargesdetails'][$i]['comission_type']} + {$output['getcommisionchargesdetails'][$i]['comission_percentage']} + {$output['getcommisionchargesdetails'][$i]['tax_on_commission']}" onclick = "setCommissionTypeFun(this.id)" data-toggle="modal" data-target="#modifyModal" class = "btn btn-success">Modify</button></td>
        </tr>      
record;
          } else {
            $record .= <<< record
            <td><button type = "button" disabled class = "btn btn-secondary">Modify</button></td>
        </tr>      
record;
          } 

        }

        echo $record;
      } else {
        if (isset($output['getcommisionchargesdetails']) && $output['getcommisionchargesdetails']['response_code'] == 405) {
          $record .= <<< record
              <h3 class = "text-center pt-5" style = "color : red;">{$output['getcommisionchargesdetails']['response_desc']}</h3>
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
          <h5 class="modal-title" id="exampleModalLabel">Add Commission Charges</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
        </div>
        <div class="modal-body">
          <form action='displayAdminCommissionChargesList.php' method='POST'>
            <div class="form-group">
              <label>Commission Type</label>
              <!-- <input type="text" id="addcommissiontype" readonly style="float : right; border : 1px solid #549eff; margin-left : 5px"> -->
              <select   id="droptype" placeholder="choose one" style='float:right;border: 1px solid black'>
                <option value="GATEWAY_CHARGES">GATEWAY CHARGES</option>
                <option value="PLATFORM_FEES">PLATFORM FEES</option>
              </select>
            </div>

            <div class="form-group">
              <label>Commission %</label>
              <input type="text" id="addCommissionper" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
            </div>

            <div class="form-group">
              <label>Tax on Commission</label>
              <input type="text" id="addTax" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
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
                    <?php
                    if(1 != 'disabled') {
                    ?>
                    <input type="button" class="btn btn-success w-50 addrow" value='Yes'>
                    <button type="button" class="btn btn-danger w-50" data-dismiss="modal">No</button>
                    <?php
                    } else {
                    ?>
                     <input type="button" disabled class="btn btn-secondary w-50 addrow" value='Yes'>
                    <button type="button" disabled class="btn btn-secondary w-50" data-dismiss="modal">No</button>
                    <?php
                    }
                    ?>
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
          <h5 class="modal-title" id="exampleModalLabel">Modify Commission Charges</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
        </div>
        <div class="modal-body">
          <form action="displayAdminCommissionChargesList.php" method="POST">
            <div class="form-group">
              <label>Commission Type</label>
              <input type="text" id="viewcommissiontype" readonly style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
            </div>

            <div class="form-group">
              <label>Commission %</label>
              <input type="text" id="viewCommissionpercentage" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
            </div>

            <div class="form-group">
              <label>Tax on Commission</label>
              <input type="text" id="viewTax" style="float : right; border : 1px solid #549eff; margin-left : 5px" <?php echo $input; ?>>
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
          <?php
          if($button != 'disabled') {
          ?>
          <button class="btn btn-primary" style="width : 35%" data-toggle="modal" data-target="#confirmUpdateModal">Update</button>


          <button class="btn btn-primary" style="width : 35%" data-toggle="modal" data-target="#confirmDeleteModal" id='dltbtn'>Delete</button>
          <button type="button" class="btn btn-danger" style="width : 35%" data-dismiss="modal">Cancel</button>
        <?php
          } else {
        ?>
        <button class="btn btn-secondary" disabled style="width : 35%" data-toggle="modal" data-target="#confirmUpdateModal">Update</button>


<button class="btn btn-secondary" disabled style="width : 35%" data-toggle="modal" data-target="#confirmDeleteModal" id='dltbtn'>Delete</button>
<button type="button" class="btn btn-secondary" disabled style="width : 35%" data-dismiss="modal">Cancel</button>
<?php
          }
?>
        </div>
      </div>
    </div>
  </div>
  <!--End of View Detail Modal-->





  
  <script>
     $(document).ready(() => {
            if ( window.history.replaceState ) {
              window.history.replaceState( null, null, window.location.href );
            }
        });
    $(".addrow").click(() => {
      var domain = $("#getDOMAIN").val();
      var addcommision_per = $("#addCommissionper").val();
      var addtax = $("#addTax").val();
      var dropopt = $('#droptype option:selected').attr("value");
      console.log(dropopt);
      $.post(domain + '/rest/admin/CommissionChargesAddAdminRest.php?comission_type=' + dropopt + '&comission_percentage=' + addcommision_per + '&current_user='+ "<?php echo $_SESSION['current_user'];?>" + '&tax_on_commission=' + addtax + '&key=<?php echo md5(VALIDATION_KEY);?>',
        function(data, status) {
          $("#confirmAddModal").click();
          $("#addModal").click();
          if (data['addcommisioncharge']['response_code'] == 200) {
                            $("#response").modal("show");
                            $("#restext").text("Added Successfully");
                            $("#resdesc").text("");
                        } else {
                            $("#response").modal("show");
                            $("#restext").text("Addition Unsuccessful");
                            $("#resdesc").text("ERROR : " + data['addcommisioncharge']['response_desc']);
                        }
        });
      //location.reload(true);
    });

    function setCommissionTypeFun(data) {
      var temp = new Array();
      temp = data.split("+");
      $('#viewcommissiontype').val(temp[0].trim());
      $('#viewCommissionpercentage').val(temp[1].trim());
      $('#viewTax').val(temp[2].trim());

    }

    $(".uptbtn").click(() => {
      var domain = $("#getDOMAIN").val();
      var commission_type = $('#viewcommissiontype').val();
      var commissionper = $('#viewCommissionpercentage').val();
      var commission_tax = $('#viewTax').val();

      $.post(domain + '/rest/admin/CommissionChargesModifyAdminRest.php?comission_type=' + commission_type + '&comission_percentage=' + commissionper + '&current_user='+ "<?php echo $_SESSION['current_user'];?>" + '&tax_on_commission=' + commission_tax + '&key=<?php echo md5(VALIDATION_KEY);?>',
        function(data, status) {
          $("#confirmUpdateModal").click();
          $("#modifyModal").click();
          if (data['modifycommisioncharge']['response_code'] == 200) {
                            $("#response").modal("show");
                            $("#restext").text("Updation Successful");
                            $("#resdesc").text("");
                        } else {
                            $("#response").modal("show");
                            $("#restext").text("Updation Unsuccessful");
                            $("#resdesc").text("ERROR : " + data['modifycommisioncharge']['response_desc']);
                        }
        });
      //location.reload(true);
    });

    $(".dltbtn").click(() => {
      var domain = $("#getDOMAIN").val();
      var commission_type = $('#viewcommissiontype').val();

      $.post(domain + '/rest/admin/CommissionChargesDeleteAdminRest.php?comission_type=' + commission_type + '&key=<?php echo md5(VALIDATION_KEY);?>',
        function(data, status) {
          $("#confirmDeleteModal").click();
          $("#modifyModal").click();
          if (data['deletecommisioncharge']['response_code'] == 200) {
                            $("#response").modal("show");
                            $("#restext").text("Deletion Successful");
                            $("#resdesc").text("");
                        } else {
                            $("#response").modal("show");
                            $("#restext").text("Deletion Unsuccessful");
                            $("#resdesc").text("ERROR : " + data['deletecommisioncharge']['response_desc']);
                        }
        });
      //location.reload(true);
    });

    function setSellerIDFun(sellerid) {
      $("#viewSellerId").val(sellerid);
    }
  </script>

  
</body>

</html>
<?php
} else {
    echo '<h2 class="pt-5" style="text-align:center;color:red;margin-top:4rem;">Permission Denied!</h2>';
}
?>
<?php include("footer.php"); ?>

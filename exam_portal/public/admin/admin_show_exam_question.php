<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<div class="bs-example4" data-example-id="contextual-table">
    <div class="row">
      <div class="col-12">
        <a href="admin_exam.php" class="btn btn-info">Back To Exams</a>
      </div>
    </div>
    <div class="row text-center" style="margin:1rem auto 3rem auto;">
      <div class="col-md-12 col-xs-12"><span><big><b>Exam Questions</b></big></span></div>
    </div>
    <div class="row text-center" style="margin:1rem auto 3rem auto;">
      <div class="col-md-12 col-xs-12 text-info"><span><b>Here, only your exam questions will be visible. To Add Section And Question, Click on "Add Section And Questions" option next to Exam</b></span></div>
    </div>
           <form class="form-horizontal" method="post">
            <div class="form-group">
                  <label for="Set" class="col-sm-2 control-label">Set</label>
                   <div class="col-sm-8">
                      <select name="set_of_exam" class="form-control1" id="list" required>
                          <option value="">To See Your Exam Question, Click Here For Selecting Exam SET</option>
                          <option value="A">A</option>
                          <!-- <option value="b">B</option>
                          <option value="c">C</option>
                          <option value="d">D</option>
                          <option value="e">E</option> -->
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <p class="help-block">Select your Set of exam</p>
                    </div>
            </div>
            <div class="row">
          <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
            <table class="table">
              <thead> 
                <tr>
                  <th>S.No.</th>
                  <th>Question</th>
                </tr>
              </thead>
              <tbody id="changable">
              
              </tbody>
    </table>
  </div>
</div>
    </form>
</div>

<script type="text/javascript">
  $("#list").on("click",function()
  {
    set=$(this).children("option:selected").val();
    eid=""+<?php echo $_GET['eid']; ?>;

    if(set!="")
    {
      $.get("ajax_show_questions.php?eid="+eid+"&set="+set,function(data,status)
      {
        $("#changable").html(data);
      });
    }
  });
</script>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?>


<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>

<?php
  if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
  {

  if(isset($_POST['delete_exam_syllabus']) && isset($_POST['eid']))
  {
    $q_ds=query("DELETE FROM exam_syllabus WHERE eid='".$_POST['eid']."'");
    confirm($q_ds);
  }

  if(isset($_POST['open_exam_syllabus']))
  {
    $q_sy=query("select * from exam_syllabus where eid='".$_POST['eid']."'");
    confirm($q_sy);

    if(mysqli_num_rows($q_sy)!=0)
    {
      echo '<div class="bs-example4" data-example-id="contextual-table">
              <div class="row">
                <div class="col-12">
                  <a href="admin_exam_syllabus.php" class="btn btn-info btn-sm" style="margin-bottom:15px;margin-top:3em;margin-left:2em;">Back To All Exam Syllabus</a>
                </div>
              </div>
              <div class="row">
                <div class="col-12 text-center">
                  <h4>Exam Syllabus</h4>
                </div>
              </div>
              <div class="row">
                <table class="table table-responsive table-bordered table-hover">
                  <thead>
                    <tr>
                      <th class="text-center">Topic</th><th class="text-center">Sub-Topics</th>
                    </tr>
                  </thead>
                  <tbody>';

      while($r_sy=fetch_array($q_sy))
      {
        $syllabus=<<< syllabus
        <tr class="active">
          <td>{$r_sy['topic']}</td>
          <td>{$r_sy['sub_topics']}</td>
        </tr>
syllabus;
        echo $syllabus;
      }

      echo '</tbody>
          </table>
          </div>
        </div>';
    }
    else
    {
      echo '<div class="row">
              <div class="col-12">
                <a href="admin_exam_syllabus.php" class="btn btn-info btn-sm" style="margin-bottom:15px;margin-top:3em;margin-left:2em;">Back To All Exam Syllabus</a>
              </div>
            </div>
            <div class="row">
              <div class="col-12 text-center text-danger">
                <h4 style="margin-top:5em;">Exam Syllabus Not Available</h4>
              </div>
            </div>';
    }
  }
  else
  {
?>
    <div class="bs-example4" data-example-id="contextual-table">
    <div class="row">
      <div class="col-md-4 col-xs-6"><span><big><b>Exams</b></big></span></div>
      <div class="col-md-8 col-xs-6 text-right">
       <a href="admin_insert_exam_syllabus.php"><button class="btn-primary btn">Add Exam Syllabus</button></a>
      </div>
    </div>
    <div class="row">
    <div class="col-md-12 col-xs-12" style="overflow-x:auto;">
    <table class="table">
      <thead> 
        <tr>
          <th>S.No</th>
          <th>Exam</th>
          <th>Syllabus</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
<?php 
    $query=query("select * from exam order by eid desc");
    confirm($query);

    if(mysqli_num_rows($query)!=0)
    {
      $i=1;
      while($row=fetch_array($query))
      {
        $cat=<<< cat
          <tr class="active">
            <td>{$i}</td>
            <td>{$row['title']}</td>
            <td>
              <form action="" method="post">
                <input type="hidden" name="eid" value="{$row['eid']}">
                <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                <input type="submit" class="btn btn-info" name="open_exam_syllabus" value="Open Syllabus">
              </form>
            </td>
            <td>
              <form action="" method="post">
                  <input type="hidden" name="eid" value="{$row['eid']}">
                  <input type="hidden" name="{$_SESSION['csrf_name']}" value="{$_SESSION['csrf_value']}">
                  <input type="submit" class="btn btn-danger" name="delete_exam_syllabus" value="Delete Syllabus" onClick="return confirm('Do you really want to delete this exam syllabus ?')">
              </form>
            </td>
          </tr>
cat;
      $i++;
      echo $cat;
      }
    }
?>
      </tbody>
    </table>
   </div>
 </div>
</div>

<?php
  }
?>

<?php
  }
  else
    echo '<div class="bs-example4" data-example-id="contextual-table"><div class="row">
            <div class="col-12 text-center text-danger">
              <h4 style="margin-top:5em;">You Don\'t Have Permission To Access This Page</h4>
            </div>
          </div></div>';
?>

<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?> 
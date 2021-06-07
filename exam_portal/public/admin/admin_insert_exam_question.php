<?php include("../../resources/config.php"); ?>
<?php include(TEMPLATE_BACK.DS."admin_header.php"); ?>
<?php 

    if(isset($_GET['scid'])&&isset($_GET['eid'])) 
    {
      $scid=$_GET['scid'];
      $eid=$_GET['eid'];
      $qides="";


      if(isset($_POST['submit_select_section']))
      {   
          $seid=$_POST['seid'];
          $set_of_exam=$_POST['set_of_exam'];

          $q_check=query("select * from exam_questions where seid='".$seid."' and set_of_exam='".$set_of_exam."' and eid='".$eid."'");
          confirm($q_check);
         
          if(mysqli_num_rows($q_check)!=0)
          {
           echo "<script>alert('Selected section is already added in this exam !')</script>";
           redirect("admin_insert_exam_question.php?eid={$eid}&scid={$scid}");
          }
      }


      if(isset($_POST['submit_exam_question'])) 
      {
        $seid=$_POST['seid'];
        $set_of_exam=$_POST['set_of_exam'];
        foreach ($_POST as $key => $var) 
        {
            if ($var==="Submit"||$key==="seid"||$key==="set_of_exam") 
            {
              break;
            }
            $qides.=$var."#";
        }

        if($qides!="")
        {
          $query_insert_question=query("insert into exam_questions(eid,seid,qid,set_of_exam) values('".$eid."','".$seid."','".$qides."','".$set_of_exam."')");
          confirm($query_insert_question);
          redirect("admin_exam.php");
        }
        else
          setmessage("<p class='text-danger text-center'>Your Questions Are Not Added In Section Because You Don't Select Any Question.Please Select Questions And Try Again.</p>");
      }   
?>
	<div class="bs-example4" data-example-id="contextual-table">
    <div class="row">
      <div class="col-12">
        <a href="admin_exam.php" class="btn btn-info">Back To Exams</a>
      </div>
    </div>
  	<div class="row text-center" style="margin:1rem auto 3rem auto;">
      <?php displaymessage(); ?>
  		<div class="col-md-12 col-xs-12"><span><big><b>Exam Questions</b></big></span></div>
  	</div>

    <?php 
      if(!isset($_POST['submit_select_section'])) 
      { 
    ?>
    <form class="form-horizontal" method="post">
                <div class="form-group">
                  <label for="Set" class="col-sm-2 control-label">Set</label>
                   <div class="col-sm-8">
                      <select name="set_of_exam" class="form-control1" required>
                          <option value="A">A</option>
                          <!-- <option value="B">B</option>
                          <option value="C">C</option>
                          <option value="D">D</option>
                          <option value="E">E</option> -->
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <p class="help-block">Select your Set of exam</p>
                    </div>
                </div>
                <div class="form-group">
                  <label for="section" class="col-sm-2 control-label">section</label>
                  <div class="col-sm-8">
                    <select name="seid" class="form-control1" required>
                      <option value="">Click to select Section for adding in Exam</option>
                      <?php 
                        $query_tid=query("select * from test where scid='".$scid."'");
                        confirm($query_tid);

                        if(mysqli_num_rows($query_tid)!=0)
                        {
                          $row_tid=fetch_array($query_tid);

                          // to fetch section id
                          $query_section=query("select * from section where tid= '".$row_tid['tid']."'");
                          confirm($query_section);

                          if(mysqli_num_rows($query_section)!=0)
                          {                        
                            while($row_section=fetch_array($query_section))
                            {
                              echo '<option value="'.$row_section['seid'].'">'.$row_section['section_name'].'</option>';

                            }
                          }
                        }
                      ?>
                    </select>
                  </div>
                  <div class="col-sm-2">
                    <p class="help-block">Select your Section of Question</p>
                  </div>
                </div>
                <input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
                <div class="row">
                 <div class="col-sm-12 text-center">
                    <input type="submit" name="submit_select_section" class="btn btn_5 btn-lg btn-primary " id="submit_insert" value="Submit">
                 </div>
               </div>
              </form>
    <?php 
      } 
      else 
      { 
       $set_of_exam=$_POST['set_of_exam'];
       $seid=$_POST['seid'];
       $query=query("select * from question_bank where seid='".$seid."'");
       confirm($query);

       if(mysqli_num_rows($query)!=0)
       {
    ?>
           <form class="form-horizontal" method="post" style="overflow-x:auto;">
            <table class="table" style="table-layout:fixed;">
              <thead> 
                <tr>
                  <th style="width:60px;">S.No.</th>
                  <th style="width:80%;">Question</th>
                  <th>Check question for exam</th>
                  <th>Is Used</th>
                </tr>
              </thead>
              <tbody>
        <?php 
            $i=1;
        		while($row=fetch_array($query))
        		{
              $query1=query("select * from question where qid={$row['qids']}");
              confirm($query1);

              if(mysqli_num_rows($query1)!=0)
              {
                $row1=fetch_array($query1);
                $question=html_entity_decode($row1['question']);
                // checking weather the question is used 
                 $query_retrive=query("select * from exam_questions");
                 confirm($query_retrive);
                 $fst=1;
                 $used=0;
                 while($row_retrive=fetch_array($query_retrive))
                 {
                    if ($fst==1) 
                    {
                      $query_check=query("select * from exam_questions where qid like '%{$row['qid']} %' ");
                      confirm($query_check);
                      if(mysqli_num_rows($query_check)>0) 
                      {
                          $used=1;
                      }
                    }
                    else
                    { 
                      $query_check=query("select * from exam_questions where qid like '% {$row['qid']} %' ");
                      confirm($query_check);
                      if(mysqli_num_rows($query_check)>0) 
                      {
                          $used=1;
                      }
                    }
                    $fst++;
                 }


                  echo "<tr class='active'>
                            <td style='width:60px;'> {$i}</td>
                            <td style='width:80%;word-break:break-word;'> {$question}</td>
                            <td>
                            <div class='form-group'>
                              <div class='col-sm-8'>
                                <label><input type='checkbox' value='{$row['qid']}' name='ques{$i}'> Check</label>
                              </div>
                            </div>
                            </td>
                            <td>
                            <div class='form-group'>
                                    <div class='col-sm-8'>";
                  if ($used==1) 
                  {
                      echo "<img src='images/right.png' alt='question is used' height='20' width='20' >";
                  }
                  else
                  {
                      echo "<img src='images/right.png' alt='question is used' height='20' width='20' style='display:none;' >"; 
                  }
                        
                  echo "          </div>
                              </div>
                            </td>
                          </tr>";
                $i++;
              }
        		}

        ?>

      </tbody>
    </table>
        <!-- this input field must be remain in this position -->
         <input type="hidden" name="seid" value="<?php echo $seid; ?>">
            <input type="hidden" name="set_of_exam" value=<?php echo $set_of_exam; ?>>
         <div class="form-group">
           <label for="submit_insert" class="col-sm-2 control-label"></label>
           <div class="col-sm-8 text-center">
            <input type="hidden" name="<?php echo $_SESSION['csrf_name']; ?>" value="<?php echo $_SESSION['csrf_value']; ?>">
             <input type="submit" name="submit_exam_question" class="btn btn_5 btn-lg btn-primary " id="submit_insert" value="Submit">
           </div>
         </div>
        </form>
  <?php 
      }
    } 
  ?>
   </div>
<?php }
      else
      {
         echo "Access Denied...
         <a href='admin_exam.php'>select</a>";
      }
?>
<?php include(TEMPLATE_BACK.DS."admin_footer.php"); ?>
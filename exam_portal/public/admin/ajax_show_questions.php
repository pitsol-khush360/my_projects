<?php include("../../resources/config.php"); ?>
<?php 
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
  if(isset($_GET['set']) && isset($_GET['eid'])) 
  {
      $eid=$_GET['eid'];
      $set_of_exam=$_GET['set'];

      $query=query("select qid from exam_questions where eid='".$eid."' and set_of_exam='".$set_of_exam."'");
      confirm($query);

      $str="";

      while($row=fetch_array($query))
      {
        $str.=$row['qid']."#";
      }
      $temp=explode("#",$str);


      $cat="";
      $i=1;
      foreach($temp as $v)
      {
        if($v!="" && $v!="undefined" && $v!=" ")
        {
          	$x=intval($v);
          // echo $x."<br>";
        	$q1=query("select * from question_bank where qid='".$x."'");
        	confirm($q1);

          if(mysqli_num_rows($q1)!=0)
          {
        	  $r1=fetch_array($q1);

            $q2=query("SELECT * from question where qid='{$r1['qids']}'");
            confirm($q2);

            if(mysqli_num_rows($q2)!=0)
            {
              $r2=fetch_array($q2);
              $question=html_entity_decode($r2['question']);
              $cat.=<<< cat
              <tr class="active">
            	 <td>{$i}</td>
               <td>{$question}</td>
              </tr>
cat;
		          $i++;
            }
          }
        }
      }
      echo $cat;
  }
}
?>
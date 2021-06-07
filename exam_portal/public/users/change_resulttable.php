<?php include("validateUserMultipleLogin.php"); ?>
<?php
	if(isset($_SESSION['username']) && isset($_GET['start']) && isset($_GET['end']))
	{
		$start=$_GET['start'];
		$end=$_GET['end'];

		$query=query("select * from result where ulid='".$_SESSION['ulid']."' order by result_id desc limit $start,$end");
    	confirm($query);

    	$here="";
    	while($row=fetch_array($query))
        {
            $ct=date('d F Y h:i A',strtotime($row['completed_time']));

            // calculating Rank Of User

            $total_students_appeared=0;
            $rank=0;

            $q_gc=query("SELECT count(*) AS totalstudents FROM result WHERE eid='".$row['eid']."'");
            confirm($q_gc);

            if(mysqli_num_rows($q_gc)!=0)
            {
              $r_gc=fetch_array($q_gc);
              $total_students_appeared=$r_gc['totalstudents'];
            }

            if($total_students_appeared!=0)
            {
              $query_rank=query("SELECT Rank FROM 
                        (SELECT @rank:=@rank+1 AS Rank, result.* 
                          FROM result, (SELECT @rank:=0) AS i 
                          WHERE eid='".$row['eid']."' ORDER BY marks_obtained DESC, completed_time ASC) AS allranks 
                          WHERE allranks.ulid='".$_SESSION['ulid']."'");
                      confirm($query_rank);

              if(mysqli_num_rows($query_rank)!=0)
              {
                $row_rank=fetch_array($query_rank);
                $rank=$row_rank['Rank'];
              }
            }
            
            $here.=<<<here
            <tr class="active">
            <td>{$row['exam_title']}</td>
            <td>{$row['exam_set']}</td>
            <td>{$row['total_questions']}</td>
            <td>{$row['total_attempted']}</td>
            <td>{$row['right_questions']}</td>
            <td>{$row['wrong_questions']}</td>
            <td>{$row['marks_obtained']}</td>
            <td>{$row['total_marks']}</td>
            <td>{$ct}</td>
            <td>{$rank} / {$total_students_appeared}</td>
            </tr>
here;
        }

        echo $here;
	}
?>
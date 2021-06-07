<?php include("../../resources/config.php"); ?>
<?php 
  if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
  {
    $f_qstid= $_GET['qstid'];
    $f_qtid= $_GET['qtid'];

  	$query=query("select * from question where qstid='{$f_qstid}'");
  	confirm($query);
  	echo"<div id='{$f_qtid}_{$f_qstid}' class='question_set'>
          <table class='table'>
            <thead> 
              <tr>
                <th>S.No.</th>
                <th>Question</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>";
    $i=1;
  	while($row=fetch_array($query)) 
  	{
  	?>
          <tr>
            <td style="width:60px;"><?php echo $i++; ?></td>  
            <td style="width:80%;word-break:break-word;"><?php echo html_entity_decode($row['question']); ?></td>	
            <td><input type="checkbox" name="<?php echo $row['qid']; ?>"></td>
          </tr>
  	<?php	
  	}
  	echo "
       </tbody>
     </table>
    </div>";
  }
?>
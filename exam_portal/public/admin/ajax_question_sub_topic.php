<?php include("../../resources/config.php"); ?>
<?php 
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
  $f_qtid= $_GET['qtid'];
 
	$query=query("select * from question_sub_topic where qtid='{$f_qtid}'");
	confirm($query);
	$count=1;
	while ($row=fetch_array($query)) 
	{
		if($count==1)
		{
			echo $row['qstid']; 
		}
	?>

	<option value="<?php echo $row['qstid']; ?>" > <?php echo $row['question_sub_topic']; ?> </option>

	<?php	
	$count++;
	}
}
?>
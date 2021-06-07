<?php include("../../resources/config.php"); ?>
<?php
	
	$scid=$_POST['scid'];

	$query1=query("select * from test where scid ='".$scid."';");
			confirm($query1);
	$row1=fetch_array($query1);

	$query=query("select * from section where tid ='".$row1['tid']."';");
			confirm($query);
	while ($row=fetch_array($query)) 
	{
		echo "<option value='{$row['seid']}'>{$row['section_name']}</option>";
	}
 ?>
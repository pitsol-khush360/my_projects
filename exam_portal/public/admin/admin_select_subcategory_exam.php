<?php include("../../resources/config.php"); ?>
<?php
	$ccid=$_GET['ccid'];

	$query=query("select * from sub_category where ccid ='".$ccid."'");
	confirm($query);

	$temp="";
	while($row=fetch_array($query)) 
	{
		$temp.=<<<temp
		<option value="{$row['scid']}">{$row['sub_category_name']}</option>
temp;
	}

	echo $temp;
?>
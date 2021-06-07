<?php include("../../resources/config.php"); ?>
<?php 
if(isset($_SESSION['admin_username']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role']==1)
{
  if(isset($_GET['ccid']))
  {
	$query=query("select * from sub_category where ccid='".$_GET['ccid']."'");
	confirm($query);

	$v="";
	$v.="<option value=\"\">-- Select Sub-Course --</option>";

	if(mysqli_num_rows($query)!=0)
	{
		while($row=fetch_array($query)) 
		{
			$v.=<<< v
			<option value="{$row['scid']}">{$row['sub_category_name']}</option>
v;
		}
	}

	echo $v;
  }
}
?>
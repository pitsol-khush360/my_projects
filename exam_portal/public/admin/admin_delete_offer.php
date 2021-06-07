<?php include("../../resources/config.php"); ?>
<?php 
	if(isset($_POST['delete_offer']) && isset($_POST['offer_id']))
	{
		$oid=$_POST['offer_id'];
		$img="";

		$get_img=query("select offer_image from offers where offer_id='".$oid."'");
		confirm($get_img);

		if(mysqli_num_rows($get_img)!=0)
		{
			$ro=fetch_array($get_img);
			$img=$ro['offer_image'];
		}

		if($img!="" && $img!="defaultoffer.jpg")
			unlink(USEROFFER_UPLOAD.DS.$img);		

		$query=query("DELETE FROM offers WHERE offer_id = '".$oid."'");
		confirm($query);

		redirect("admin_offer.php");
	}
	else
	{
		echo "<script>alert('you cant access this page directly.')</script>";
		redirect("admin_offer.php");
	}
?>
<?php 
	if(isset($_GET['profile']) && isset($_SESSION['username']) && isset($_SESSION['ulid']))
	{
		$query=query("select * from user_personal where ulid='".$_SESSION['ulid']."'");
		confirm($query);
		$row=fetch_array($query);
		if($row['profile_picture']=="")
			$img_path=image_path_profile("defaultpic.jpg");
		else
			$img_path=image_path_profile($row['profile_picture']);
?>
<div class="container">
	<div class="row profilerow">
				<div class="col-md-12">
					<h3 class="profileheading"><strong>Your Details</strong></h3>
				</div>
				<div class="col-md-3 col-xs-12 text-center">
					<img src="../../resources/<?php echo $img_path; ?>" class="profileimage">
				</div>
				<div class="col-md-1 col-xs-12"></div>
				<div class="col-md-6 col-xs-12">
					<div class="row">
						<table class="table table-responsive table-hover">
						<tbody>
						<tr>
							<td><strong><span class="heading">Name</h3></span></strong></td>
							<td><span class="details"><u><?php echo $row['name']; ?></u></span></td>
						</tr>
						<tr>
							<td><strong><span class="heading">Email</span></strong></td>
							<td><span class="details"><u><?php echo $row['email']; ?></u></span></td>
						</tr>
						<tr>
							<td><strong><span class="heading">Mobile</span></strong></td>
							<td><span class="details"><u><?php echo $row['mobile']; ?></u></span></td>
						</tr>
						<tr>
							<td><strong><span class="heading">Address</span></strong></td>
							<td><span class="details"><u><?php echo $row['address']; ?></u></span></td>
						</tr>
						<tr>
							<td><strong><span class="heading">District</span></strong></td>
							<td><span class="details"><u><?php echo $row['district']; ?></u></span></td>
						</tr>
						<tr>
							<td><strong><span class="heading">State</span></strong></td>
							<td><span class="details"><u><?php echo $row['state']; ?></u></span></td>
						</tr>
						<tr>
							<td><strong><span class="heading">Country</span></strong></td>
							<td><span class="details"><u><?php echo $row['country']; ?></u></span></td>
						</tr>
						</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-2 col-xs-12"></div>
	</div>	
	<div class="row">
		<div class="col-1 col-sm-2 col-md-3 col-lg-3"></div>
		<div class="col-4 col-sm-4 col-lg-4" style="margin-top:2rem;">
			<a href="../signup_complete.php?edit" style="text-decoration:none;margin-left:auto;margin-right:auto;display:block;width:120px;"><button class="btn btn-info btn-lg">Update Details</button></a>
		</div>
	</div>
</div>
<?php
	}
	else
	{
		echo "Can't Accessible";
	}
?>
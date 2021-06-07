<?php include("validateUserMultipleLogin.php"); ?>
<?php
	if(isset($_SESSION['username']) && isset($_GET['start']) && isset($_GET['end']))
	{
		$start=$_GET['start'];
		$end=$_GET['end'];

		$query=query("select * from faqs order by faq_id desc limit $start,$end");
		confirm($query);

		$here="";

				$here.='<div class="row">';
				while($row=fetch_array($query))
				{
					$here.=<<< faq
					<div class="col-12 col-sm-7 col-md-8 col-lg-10 faq_panel_col_margin">
						<div class="card">
							<div class="card-header card1-header">
								<div class="col-12">
									<p><b>Question&nbsp; :</b><br>{$row['question']}</p>
								</div>
							</div>
							<div class="card-body card1-body">
								<div class="col-12">
									<p><b>Answer&nbsp;:</b><br>{$row['answer']}</p>
								</div>
							</div>	
						</div>
					</div>
faq;
				}
				$here.='</div>';

				echo $here;
	}
?>
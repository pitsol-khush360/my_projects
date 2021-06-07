<?php 
	require_once("../../config/config.php"); 
	require_once("../../config/".ENV."_config.php");
?>

<?php
	if(isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role']==2 && isset($_GET['ticketid']))
	{
		$data['user_id']=$_SESSION['user_id'];
		$data['ticket_id']=$_GET['ticketid'];

		$url=DOMAIN.'/rest/seller/ticketListScreenRest.php';

		$output=getRestApiResponse($url,$data);

		if(isset($output['ticketlist']) && count($output['ticketlist'])>2)
		{
			if(isset($output['ticketlist']['rows']) && $output['ticketlist']['rows']!=0)
			{
				$record="";
				for($i=0;$i<$output['ticketlist']['rows'];$i++)
				{
					$record.=<<< record
					<tr>
						<td>{$output['ticketlist'][$i]['ticket_id']}</td>
						<td>{$output['ticketlist'][$i]['subject']}</td>
record;

					if($output['ticketlist'][$i]['status']==1)
					{
						$record.=<<< record
						<td><p class="text-success">Open</p></td>
						<td>
							<form action="" method="post">
								<input type="hidden" name="tid" value="{$output['ticketlist'][$i]['ticket_id']}">
								<button type="submit" name="viewticket" class="btn btn-info">View</button>
							</form>
						</td>
						<td>
							<form action="" method="post">
								<input type="hidden" name="tid" value="{$output['ticketlist'][$i]['ticket_id']}">
								<button type="submit" name="cancelticketscreen" class="btn btn-danger">Cancel Ticket</button>
							</form>
						</td>
record;
					}
					else
					if($output['ticketlist'][$i]['status']==2)
					{
						$record.=<<< record
						<td><p class="text-success">Resolved</p></td>
						<td>
							<form action="" method="post">
								<input type="hidden" name="tid" value="{$output['ticketlist'][$i]['ticket_id']}">
								<button type="submit" name="viewresolvedticket" class="btn btn-info">View</button>
							</form>
						</td>
						<td></td>
record;
					}
					else
					if($output['ticketlist'][$i]['status']==3)
					{
						$record.=<<< record
						<td><p class="text-danger">Cancelled</p></td>
						<td>
							<form action="" method="post">
								<input type="hidden" name="tid" value="{$output['ticketlist'][$i]['ticket_id']}">
								<button type="submit" name="viewticket" class="btn btn-info">View</button>
							</form>
						</td>
						<td></td>
record;
					}
					else
					if($output['ticketlist'][$i]['status']==4)
					{
						$record.=<<< record
						<td><p class="text-info">Reopened</p></td>
						<td>
							<form action="" method="post">
								<input type="hidden" name="tid" value="{$output['ticketlist'][$i]['ticket_id']}">
								<button type="submit" name="viewticket" class="btn btn-info">View</button>
							</form>
						</td>
						<td></td>
record;
					}
					else
					if($output['ticketlist'][$i]['status']==5)
					{
						$record.=<<< record
						<td><p class="text-danger">Closed</p></td>
						<td>
							<form action="" method="post">
								<input type="hidden" name="tid" value="{$output['ticketlist'][$i]['ticket_id']}">
								<button type="submit" name="viewticket" class="btn btn-info">View</button>
							</form>
						</td>
						<td></td>
record;
					}
				}
				echo $record;
			}
		}
	}
?>

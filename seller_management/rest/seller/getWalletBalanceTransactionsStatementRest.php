<?php

header("Content-Type:application/json");	// setting content as we will convert data in JSON type.
require('getSellerWalletBalanceRest.php');
require_once '../../public/mcpdf/vendor/autoload.php';
//include('../../config/config.php');
if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!=''&&isset($_REQUEST['interval']) && $_REQUEST['interval']!='')
	{
		$start='';
		//$end=10;
		$end='';
		if(isset($_REQUEST['start']) && $_REQUEST['start']!='')
		{
			$start=date('Y-m-d',strtotime($_REQUEST['start']));
		}		
		if(isset($_REQUEST['end']) && $_REQUEST['end']!='')
		{
			$end=date('Y-m-d',strtotime($_REQUEST['end']));
		}
		// if(isset($_REQUEST['end']) && $_REQUEST['end']!='')
		// {
		// 	$end=$_REQUEST['end'];
		// }
		$walletbalance = new Wallet();
		$temp=array();
		$temp=$walletbalance->getWalletTransactionStatement($_REQUEST['user_id'],$_REQUEST['interval'],$start,$end);
		if(isset($_REQUEST['statement_mode']) && $_REQUEST['statement_mode']=='getonemail')
		{
			$query ="
					SELECT 
						seller_email,
						seller_business_name
					FROM 
						seller_details
					WHERE 
						seller_id ='".$_REQUEST['user_id']."' 
					";
			$query = query($query);
			confirm($query);
			$row = fetch_array($query);
			$rows = mysqli_num_rows($query);
			if($rows>0 && $row['seller_email']!='' && $row['seller_email']!=NULL)

			{			
				$email 		  = $row['seller_email'];
				$businessName = $row['seller_business_name'];
				$interval = $_REQUEST['interval'];
				if($interval == 7)
				{
					$interval = 'From '.$start.' To '.$end;
				}
				$tabdata=prepareTransactionsDataForDownloadStatement(array("getwalletbalance"=>$temp),$_REQUEST['user_id'],$businessName,getTransactionDescription($interval));
				
		        $mpdf = new \Mpdf\Mpdf();
		        $mpdf->WriteHTML($tabdata);
		        //ob_clean();
		        $mpdf->SetTitle('transaction_statement.pdf');
		        $pdf=$mpdf->Output("","S");
		        sendMail1($email,"Transaction Pass Book","Your Transactions Statement",$pdf);
		        
			}
		}
		$temp['response_code']=200;
		$temp['response_desc']="Sucess";
	
 		echo json_encode(array("getwalletbalance"=>$temp));
		close();
		exit();
	}
else
	{
		$temp=array();
		$temp['response_code']=400;
		$temp['response_desc']="Invalid Request";
		echo json_encode(array("getwalletbalance"=>$temp));
		close();
		exit();
	}

close();
?>

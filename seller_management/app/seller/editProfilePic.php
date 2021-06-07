<?php 
    require_once("../../config/config.php"); 
    require_once("../../config/".ENV."_config.php");
?>

<?php
    if(isset($_SESSION['user_id']))
    {
        if(isset($_POST['hidden_seller_image']))
        {
            $data['user_id']=$_SESSION['user_id'];

            $image=$_FILES['seller_image']['tmp_name'];

            if(empty($image) || $image=="")
            {
                $data['imagestatus']=0;
                $data['image_name']=$_POST['hidden_seller_image'];
            }
            else
            {
                $data['imagestatus']=1;
                $data['image_name']=$_POST['hidden_seller_image'];
                $image = file_get_contents($image);
                $image= base64_encode($image);
                $data['seller_image']=$image;
            }

            $url=DOMAIN.'/rest/seller/updateSellerImageRest.php';
            $output=getRestApiResponse($url,$data);

            if(isset($output['updatesellerimage']) && $output['updatesellerimage']['response_code']==200)
            {
                if($output['updatesellerimage']['image']!="")
                    $_SESSION['seller_image']=$output['updatesellerimage']['image'];

                $response['status']=1;
                echo json_encode($response);
            }
            else
            {
                $response['status']=0;
                echo json_encode($response);
            }
        }
    }
?>

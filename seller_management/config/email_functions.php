<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../public/PHPMailer/src/PHPMailer.php';
require '../../public/PHPMailer/src/Exception.php';
require '../../public/PHPMailer/src/SMTP.php';
require_once("config.php"); 
require_once(ENV."_config.php");
//SEND MAIL 
function sendMail($receiver,$subject,$body)
{
  	$mail = new PHPMailer(true);

	try 
	{
	    //Server settings
	   // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
	    $mail->isSMTP();                                            // Send using SMTP
	    $mail->Host       = EMAIL_HOST;                    // Set the SMTP server to send through
	    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
	    $mail->Username   = SENDER_MAIL;                     // SMTP username
	    $mail->Password   = SENDER_PASSWORD;                               // SMTP password
	    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

	    //Recipients
	    $mail->setFrom(SENDER_MAIL,DOMAIN_NAME);
	    $mail->addAddress($receiver);     // Add a recipient
	    //$mail->addReplyTo('daggupatimahesh291@gmail.com', 'Information');
	    // $mail->addCC('cc@example.com');
	    // $mail->addBCC('bcc@example.com');

	    // Attachments
	    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	    // Content
	    //$mail->msgHTML(file_get_contents('message.html'), __DIR__);
	    $mail->isHTML(true);                                  // Set email format to HTML
	    $mail->Subject = $subject;
	    $mail->Body    = $body;
	    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	    $mail->send();
	    //echo 'Message has been sent';
  	} 
	catch(Exception $e) 
  	{
    	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  	}
}
function sendMail1($receiver,$subject,$body,$attachment)
{
  	$mail = new PHPMailer(true);
  	$response=0;
	try 
	{
	    //Server settings
	   // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
	    $mail->isSMTP();                                            // Send using SMTP
	    $mail->Host       = EMAIL_HOST;                    // Set the SMTP server to send through
	    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
	    $mail->Username   = SENDER_MAIL;                     // SMTP username
	    $mail->Password   = SENDER_PASSWORD;                               // SMTP password
	    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

	    //Recipients
	    $mail->setFrom(SENDER_MAIL,DOMAIN_NAME);
	    $mail->addAddress($receiver);     // Add a recipient
	    //$mail->addReplyTo('daggupatimahesh291@gmail.com', 'Information');
	    // $mail->addCC('cc@example.com');
	    // $mail->addBCC('bcc@example.com');

	    // Attachments
	    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	    if($attachment!="")
	    	$mail->addStringAttachment($attachment,"attachment.pdf");

	    // Content
	    //$mail->msgHTML(file_get_contents('message.html'), __DIR__);
	    $mail->isHTML(true);                                  // Set email format to HTML
	    $mail->Subject = $subject;
	    $mail->Body    = $body;
	    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	    // $mail->send();
	    //echo 'Message has been sent';

	    if($mail->send())
	    	$response=1;
	    else
	    	$response=0;
  	} 
	catch(Exception $e) 
  	{
    	//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    	$response=0;
  	}
  	return $response;
}

?>

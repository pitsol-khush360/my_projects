<?php
//defined("KEY") ? null : define("KEY","35F7DF4C8D9D3F");
// defined("SENDER_MAIL") ? null : define("SENDER_MAIL","daggupati.mahesh@uatcode.com");
// defined("SENDER_PASSWORD") ? null : define("SENDER_PASSWORD","Abc12345");
// defined("EMAIL_HOST") ? null : define("EMAIL_HOST","smtp.hostinger.com");
// defined("DOMAIN_NAME") ? null : define("DOMAIN_NAME","BLUTSE");
$orderId='';
defined("SUBJECT_SHIPPED") ? null : define("SUBJECT_SHIPPED","Order Shipped");
defined("BODY_SHIPPED") ? null : define("BODY_SHIPPED","Your Order has been Shipped and is Out for Delivery.
	Your Order Id is ");

defined("SUBJECT_ORDER") ? null : define("SUBJECT_ORDER","Order Booked");
defined("BODY_ORDER") ? null : define("BODY_ORDER","Your Order has been booked, Order Id : ");
defined("SELLER_BODY_ORDER") ? null : define("SELLER_BODY_ORDER","You have recieved an order. Order id : ");

defined("SUBJECT_DELIVERED") ? null : define("SUBJECT_DELIVERED","Order Delivered");
defined("BODY_DELIVERED") ? null : define("BODY_DELIVERED","Your Order has been Delivered. Your Order ");

defined("SUBJECT_RETURNED") ? null : define("SUBJECT_RETURNED","Order Returned");
defined("BODY_RETURNED") ? null : define("BODY_RETURNED","Your Order has been Returned.<br>
	Your Order Id is ");

defined("SUBJECT_REJECTED") ? null : define("SUBJECT_REJECTED","Order Rejected");
defined("BODY_REJECTED") ? null : define("BODY_REJECTED","Your Order has been declined by the Seller. Please contact seller. Your Order Id is ");

defined("SUBJECT_MONEYADDED") ? null : define("SUBJECT_MONEYADDED","Wallet Balance");
defined("BODY_MONEYADDED") ? null : define("BODY_MONEYADDED"," Your Seller Plus Wallet has been Credited with INR ");
?>

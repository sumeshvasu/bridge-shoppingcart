<?php
//start session in all pages
if (session_status() == PHP_SESSION_NONE) { session_start(); } //PHP >= 5.4.0
//if(session_id() == '') { session_start(); } //uncomment this line if PHP < 5.4.0 and comment out line above

$PayPalMode 			= 'sandbox'; // sandbox or live
$PayPalApiUsername 		= 'sobin87-facilitator_api1.gmail.com'; //PayPal API Username
$PayPalApiPassword 		= '1406782475'; //Paypal API password
$PayPalApiSignature 	= 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AO6y-681EaMbmEcM5aJnJMbVssg1'; //Paypal API Signature
$PayPalCurrencyCode 	= 'USD'; //Paypal Currency Code
$PayPalReturnURL 		= 'http://localhost/paypal/process.php'; //Point to process.php page
$PayPalCancelURL 		= 'http://localhost/paypal/cancel_url.php'; //Cancel URL if user clicks cancel
?>
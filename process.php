<?php

include('config.php');
include_once 'PHPMailer/PHPMailerAutoload.php';
include_once("paypal/paypal-config.php");
include_once("paypal/paypal.class.php");
include_once('controller/application-controller.php');
include_once('controller/database-controller.php');
include_once('controller/product-controller.php');

$paypalmode = ($PayPalMode == 'sandbox') ? '.sandbox' : '';

$db     = new DataBaseController();
$mysqli = $db->dbConnect('mysqli');

$app = new AppController();

if ($_POST) //Post Data received from product list page.
{

    $buyer_id        = $_SESSION["user_id"];
    $ItemName        = $_POST["itemname"]; //Item Name
    $ItemPrice       = $_POST["itemprice"]; //Item Price
    $ItemNumber      = $_POST["itemnumber"]; //Item Number
    $ItemDesc        = $_POST["itemdesc"]; //Item Number
    $ItemQty         = $_POST["itemQty"]; // Item Quantity
    $ItemTotalPrice  = ($ItemPrice * $ItemQty); //(Item Price x Quantity = Total) Get total amount of product;
    //Other important variables like tax, shipping cost
    $TotalTaxAmount  = 0.00;  //Sum of tax for all items in this order.
    $HandalingCost   = 0.00;  //Handling cost for this order.
    $InsuranceCost   = 0.00;  //shipping insurance cost for this order.
    $ShippinDiscount = 0.00; //Shipping discount for this order. Specify this as negative number.
    $ShippinCost     = 0.00; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
    //Grand total including all tax, insurance, shipping cost and discount
    $GrandTotal      = ($ItemTotalPrice + $TotalTaxAmount + $HandalingCost + $InsuranceCost + $ShippinCost + $ShippinDiscount);

    // Save the initial purchse data to db
    $query = "INSERT INTO bs_purchases
      (productId, userId, dateTime, transactionId, totalPrice, paymentStatus)
      VALUES ($ItemNumber, $buyer_id, NOW(), '', $GrandTotal, 'Pending')";

    $insert_row = $mysqli->query($query);

    if ($insert_row)
    {
        $purchase_id = $mysqli->insert_id;
    }
    else
    {
        die('Error : (' . $mysqli->errno . ') ' . $mysqli->error);
    }

    $PayPalReturnURL .= '?purchase_id=' . $purchase_id;
    //Parameters for SetExpressCheckout, which will be sent to PayPal
    $padata = '&METHOD=SetExpressCheckout' .
            '&RETURNURL=' . ($PayPalReturnURL ) .
            '&CANCELURL=' . ($PayPalCancelURL) .
            '&PAYMENTREQUEST_0_PAYMENTACTION=' . ("SALE") .
            '&L_PAYMENTREQUEST_0_NAME0=' . ($ItemName) .
            '&L_PAYMENTREQUEST_0_NUMBER0=' . ($ItemNumber) .
            '&L_PAYMENTREQUEST_0_DESC0=' . ($ItemDesc) .
            '&L_PAYMENTREQUEST_0_AMT0=' . ($ItemPrice) .
            '&L_PAYMENTREQUEST_0_QTY0=' . ($ItemQty) .
            /*
              //Additional products (L_PAYMENTREQUEST_0_NAME0 becomes L_PAYMENTREQUEST_0_NAME1 and so on)
              '&L_PAYMENTREQUEST_0_NAME1='.($ItemName2).
              '&L_PAYMENTREQUEST_0_NUMBER1='.($ItemNumber2).
              '&L_PAYMENTREQUEST_0_DESC1='.($ItemDesc2).
              '&L_PAYMENTREQUEST_0_AMT1='.($ItemPrice2).
              '&L_PAYMENTREQUEST_0_QTY1='. ($ItemQty2).
             */

            /*
              //Override the buyer's shipping address stored on PayPal, The buyer cannot edit the overridden address.
              '&ADDROVERRIDE=1'.
              '&PAYMENTREQUEST_0_SHIPTONAME=J Smith'.
              '&PAYMENTREQUEST_0_SHIPTOSTREET=1 Main St'.
              '&PAYMENTREQUEST_0_SHIPTOCITY=San Jose'.
              '&PAYMENTREQUEST_0_SHIPTOSTATE=CA'.
              '&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=US'.
              '&PAYMENTREQUEST_0_SHIPTOZIP=95131'.
              '&PAYMENTREQUEST_0_SHIPTOPHONENUM=408-967-4444'.
             */

            '&NOSHIPPING=0' . //set 1 to hide buyer's shipping address, in-case products that does not require shipping

            '&PAYMENTREQUEST_0_ITEMAMT=' . ($ItemTotalPrice) .
            '&PAYMENTREQUEST_0_TAXAMT=' . ($TotalTaxAmount) .
            '&PAYMENTREQUEST_0_SHIPPINGAMT=' . ($ShippinCost) .
            '&PAYMENTREQUEST_0_HANDLINGAMT=' . ($HandalingCost) .
            '&PAYMENTREQUEST_0_SHIPDISCAMT=' . ($ShippinDiscount) .
            '&PAYMENTREQUEST_0_INSURANCEAMT=' . ($InsuranceCost) .
            '&PAYMENTREQUEST_0_AMT=' . ($GrandTotal) .
            '&PAYMENTREQUEST_0_CURRENCYCODE=' . ($PayPalCurrencyCode) .
            '&LOCALECODE=GB' . //PayPal pages to match the language on your website.            
            '&CARTBORDERCOLOR=FFFFFF' . //border color of cart
            '&ALLOWNOTE=1';

    ############# set session variable for "DoExpressCheckoutPayment" #######
    $_SESSION['ItemName']        = $ItemName; //Item Name
    $_SESSION['ItemPrice']       = $ItemPrice; //Item Price
    $_SESSION['ItemNumber']      = $ItemNumber; //Item Number
    $_SESSION['ItemDesc']        = $ItemDesc; //Item Number
    $_SESSION['ItemQty']         = $ItemQty; // Item Quantity
    $_SESSION['ItemTotalPrice']  = $ItemTotalPrice; //(Item Price x Quantity = Total) Get total amount of product;
    $_SESSION['TotalTaxAmount']  = $TotalTaxAmount;  //Sum of tax for all items in this order.
    $_SESSION['HandalingCost']   = $HandalingCost;  //Handling cost for this order.
    $_SESSION['InsuranceCost']   = $InsuranceCost;  //shipping insurance cost for this order.
    $_SESSION['ShippinDiscount'] = $ShippinDiscount; //Shipping discount for this order. Specify this as negative number.
    $_SESSION['ShippinCost']     = $ShippinCost; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
    $_SESSION['GrandTotal']      = $GrandTotal;


    //We need to execute the "SetExpressCheckOut" method to obtain paypal token
    $paypal               = new MyPayPal();
    $httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

    //Respond according to message we receive from Paypal
    if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
    {
        //Redirect user to PayPal store with Token received.
        $paypalurl = 'https://www' . $paypalmode . '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $httpParsedResponseAr["TOKEN"] . '';
        header('Location: ' . $paypalurl);
    }
    else
    {
        $_SESSION['payment_error_detail'] = $httpParsedResponseAr["L_LONGMESSAGE0"];
        $app->redirect('index.php?page=paymentResponse&status=error');
    }
}

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if (isset($_GET["token"]) && isset($_GET["PayerID"]))
{
    //we will be using these two variables to execute the "DoExpressCheckoutPayment"
    //Note: we haven't received any payment yet.

    $token    = $_GET["token"];
    $payer_id = $_GET["PayerID"];

    //get session variables
    $ItemName        = $_SESSION['ItemName']; //Item Name
    $ItemPrice       = $_SESSION['ItemPrice']; //Item Price
    $ItemNumber      = $_SESSION['ItemNumber']; //Item Number
    $ItemDesc        = $_SESSION['ItemDesc']; //Item Number
    $ItemQty         = $_SESSION['ItemQty']; // Item Quantity
    $ItemTotalPrice  = $_SESSION['ItemTotalPrice']; //(Item Price x Quantity = Total) Get total amount of product;
    $TotalTaxAmount  = $_SESSION['TotalTaxAmount'];  //Sum of tax for all items in this order.
    $HandalingCost   = $_SESSION['HandalingCost'];  //Handling cost for this order.
    $InsuranceCost   = $_SESSION['InsuranceCost'];  //shipping insurance cost for this order.
    $ShippinDiscount = $_SESSION['ShippinDiscount']; //Shipping discount for this order. Specify this as negative number.
    $ShippinCost     = $_SESSION['ShippinCost']; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
    $GrandTotal      = $_SESSION['GrandTotal'];

    $padata = '&TOKEN=' . ($token) .
            '&PAYERID=' . ($payer_id) .
            '&PAYMENTREQUEST_0_PAYMENTACTION=' . ("SALE") .
            //set item info here, otherwise we won't see product details later
            '&L_PAYMENTREQUEST_0_NAME0=' . ($ItemName) .
            '&L_PAYMENTREQUEST_0_NUMBER0=' . ($ItemNumber) .
            '&L_PAYMENTREQUEST_0_DESC0=' . ($ItemDesc) .
            '&L_PAYMENTREQUEST_0_AMT0=' . ($ItemPrice) .
            '&L_PAYMENTREQUEST_0_QTY0=' . ($ItemQty) .
            /*
              //Additional products (L_PAYMENTREQUEST_0_NAME0 becomes L_PAYMENTREQUEST_0_NAME1 and so on)
              '&L_PAYMENTREQUEST_0_NAME1='.($ItemName2).
              '&L_PAYMENTREQUEST_0_NUMBER1='.($ItemNumber2).
              '&L_PAYMENTREQUEST_0_DESC1=Description text'.
              '&L_PAYMENTREQUEST_0_AMT1='.($ItemPrice2).
              '&L_PAYMENTREQUEST_0_QTY1='. ($ItemQty2).
             */

            '&PAYMENTREQUEST_0_ITEMAMT=' . ($ItemTotalPrice) .
            '&PAYMENTREQUEST_0_TAXAMT=' . ($TotalTaxAmount) .
            '&PAYMENTREQUEST_0_SHIPPINGAMT=' . ($ShippinCost) .
            '&PAYMENTREQUEST_0_HANDLINGAMT=' . ($HandalingCost) .
            '&PAYMENTREQUEST_0_SHIPDISCAMT=' . ($ShippinDiscount) .
            '&PAYMENTREQUEST_0_INSURANCEAMT=' . ($InsuranceCost) .
            '&PAYMENTREQUEST_0_AMT=' . ($GrandTotal) .
            '&PAYMENTREQUEST_0_CURRENCYCODE=' . ($PayPalCurrencyCode);

    // Execute the "DoExpressCheckoutPayment" at to Receive payment from user.
    $paypal               = new MyPayPal();
    $httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
    //Check if everything went ok..
    if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
    {
        /*
          //Sometimes Payment are kept pending even when transaction is complete.
          //hence we need to notify user about it and ask him manually approve the transiction
         */
        $payment_status = $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTSTATUS"];
        if ('Completed' == $payment_status)
        {
            $_SESSION['payment_message'] = '<div style="color:green">Payment Received! Your product will be sent to you very soon!</div>';
        }
        elseif ('Pending' == $payment_status)
        {
            $_SESSION['payment_message'] = '<div style="color:red">Transaction Complete, but payment is still pending! ' .
                    'You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a></div>';
        }

        $padata               = '&TOKEN=' . ($token);
        $paypal               = new MyPayPal();
        $httpParsedResponseAr = $paypal->PPHttpPost('GetExpressCheckoutDetails', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);

        if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
        {
            $buyerName      = $httpParsedResponseAr["FIRSTNAME"] . ' ' . $httpParsedResponseAr["LASTNAME"];
            $buyerEmail     = $httpParsedResponseAr["EMAIL"];
            $purchase_id    = (isset($_GET['purchase_id'])) ? $_GET['purchase_id'] : null;
            $transaction_id = $httpParsedResponseAr['PAYMENTREQUESTINFO_0_TRANSACTIONID'];
            $product_id     = $httpParsedResponseAr["L_PAYMENTREQUEST_0_NUMBER0"];

            if ($purchase_id)
            {
                // Save purchase data
                $query = "UPDATE bs_purchases SET transactionId = '$transaction_id', paymentStatus = '$payment_status' 
                  WHERE id = $purchase_id";

                $insert_row = $mysqli->query($query);

                if ($insert_row)
                {
                    // Send an email to buyer with the download link                                        
                    $product = new ProductController();
                    $product->generate_download_link($purchase_id, $buyerEmail, $product_id, $config['base_url']);
                }
                else
                {
                    die('Error : (' . $mysqli->errno . ') ' . $mysqli->error);
                }
            }

            $req  = 'cmd=_notify-validate';
            $test = array();
            foreach ($httpParsedResponseAr as $key => $value)
            {
                $value      = (stripslashes($value));
                $value      = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
                $test[$key] = urldecode($value);
            }

            $app->redirect('index.php?page=paymentResponse&status=success');
        }
        else
        {
            $_SESSION['payment_error_detail'] = urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
            $app->redirect('index.php?page=paymentResponse&status=error');
        }
    }
    else
    {
        $_SESSION['payment_error_detail'] = urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]);
        $app->redirect('index.php?page=paymentResponse&status=error');
    }
}
?>

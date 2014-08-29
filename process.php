<?php

include('config.php');
include_once 'PHPMailer/PHPMailerAutoload.php';
include_once("paypal/paypal-config.php");
include_once("paypal/paypal.class.php");
include_once('controller/application-controller.php');
include_once('controller/database-controller.php');
include_once('controller/product-controller.php');
include_once('controller/user-controller.php');

$paypalmode = ($PayPalMode == 'sandbox') ? '.sandbox' : '';

$db     = new DataBaseController();
$mysqli = $db->db_connect('mysqli');

$app = new AppController();

if ($_POST) //Post Data received from product list page.
{

    $buyer_id        = $_SESSION["user_id"];    
    $item_name        = $_POST["itemname"]; //Item Name
    $item_price       = $_POST["itemprice"]; //Item Price
    $item_number      = $_POST["itemnumber"]; //Item Number
    $item_desc        = $_POST["itemdesc"]; //Item Number
    $item_qty         = $_POST["itemQty"]; // Item Quantity
    $Item_total_price  = ($item_price * $item_qty); //(Item Price x Quantity = Total) Get total amount of product;
    
    
    //Other important variables like tax, shipping cost
    $total_tax_amount  = 0.00;  //Sum of tax for all items in this order.
    $handaling_cost   = 0.00;  //Handling cost for this order.
    $insurance_cost   = 0.00;  //shipping insurance cost for this order.
    $shippin_discount = 0.00; //Shipping discount for this order. Specify this as negative number.
    $shippin_cost     = 0.00; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
    //Grand total including all tax, insurance, shipping cost and discount
    $grand_total      = ($Item_total_price + $total_tax_amount + $handaling_cost + $insurance_cost + $shippin_cost + $shippin_discount);

    // Save the initial purchse data to db
    $query = "INSERT INTO bs_purchases
      (user_id, date_time, transaction_id, total_price, payment_status)
      VALUES ($buyer_id, NOW(), '', $grand_total, 'Pending')";

    $insert_row = $mysqli->query($query);

    if ($insert_row)
    {
        $purchase_id = $mysqli->insert_id;
        // Insert into purchase products table
        $query = "INSERT INTO bs_purchase_products(purchase_id, product_id) VALUES ($purchase_id, $item_number)";
        $insert_row = $mysqli->query($query);
        
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
            '&L_PAYMENTREQUEST_0_NAME0=' . ($item_name) .
            '&L_PAYMENTREQUEST_0_NUMBER0=' . ($item_number) .
            '&L_PAYMENTREQUEST_0_DESC0=' . ($item_desc) .
            '&L_PAYMENTREQUEST_0_AMT0=' . ($item_price) .
            '&L_PAYMENTREQUEST_0_QTY0=' . ($item_qty) .
            /*
              //Additional products (L_PAYMENTREQUEST_0_NAME0 becomes L_PAYMENTREQUEST_0_NAME1 and so on)
              '&L_PAYMENTREQUEST_0_NAME1='.($item_name2).
              '&L_PAYMENTREQUEST_0_NUMBER1='.($item_number2).
              '&L_PAYMENTREQUEST_0_DESC1='.($item_desc2).
              '&L_PAYMENTREQUEST_0_AMT1='.($item_price2).
              '&L_PAYMENTREQUEST_0_QTY1='. ($item_qty2).
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

            '&PAYMENTREQUEST_0_ITEMAMT=' . ($Item_total_price) .
            '&PAYMENTREQUEST_0_TAXAMT=' . ($total_tax_amount) .
            '&PAYMENTREQUEST_0_SHIPPINGAMT=' . ($shippin_cost) .
            '&PAYMENTREQUEST_0_HANDLINGAMT=' . ($handaling_cost) .
            '&PAYMENTREQUEST_0_SHIPDISCAMT=' . ($shippin_discount) .
            '&PAYMENTREQUEST_0_INSURANCEAMT=' . ($insurance_cost) .
            '&PAYMENTREQUEST_0_AMT=' . ($grand_total) .
            '&PAYMENTREQUEST_0_CURRENCYCODE=' . ($PayPalCurrencyCode) .
            '&LOCALECODE=GB' . //PayPal pages to match the language on your website.            
            '&CARTBORDERCOLOR=FFFFFF' . //border color of cart
            '&ALLOWNOTE=1';

    ############# set session variable for "DoExpressCheckoutPayment" #######
    $_SESSION['ItemName']        = $item_name; //Item Name
    $_SESSION['ItemPrice']       = $item_price; //Item Price
    $_SESSION['ItemNumber']      = $item_number; //Item Number
    $_SESSION['ItemDesc']        = $item_desc; //Item Number
    $_SESSION['ItemQty']         = $item_qty; // Item Quantity
    $_SESSION['ItemTotalPrice']  = $Item_total_price; //(Item Price x Quantity = Total) Get total amount of product;
    $_SESSION['TotalTaxAmount']  = $total_tax_amount;  //Sum of tax for all items in this order.
    $_SESSION['HandalingCost']   = $handaling_cost;  //Handling cost for this order.
    $_SESSION['InsuranceCost']   = $insurance_cost;  //shipping insurance cost for this order.
    $_SESSION['ShippinDiscount'] = $shippin_discount; //Shipping discount for this order. Specify this as negative number.
    $_SESSION['ShippinCost']     = $shippin_cost; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
    $_SESSION['GrandTotal']      = $grand_total;


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
    $item_name        = $_SESSION['ItemName']; //Item Name
    $item_price       = $_SESSION['ItemPrice']; //Item Price
    $item_number      = $_SESSION['ItemNumber']; //Item Number
    $item_desc        = $_SESSION['ItemDesc']; //Item Number
    $item_qty         = $_SESSION['ItemQty']; // Item Quantity
    $Item_total_price  = $_SESSION['ItemTotalPrice']; //(Item Price x Quantity = Total) Get total amount of product;
    $total_tax_amount  = $_SESSION['TotalTaxAmount'];  //Sum of tax for all items in this order.
    $handaling_cost   = $_SESSION['HandalingCost'];  //Handling cost for this order.
    $insurance_cost   = $_SESSION['InsuranceCost'];  //shipping insurance cost for this order.
    $shippin_discount = $_SESSION['ShippinDiscount']; //Shipping discount for this order. Specify this as negative number.
    $shippin_cost     = $_SESSION['ShippinCost']; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
    $grand_total      = $_SESSION['GrandTotal'];

    $padata = '&TOKEN=' . ($token) .
            '&PAYERID=' . ($payer_id) .
            '&PAYMENTREQUEST_0_PAYMENTACTION=' . ("SALE") .
            //set item info here, otherwise we won't see product details later
            '&L_PAYMENTREQUEST_0_NAME0=' . ($item_name) .
            '&L_PAYMENTREQUEST_0_NUMBER0=' . ($item_number) .
            '&L_PAYMENTREQUEST_0_DESC0=' . ($item_desc) .
            '&L_PAYMENTREQUEST_0_AMT0=' . ($item_price) .
            '&L_PAYMENTREQUEST_0_QTY0=' . ($item_qty) .
            /*
              //Additional products (L_PAYMENTREQUEST_0_NAME0 becomes L_PAYMENTREQUEST_0_NAME1 and so on)
              '&L_PAYMENTREQUEST_0_NAME1='.($item_name2).
              '&L_PAYMENTREQUEST_0_NUMBER1='.($item_number2).
              '&L_PAYMENTREQUEST_0_DESC1=Description text'.
              '&L_PAYMENTREQUEST_0_AMT1='.($item_price2).
              '&L_PAYMENTREQUEST_0_QTY1='. ($item_qty2).
             */

            '&PAYMENTREQUEST_0_ITEMAMT=' . ($Item_total_price) .
            '&PAYMENTREQUEST_0_TAXAMT=' . ($total_tax_amount) .
            '&PAYMENTREQUEST_0_SHIPPINGAMT=' . ($shippin_cost) .
            '&PAYMENTREQUEST_0_HANDLINGAMT=' . ($handaling_cost) .
            '&PAYMENTREQUEST_0_SHIPDISCAMT=' . ($shippin_discount) .
            '&PAYMENTREQUEST_0_INSURANCEAMT=' . ($insurance_cost) .
            '&PAYMENTREQUEST_0_AMT=' . ($grand_total) .
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
            $buyerEmail     = urldecode($httpParsedResponseAr["EMAIL"]);
            $purchase_id    = (isset($_GET['purchase_id'])) ? $_GET['purchase_id'] : null;
            $transaction_id = $httpParsedResponseAr['PAYMENTREQUESTINFO_0_TRANSACTIONID'];
            $product_id     = $httpParsedResponseAr["L_PAYMENTREQUEST_0_NUMBER0"];

            if ($purchase_id)
            {
                // Save purchase data
                $query = "UPDATE bs_purchases SET transaction_id = '$transaction_id', payment_status = '$payment_status' 
                  WHERE id = $purchase_id";

                $insert_row = $mysqli->query($query);

                if ($insert_row)
                {
                    $emails    = array(array(
                            'email' => $buyerEmail,
                            'name'  => $buyerName
                        )
                    );
                    $user      = new UserController();
                    $user_info = $user->get(array('id' => $_SESSION['user_id']));
                    if (!empty($user_info))
                    {
                        $user_email = (isset($user_info['email'])) ? $user_info['email'] : '';
                        $user_name  = (isset($user_info['firstname'])) ? $user_info['firstname'] : $user_info['username'];
                        $emails[]   = array(
                            'email' => $user_email,
                            'name'  => $user_name
                        );
                    }
                    // Send an email to buyer with the download link                                        
                    $product = new ProductController();
                    $product->generate_download_link($purchase_id, $emails, $product_id, $config['base_url']);
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

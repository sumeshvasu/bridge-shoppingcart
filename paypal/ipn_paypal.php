<?php
echo 'paypal IPN URL';
//include_once("paypal-config.php");

       //Change these with your information
    $paypalmode = 'sandbox'; //Sandbox for testing or empty ''
    $dbusername     = 'root'; //db username
    $dbpassword     = ''; //db password
    $dbhost     = 'localhost'; //db host
    $dbname     = 'bridge-store'; //db name

if($_POST)
{echo 'Post Found';
        if($paypalmode=='sandbox')
        {
            $paypalmode     =   '.sandbox';
        }
        $req = 'cmd=' . urlencode('_notify-validate'). '&rm=2';
        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www'.$paypalmode.'.sandbox.paypal.com'));
        $res = curl_exec($ch);
        curl_close($ch);
        if (strcmp ($res, "VERIFIED") == 0 )
        {echo 'Verified';
            $transaction_id = $_POST['txn_id'];
            $payerid = $_POST['payer_id'];
            $firstname = $_POST['first_name'];
            $lastname = $_POST['last_name'];
            $payeremail = $_POST['payer_email'];
            $paymentdate = $_POST['payment_date'];
            $paymentstatus = $_POST['payment_status'];
            $mdate= date('Y-m-d h:i:s',strtotime($paymentdate));
            $otherstuff = json_encode($_POST);

            $conn = mysql_connect($dbhost,$dbusername,$dbpassword);
            if (!$conn)
            {
             die('Could not connect: ' . mysql_error());
            }

            mysql_select_db($dbname, $conn);

            // insert in our IPN record table
            $query = "INSERT INTO ibn_table
            (itransaction_id,ipayerid,iname,iemail,itransaction_date, ipaymentstatus,ieverything_else)
            VALUES
            ('123','asdsa','sadas','sdsd','now()', 'zxxx','zxcx')";

            if(!mysql_query($query))
            {
                die('mysql error..!');
            }
            else
                die('DB updated');
            mysql_close($conn);

        }
        else
            die('Invalid');
}
else {
    die('No post Found');
}
?>
<form target="_new" method="post" action="https://10.0.0.19/bridge-shoppingcart/paypal/ipn_paypal.php">
<input type="hidden" name="txn_id" value="SomeValue1"/>
<input type="hidden" name="payer_id" value="SomeValue2"/>
<input type="hidden" name="first_name" value="SomeValue2"/>
<input type="hidden" name="last_name" value="SomeValue2"/>
<input type="hidden" name="payer_email" value="SomeValue2"/>
<input type="hidden" name="payment_date" value=""/>
<input type="hidden" name="payment_status" value="Confirm"/>

<!-- code for other variables to be tested ... -->

<input type="submit" value="Submit"/>
</form>
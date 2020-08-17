<?php




require('config.php');
require_once('pdo.php');//this is ur connection file replace it with urs
session_start();

require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;

$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false)
{
    $api = new Api($keyId, $keySecret);

    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

if ($success === true)
{
  if(!isset($_SESSION['reload_block'])){


$_SESSION['reload_block']="block";
  $stmn = $pdo->prepare('INSERT INTO payment
           (payments_id, orderId, transaction_amount, transaction_status) VALUES ( :uname, :pwd, :mb, :em)');//replace table_name with urs
           $stmn->execute(array(
                   ':uname' => $_POST['razorpay_payment_id'],
                   ':pwd' => $_SESSION['razorpay_order_id'],
                   ':mb' => $_SESSION['amount'],
                   ':em' => "Successfull"

              )
            );}
//shows the razorpay payment id to customer
    $html = "<p>Your payment was successful</p>
             <p>Payment ID: {$_POST['razorpay_payment_id']}</p>";





}
else
{
    $html = "<p>Your payment failed</p>
             <p>{$error}</p>";
}

echo $html;

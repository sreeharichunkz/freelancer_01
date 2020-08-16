<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('config.php');
require('razorpay-php/Razorpay.php');
require_once('pdo.php');//this is ur connection file replace it with urs
     unset($_SESSION['reload_block']);
?>

<?
// Create the Razorpay Order
use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);
//
// We create an razorpay order using orders api
// Docs: https://docs.razorpay.com/docs/orders
//
$orderData = [
    'receipt'         => 3456,
    'amount'          => 1 * 100, // replace it with the amount you want to collect, The shown one will collect rs 1
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;

$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR')
{
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}

$checkout = 'manual';

if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true))
{   unset($_SESSION['payed_with_coin']);
      unset($_SESSION['contest_joined']);
         unset($_SESSION['posted']);
    $checkout = $_GET['checkout'];
}
$_SESSION['amount']=$amount;
$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => "Ur Website name",
    "description"       => "pt description",
    "image"             => "put ur logo",
    "buttontext"        => "Text for button",
    "prefill"           => [

    "name"              => "dfdf",
    "email"             => "example@gmail.com",
    "contact"           => "9876543210",

    ],
    "notes"             => [
    "address"           => "Hello World",
    "merchant_order_id" => "12312321",
    ],
    "theme"             => [
    "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);



?>







     <!-- below shownn code snippet is the payment button place it where ever required -->

            <?  require("checkout/{$checkout}.php");



            ?>

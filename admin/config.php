

<?php
require_once "../vendor/autoload.php";


use Omnipay\Omnipay;

define('CLIENT_ID', 'AeE4QeJxxVIPOj63nio46jGFm5GuJViQx3jvxbt7r914va7u_dErANYI3ibtzpdDYDhHp2qK0Ku-EQ1l');
define('CLIENT_SECRET', 'EKEcW6oc16u87T3IQr5X8oHUhYSQF_CFgqlQhMYK_6u1lNLLkneHI9FpW73FM-envwHQLjaLbklluuEk');

define('PAYPAL_RETURN_URL', 'http://localhost/admin/success.php');
define('PAYPAL_CANCEL_URL', 'http://localhost/index.php?page=checkout');
define('PAYPAL_CURRENCY', 'MYR'); // set your currency here

// Connect with the database

include('../admin/db_connect.php');




$gateway = Omnipay::create('PayPal_Rest');
$gateway->setClientId(CLIENT_ID);
$gateway->setSecret(CLIENT_SECRET);
$gateway->setTestMode(true); //set it to 'false' when go live
<?php 
//testing product detail 
 // Path to the SessionManager class




$itemNumber = "AI12345";
$itemName = "Test Product";
//$ item price is $_SESSION['total'];
$itemPrice = "100";

$currency = "MYR";
/* paypal REST API configuration 

*gerenate API credentials from paypal developer panel 
*https://developer.paypal.com/developer/applications

*/

define('PAYPAL_SANDBOX', TRUE);
define('PAYPAL_SANDBOX_CLIENT_ID', 'AeE4QeJxxVIPOj63nio46jGFm5GuJViQx3jvxbt7r914va7u_dErANYI3ibtzpdDYDhHp2qK0Ku-EQ1l');
define('PAYPAL_SANDBOX_CLIENT_SECRET', 'EKEcW6oc16u87T3IQr5X8oHUhYSQF_CFgqlQhMYK_6u1lNLLkneHI9FpW73FM-envwHQLjaLbklluuEk');
define('PAYPAL_LIVE_CLIENT_ID', 'AVrlDPQwI4UM-IjanvPDZm2V29qdad0D3IrwV8cQGRuItFVFG2t3r_s_8m8HeV35hKqo3TVoLbN_tzK5');
define('PAYPAL_LIVE_CLIENT_SECRET', 'EMmMNavEzl0sl9HlXW6Y7CEKEYU8iL5kuhloepYA0Lmus3fFRDw71K5VYGlGZ2OcojiyNUzoB79JhZKp');




// Database configuration  
define('DB_HOST', 'localhost');  
define('DB_USERNAME', 'root');  
define('DB_PASSWORD', 'root');  
define('DB_NAME', 'pakmalausatay_db'); 

<?php

namespace Sample;

require __DIR__ . '/vendor/autoload.php';
// 1. Import the PayPal SDK client that was created in `Set up Server-Side SDK`.
use Sample\PayPalClient;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

require 'paypalclient.php';
$orderID = $_GET['orderID'];
class GetOrder 
{
    //2. Set up your server to receive a call from the client
    /**
     * You can use this function to retrieve an order by passing order ID as an argument.
     */
    public static function getOrder($orderId){

        // 3. Call PayPal to get the transaction details
        $client = PayPalClient::client();
        $response = $client->execute(new OrdersGetRequest($orderID));
        //transaction details
        $orderID = $response->result->id;
        $email = $response->result->payer->email_address;
        $name = (object) $response->result->purchase_units[0]->shipping->name->full_name;
        $address1 = $response->result->purchase_units[0]->address->address_line_1;
        $address2 = $response->result->purchase_units[0]->address->admin_area_2;
        $address3 = $response->result->purchase_units[0]->address->admin_area_1;
        $address4 = $response->result->purchase_units[0]->address->postal_code;
        $address5 = $response->result->purchase_units[0]->address->country_code;
        $FullAddress = $address1 . "," . $address2 . "," . $address3 . "," . $address4 . "," . $address5;

        //Insert details to our database 
        include 'admin/db_connect.php';
        $db = $conn;
        $stmt = $db->prepare("INSERT INTO transactions (payer_name,payer_email,order_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $orderID);
        $stmt->execute();
        if (!$stmt){
            echo 'There was a problem on your code' . mysql_error($db);
        }
        else{
            header("Location:../receipt.php");
        }

    }
}
/**
 * This driver function invokes the getOrder function to retrieve
 * sample order details.
 * 
 * To get the correct order ID, this sample uses createOrder to create an order
 */
if(!count(debug_backtrace()))
{
    GetOrder::getOrder('$orderID', true);
}
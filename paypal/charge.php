<?php 

require_once 'config.php';

// i want to set submit by name of the button
// if button is set


if (isset($_POST['submit'])){  
    try {
        $response = $gateway->purchase(array(
            'amount' => $_POST['amount'],
            'currency' => PAYPAL_CURRENCY,
            'returnUrl' => PAYPAL_RETURN_URL,
            'cancelUrl' => PAYPAL_CANCEL_URL,
            'items' => array(
                array(
                    'name' => $_POST['item_name'],
                    'quantity' => 1,
                    'price' => $_POST['amount']
                )
            )
        ))->send();
        if ($response->isRedirect()){
            //forward customer to paypal
            //set as modal redirect
            $response->redirect();
             
      
        }else{
            //display error to customer
            echo $response->getMessage();
        }
    }catch(Exception $e){
        echo $e->getMessage();
    }
}
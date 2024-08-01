<?php
session_start();

if (isset($_POST['roundedFee'])) {
    $total = 0;
    $roundedFee = $_POST['roundedFee'];
    $_SESSION['deliveryf'] = $roundedFee;

    $total = $_SESSION['subtotal'] + $roundedFee;


    $_SESSION['service_tax'] = $total * 0.06;

    $totals = $_SESSION['service_tax'] + $total;
      // Round the total to the nearest 0.10
    $roundedTotal = round($totals * 10) / 10;
    $_SESSION['total'] = $roundedTotal;

    
    // Output the combined total
    echo $_SESSION['total']; // Return any data you want to send back to JavaScript
    exit; // Terminate PHP script
}
?>

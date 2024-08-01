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
if (!isset($_SESSION['original_total'])) {
    $_SESSION['original_total'] = $_SESSION['total'];
}

if (isset($_POST['selectedDiscount'])) {
    $selectedDiscount = $_POST['selectedDiscount'];

    // Reset the total to the original total before applying discount
    $discountedTotal = $_SESSION['original_total'];

    if ($selectedDiscount === "nodis") {
        // No discount applied
        $discountedTotal = $_SESSION['original_total'];
    } else if ($selectedDiscount === "dis") {
        $discountPoint = $_SESSION['login_discount_point'];
        $discountedTotal -= $discountPoint;
        
        if ($discountedTotal < 0) {
            $discountedTotal = 0;
        }

        $discountedTotal = round($discountedTotal, 2);
    }

    $_SESSION['total'] = $discountedTotal;
    echo $discountedTotal;
    exit;
}
?>

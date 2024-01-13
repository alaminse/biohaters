<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $coupon_code = $_POST["coupon_code"];
        $originalPrice = 100; // Replace this with your actual price
        $discountedPrice = $originalPrice * 0.9;
        $_SESSION["discounted_price"] = $discountedPrice;

        // Send the discounted price as a response
        echo $discountedPrice;
    }
?>

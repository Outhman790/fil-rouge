<?php
session_start();
require_once "StripeHelper.class.php";
require_once "payments.class.php";
// Dynamically determine base URL
if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
    $appUrl = "http://localhost/sandik/";
} else {
    $appUrl = "http://35.180.140.169/";
}
$productPrice = 300;
$stripeHelper = new StripeHelper();
$stripe = $stripeHelper->stripeClient;
// Get product
$product = $stripeHelper->getProduct('price_1NI5P8JsAbiLXvIKOSOZxqhb');

$stripeSession = $stripe->checkout->sessions->create(
    array(
        'success_url' => $appUrl . 'success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $appUrl . '.failed.php',
        'payment_method_types' => array('card'),
        'mode' => 'payment',
        'line_items' => array(
            array(
                'price' => $stripeHelper->getPrice('price_1NI5P8JsAbiLXvIKOSOZxqhb'),
                'quantity' => 1,
            )
        )
    )
);
header("Location: " . $stripeSession->url);

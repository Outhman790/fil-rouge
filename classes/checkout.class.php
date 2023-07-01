<?php
session_start();
require_once "StripeHelper.class.php";
require_once "payments.class.php";
// APP url/base url
$appUrl = "http://localhost/sandik%20pfe/";
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

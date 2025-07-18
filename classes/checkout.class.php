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

// Get payment details from form
$payAll = isset($_POST['pay_all']) && $_POST['pay_all'] == '1';
$amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 300;
$description = isset($_POST['description']) ? $_POST['description'] : '1 month';
$unpaidMonths = isset($_POST['unpaid_months']) ? json_decode($_POST['unpaid_months'], true) : [];

// Store payment info in session for success page
$_SESSION['payment_info'] = [
    'pay_all' => $payAll,
    'amount' => $amount,
    'description' => $description,
    'unpaid_months' => $unpaidMonths
];

$stripeHelper = new StripeHelper();
$stripe = $stripeHelper->stripeClient;

// Create dynamic payment session
$stripeSession = $stripe->checkout->sessions->create(
    array(
        'success_url' => $appUrl . 'success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $appUrl . 'pay.php?error=cancelled',
        'payment_method_types' => array('card'),
        'mode' => 'payment',
        'line_items' => array(
            array(
                'price_data' => array(
                    'currency' => 'mad',
                    'product_data' => array(
                        'name' => 'Building Fee Payment',
                        'description' => 'Payment for ' . $description,
                    ),
                    'unit_amount' => $amount * 100, // Stripe expects amount in cents
                ),
                'quantity' => 1,
            )
        ),
        'metadata' => array(
            'resident_id' => $_SESSION['resident_id'],
            'pay_all' => $payAll ? 'true' : 'false',
            'months_count' => $payAll ? count($unpaidMonths) : 1,
            'description' => $description
        )
    )
);

header("Location: " . $stripeSession->url);

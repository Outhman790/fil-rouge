<?php
// Include the Stripe PHP library
// require_once('vendor/autoload.php');

// // Set your Stripe API keys
// $stripeSecretKey = 'sk_test_51NHQn6JsAbiLXvIKW2PwqeGkLRnKlxrOJvJ6OcLXAwNxB41JJJUDhrMDgI9RpdnK0ecSIXw666K72FEYFYZZ6meU0083s703GX';

// // Set the Stripe API version
// \Stripe\Stripe::setApiKey($stripeSecretKey);

// // Handle the payment processing
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Get the payment details from the client-side
//     $paymentAmount = $_POST['paymentAmount'];
//     $customerName = $_POST['customerName'];
//     $customerEmail = $_POST['customerEmail'];
//     $paymentMethodId = $_POST['paymentMethodId'];

//     try {
//         // Create a customer in Stripe
//         $customer = \Stripe\Customer::create([
//             'name' => $customerName,
//             'email' => $customerEmail,
//             'payment_method' => $paymentMethodId,
//             'invoice_settings' => [
//                 'default_payment_method' => $paymentMethodId,
//             ],
//         ]);

//         // Create a payment intent
//         $paymentIntent = \Stripe\PaymentIntent::create([
//             'amount' => $paymentAmount,
//             'currency' => 'mad',
//             'customer' => $customer->id,
//             'payment_method' => $paymentMethodId,
//             'confirmation_method' => 'manual',
//             'confirm' => true,
//         ]);

//         // Retrieve the client secret
//         $clientSecret = $paymentIntent->client_secret;

//         // Return the client secret to the client-side
//         echo json_encode(['clientSecret' => $clientSecret]);
//     } catch (\Stripe\Exception\CardException $e) {
//         // Handle card errors
//         echo json_encode(['error' => $e->getMessage()]);
//     } catch (\Stripe\Exception\RateLimitException $e) {
//         // Handle rate limit errors
//         echo json_encode(['error' => 'Too many requests. Please try again later.']);
//     } catch (\Stripe\Exception\InvalidRequestException $e) {
//         // Handle invalid request errors
//         echo json_encode(['error' => $e->getMessage()]);
//     } catch (\Stripe\Exception\AuthenticationException $e) {
//         // Handle authentication errors
//         echo json_encode(['error' => 'Authentication failed. Please check your API keys.']);
//     } catch (\Stripe\Exception\ApiConnectionException $e) {
//         // Handle API connection errors
//         echo json_encode(['error' => 'Network connection error. Please try again later.']);
//     } catch (\Stripe\Exception\ApiErrorException $e) {
//         // Handle generic API errors
//         echo json_encode(['error' => 'An error occurred. Please try again later.']);
//     }
// }
require_once('vendor/autoload.php');
\Stripe\Stripe::setApiKey('sk_test_51NHQn6JsAbiLXvIKW2PwqeGkLRnKlxrOJvJ6OcLXAwNxB41JJJUDhrMDgI9RpdnK0ecSIXw666K72FEYFYZZ6meU0083s703GX');

header('Content-Type: application/json');

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => 30000,
        'currency' => 'mad',
    ]);

    echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

<?php
require_once __DIR__ . '/../vendor/autoload.php';
include_once "constants.class.php";
class StripeHelper
{

    public $stripeClient;
    public function __construct()
    {
        $this->stripeClient = new \Stripe\StripeClient(STRIPE_API_SECRET_KEY);
    }

    public function getPrice($priceId)
    {
        try {
            return $this->stripeClient->prices->retrieve($priceId);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle the Stripe API error
            error_log('Stripe API Error: ' . $e->getMessage());
            return null;
        }
    }


    public function getProduct($productId)
    {
        try {
            return $this->stripeClient->products->retrieve($productId);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle the Stripe API error
            error_log('Stripe API Error: ' . $e->getMessage());
            return null;
        }
    }

    public function getSession($sessionId)
    {
        return $this->stripeClient->checkout->sessions->retrieve($sessionId);
    }
}

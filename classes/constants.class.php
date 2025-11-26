<?php

/**
 * Stripe API configuration
 * Load from environment variables
 */

// Load environment variables from .env file if it exists
if (file_exists(__DIR__ . '/../.env')) {
    $envVars = parse_ini_file(__DIR__ . '/../.env');
    foreach ($envVars as $key => $value) {
        if (!getenv($key)) {
            putenv("$key=$value");
        }
    }
}

// Get Stripe API key from environment variable
$stripeSecretKey = getenv('STRIPE_SECRET_KEY') ?: '';

if (empty($stripeSecretKey)) {
    error_log('WARNING: STRIPE_SECRET_KEY not set in environment variables. Please configure .env file.');
}

define('STRIPE_API_SECRET_KEY', $stripeSecretKey);

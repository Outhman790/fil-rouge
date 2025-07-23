<?php

/**
 * Secure Database Configuration
 * 
 * This file loads database configuration from environment variables.
 * Create a .env file in the project root with your credentials.
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

// Environment detection
$environment = getenv('APP_ENV') ?: 'local';

// Get database configuration from environment variables
$dbConfig = [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'dbname' => getenv('DB_NAME') ?: 'sandik',
    'username' => getenv('DB_USERNAME') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: ''
];

// Fallback for production (remove after migration to environment variables)
if ($environment === 'production' && empty($dbConfig['password'])) {
    error_log('WARNING: Using fallback database configuration. Please set environment variables.');
    $dbConfig['username'] = 'outhman790';
    $dbConfig['password'] = 'outhman790..!!';
}

// Define constants for backward compatibility
define('DB_HOST', $dbConfig['host']);
define('DB_NAME', $dbConfig['dbname']);
define('DB_USERNAME', $dbConfig['username']);
define('DB_PASSWORD', $dbConfig['password']);

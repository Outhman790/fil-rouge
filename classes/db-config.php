<?php

/**
 * Database Configuration
 * 
 * This file contains database configuration for different environments.
 * Update the values below according to your environment.
 */

// Environment detection (you can set this via environment variable or manually)
$environment = getenv('APP_ENV') ?: 'production'; // Change to 'local' for local development

// Database configurations for different environments
$configs = [
    'local' => [
        'host' => 'localhost',
        'dbname' => 'sandik',
        'username' => 'root',
        'password' => ''
    ],
    'production' => [
        'host' => 'localhost',
        'dbname' => 'sandik',
        'username' => 'outhman790',
        'password' => 'outhman790..!!'
    ]
];

// Get the current configuration
$currentConfig = $configs[$environment] ?? $configs['production'];

// Define constants for backward compatibility
define('DB_HOST', $currentConfig['host']);
define('DB_NAME', $currentConfig['dbname']);
define('DB_USERNAME', $currentConfig['username']);
define('DB_PASSWORD', $currentConfig['password']);

// Export configuration array
$dbConfig = $currentConfig;

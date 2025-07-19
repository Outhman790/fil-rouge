<?php
// Application Configuration Settings
// This file contains configurable settings for the building management system

return [
    // Payment Settings
    'monthly_fee' => 300, // Monthly payment amount in MAD
    'currency' => 'MAD',
    'currency_symbol' => 'MAD',
    
    // Application Settings
    'app_name' => 'Obuildings',
    'app_description' => 'Building Management System',
    
    // Pagination Settings
    'items_per_page' => 4,
    'announcements_per_page' => 10,
    
    // File Upload Settings
    'max_file_size' => 5242880, // 5MB in bytes
    'allowed_image_types' => ['jpg', 'jpeg', 'png', 'gif'],
    'upload_directory' => 'includes/uploads/',
    
    // Session Settings
    'session_timeout' => 3600, // 1 hour in seconds
    
    // WebSocket Settings
    'websocket_host' => 'localhost',
    'websocket_port' => 8080,
    
    // Error Reporting
    'debug_mode' => false, // Set to false in production
    'log_errors' => true,
    'error_log_file' => 'logs/errors.log'
];
?>
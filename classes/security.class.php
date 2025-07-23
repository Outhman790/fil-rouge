<?php

/**
 * Security Helper Class
 * Handles CSRF protection, input validation, and other security measures
 */
class Security
{
    /**
     * Generate CSRF token and store in session
     */
    public static function generateCSRFToken()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_token_time'] = time();
        return $token;
    }
    
    /**
     * Validate CSRF token
     */
    public static function validateCSRFToken($token)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if token exists in session
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        // Check token expiry (30 minutes)
        if (!isset($_SESSION['csrf_token_time']) || 
            (time() - $_SESSION['csrf_token_time']) > 1800) {
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_time']);
            return false;
        }
        
        // Validate token
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Validate file upload security
     */
    public static function validateFileUpload($file, $allowedTypes = [], $maxSize = 5242880)
    {
        $errors = [];
        
        // Check if file was uploaded
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $errors[] = 'No file uploaded';
            return $errors;
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload error occurred';
            return $errors;
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            $errors[] = 'File size exceeds maximum allowed size';
        }
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!empty($allowedTypes) && !in_array($mimeType, $allowedTypes)) {
            $errors[] = 'File type not allowed';
        }
        
        // Check file extension
        $allowedExtensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf'
        ];
        
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (isset($allowedExtensions[$mimeType]) && 
            $extension !== $allowedExtensions[$mimeType]) {
            $errors[] = 'File extension does not match file type';
        }
        
        return $errors;
    }
    
    /**
     * Generate secure filename
     */
    public static function generateSecureFilename($originalFilename)
    {
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
        $baseName = pathinfo($originalFilename, PATHINFO_FILENAME);
        
        // Sanitize base name
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '', $baseName);
        $baseName = substr($baseName, 0, 50); // Limit length
        
        // Generate unique filename
        $uniqueId = uniqid();
        $timestamp = time();
        
        return $baseName . '_' . $timestamp . '_' . $uniqueId . '.' . $extension;
    }
    
    /**
     * Sanitize input data
     */
    public static function sanitizeInput($input)
    {
        if (is_array($input)) {
            return array_map([self::class, 'sanitizeInput'], $input);
        }
        
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate session security
     */
    public static function validateSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check session timeout (2 hours)
        if (isset($_SESSION['last_activity']) && 
            (time() - $_SESSION['last_activity']) > 7200) {
            session_destroy();
            return false;
        }
        
        // Update last activity time
        $_SESSION['last_activity'] = time();
        
        return true;
    }
    
    /**
     * Regenerate session ID for security
     */
    public static function regenerateSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        session_regenerate_id(true);
    }
}
<?php
require_once '../classes/db.class.php';
require_once '../classes/add-expense.class.php';
require_once '../classes/security.class.php';
session_start();

if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !Security::validateCSRFToken($_POST['csrf_token'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Invalid security token']);
        exit;
    }
    
    // Validate session
    if (!Security::validateSession()) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Session expired']);
        exit;
    }

    // Create an instance of ExpenseManager
    $expenseManager = new ExpenseManager();

    // Sanitize form data
    $name = Security::sanitizeInput($_POST['name']);
    $description = Security::sanitizeInput($_POST['description']);
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);
    $spending_date = Security::sanitizeInput($_POST['spending_date']);

    // Validate required fields
    if (empty($name) || empty($description) || $amount === false || empty($spending_date)) {
        echo json_encode(['success' => false, 'error' => 'Invalid form data']);
        exit;
    }

    $invoice = null;
    // Secure file upload handling
    if (isset($_FILES['invoice']) && $_FILES['invoice']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        $uploadErrors = Security::validateFileUpload($_FILES['invoice'], $allowedTypes, $maxSize);
        
        if (empty($uploadErrors)) {
            $secureFilename = Security::generateSecureFilename($_FILES['invoice']['name']);
            $uploadDirectory = '../uploads/invoices/';
            
            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0755, true);
            }
            
            $destinationPath = $uploadDirectory . $secureFilename;
            
            if (move_uploaded_file($_FILES['invoice']['tmp_name'], $destinationPath)) {
                $invoice = $secureFilename;
            } else {
                echo json_encode(['success' => false, 'error' => 'File upload failed']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => implode(', ', $uploadErrors)]);
            exit;
        }
    }

    $resident_id = $_SESSION['resident_id'];
    
    // Add the expense with sanitized data
    $success = $expenseManager->addExpense(
        $name,
        $description,
        $invoice,
        $amount,
        $spending_date,
        $resident_id
    );

    if ($success) {
        // Log security event
        error_log("Expense added by user ID: {$resident_id}, Amount: {$amount}");
        echo json_encode(['success' => true, 'message' => 'Expense added successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add expense']);
    }
} else {
    header('location: ../index.php');
}
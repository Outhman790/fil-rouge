<?php
session_start();
require_once '../classes/admin.class.php';

// Check if user is admin
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'Admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Check if resident_id is provided
if (!isset($_GET['resident_id']) || empty($_GET['resident_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Resident ID is required']);
    exit();
}

try {
    $adminObj = new Admin();
    $resident = $adminObj->selectResident($_GET['resident_id']);
    
    if ($resident) {
        // Return resident data as JSON
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => [
                'resident_id' => $resident['resident_id'],
                'fName' => $resident['fName'],
                'lName' => $resident['lName'],
                'email' => $resident['email'],
                'username' => $resident['username'],
                'status' => $resident['status']
                // Note: We don't return the password for security reasons
            ]
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Resident not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
}
?>
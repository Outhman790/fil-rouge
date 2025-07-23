<?php
session_start();
require_once('../classes/admin.class.php');
if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {
    $currentYear = intval(date("Y"));
    $currentMonth = (int)date('n');
    $admin = new Admin();

    // Optimized: Get all monthly totals in a single database query
    $allMonthlyTotals = $admin->getAllMonthlyPaymentTotals($currentYear, $currentMonth);
    
    // Format the result to maintain backward compatibility
    $totalPayments = [];
    for ($i = $currentMonth; $i >= 1; $i--) {
        $totalPayments[$i] = $allMonthlyTotals[$i];
    }
    header('Content-Type: application/json');

    // Return the total payments array as JSON
    echo json_encode($totalPayments);
} else {
    header('location: ../index.php');
}

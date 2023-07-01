<?php
session_start();
require_once('../classes/admin.class.php');
if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {
    $currentYear = intval(date("Y"));
    $currentMonth = (int)date('n');
    $admin = new Admin();

    $totalPayments = [];
    // Calculating total amount of payments for each month before the current month
    for ($i = $currentMonth; $i >= 1; $i--) {
        $totalPayment = $admin->getTotalPaimentsOfMonthAndYear($i, $currentYear);
        $totalPayments[$i] = $totalPayment;
    }
    header('Content-Type: application/json');

    // Return the total payments array as JSON
    echo json_encode($totalPayments);
} else {
    header('location: ../index.php');
}

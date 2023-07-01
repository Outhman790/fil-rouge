<?php
session_start();
require_once('../classes/admin.class.php');
if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {

    $admin = new Admin();
    $amounts = $admin->getResidentAmount();

    // Set the response content type to JSON
    header('Content-Type: application/json');

    // Output the JSON data
    echo $amounts;
} else {
    header('location: ../index.php');
}

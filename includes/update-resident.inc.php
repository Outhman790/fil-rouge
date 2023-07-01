<?php
session_start();
require_once '../classes/residents-CRUD-class.php';

if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $residentId = $_POST['resident_id'];
        $fName = $_POST['fName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $username = $_POST['username'];

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Continue with the update operation
        $updater = new Resident();
        $success = $updater->updateResident($residentId, $fName, $lName, $email, $hashedPassword, $username);
        echo $success;

        if ($success) {
            header('location: ../index.php?update-resident=success');
        } else {
            header('location: ../index.php?update-resident=error');
        }
    }
} else {
    header('location: ../index.php');
}

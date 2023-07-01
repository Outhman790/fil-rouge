<?php
session_start();

require_once '../classes/residents-CRUD-class.php';
if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {
    $resident = new Resident();
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    $success = $resident->addResident($fName, $lName, $email, $password, $username);
    $response = ['success' => $success];
    echo json_encode($response);
} else {
    header('location: ../index.php');
}

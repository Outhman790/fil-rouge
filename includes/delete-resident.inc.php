<?php
session_start();
require_once '../classes/residents-CRUD-class.php';
if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {
    $resident_id = $_GET['id'];
    $resident = new Resident();
    $success = $resident->deleteResident($resident_id);
    if ($success) {
        header('location: ../index.php?delete-resident=success');
    } else {
        header('location: ../index.php?delete-resident=error');
    }
} else {
    header('location: ../index.php');
}

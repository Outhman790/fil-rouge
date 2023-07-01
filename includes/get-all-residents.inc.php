<?php
// Include the necessary files and classes
require_once './classes/get-all-residents.class.php';
if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {

    // Create an instance of the Residents class
    $residentsObj = new Residents();

    // Call the getAllResidents() method to get all residents
    $residents = $residentsObj->getAllResidents();
} else {
    header('location: ../index.php');
}

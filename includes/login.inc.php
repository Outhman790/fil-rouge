<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['login'])) {
    // Getting the data
    $email = $_POST['login-email'];
    $password = $_POST['login-password'];


    // Instantiate signup-controller class
    include('../classes/db.class.php');
    include('../classes/login.class.php');
    include('../classes/loginContr.class.php');
    $login = new LoginController($email, $password);

    // running error handler and user login
    $login->loginUser();
    
    // The redirect is handled in the login class based on user status
} else {
    header('location: ../index.php');
}

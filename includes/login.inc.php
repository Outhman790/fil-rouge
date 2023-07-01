<?php
if (isset($_POST['login'])) {
    // Getting the data
    $email = $_POST['login-email'];
    $password = $_POST['login-password'];


    // Instantiate signup-controller class
    include('../classes/db.class.php');
    include('../classes/login.class.php');
    include('../classes/logincontr.class.php');
    $login = new LoginController($email, $password);

    // running error handler and user signup
    $login->loginUser();

    // Going back to Admin dashboard page
    session_start();
    if ($_SESSION['email']) {
        header('location: ../index.php');
    }
} else {
    header('location: ../index.php');
}

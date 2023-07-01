<?php
require_once '../classes/db.class.php';
require_once '../classes/add-expense.class.php';
session_start();
if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {
    // Create an instance of ExpenseManager
    $expenseManager = new ExpenseManager();

    // Get the form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $invoice = $_FILES['invoice']['name'];
    $amount = $_POST['amount'];
    $spending_date = $_POST['spending_date'];

    // Check if the file was uploaded successfully
    if (isset($_FILES['invoice']) && $_FILES['invoice']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['invoice']['tmp_name'];
        $fileName = $_FILES['invoice']['name'];

        $uploadDirectory = 'uploads/';
        $destinationPath = $uploadDirectory . $fileName;

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        move_uploaded_file($tmpName, $destinationPath);
    }

    $resident_id = $_SESSION['resident_id'];
    // Add the expense
    $success = $expenseManager->addExpense(
        $name,
        $description,
        $invoice,
        $amount,
        $spending_date,
        $resident_id
    );

    $response = ['success' => $success];
    // send the response
    echo json_encode($response);
} else {
    header('location: ../index.php');
}
<?php
session_start();
require_once '../classes/admin.class.php';
if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {
    if (isset($_POST['expense_id'])) {
        $expense_id = $_POST['expense_id'];
        $admin = new Admin();
        $success = $admin->deleteExpense($expense_id);
        if ($success) {
            header('Location: ../index.php?delete-expense=success');
            exit();
        } else {
            header('Location: ../index.php?delete-expense=error');
            exit();
        }
    } else {
        header('Location: ../index.php?delete-expense=error');
        exit();
    }
} else {
    header('Location: ../index.php');
    exit();
}

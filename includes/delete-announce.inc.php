<?php
session_start();
require_once '../classes/admin.class.php';
if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {
    if (isset($_POST['announce_id'])) {
        $announce_id = $_POST['announce_id'];
        $admin = new Admin();
        $success = $admin->deleteAnnouncement($announce_id);
        if ($success) {
            header('Location: ../index.php?delete-announce=success');
            exit();
        } else {
            header('Location: ../index.php?delete-announce=error');
            exit();
        }
    } else {
        header('Location: ../index.php?delete-announce=error');
        exit();
    }
} else {
    header('Location: ../index.php');
    exit();
}

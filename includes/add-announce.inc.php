<?php
// Include the necessary files and initialize the database connection
session_start();

require_once '../classes/admin.class.php'; // Assuming you have an Admin class defined
if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the form inputs
        $title = $_POST['title'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name']; // Assuming you want to store the filename

        // Create an instance of the Admin class
        $admin = new Admin();

        // Call the addAnnouncement method and check if it's successful
        if ($admin->addAnnouncement($title, $description, $image)) {
            // Announcement added successfully
            echo "Announcement added successfully.";

            // Move the uploaded image to the "uploads" folder
            $targetDirectory = '../uploads/announces/';
            $targetFilePath = $targetDirectory . basename($image);

            if (!is_dir($targetDirectory)) {
                mkdir($targetDirectory, 0755, true);
            }

            move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath);
            header('location: ../index.php');
        } else {
            header('location: ../index.php');
        }
    }
} else {
    header('location: ../index.php');
}

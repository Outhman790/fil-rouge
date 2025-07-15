<?php
// change-all-passwords.php
// Standalone script to update all user passwords except admin

require_once __DIR__ . '/classes/db-config.php';

try {
    // Connect to the database
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Set the new password here
    $newPasswordPlain = 'Password123!';
    $newPasswordHash = password_hash($newPasswordPlain, PASSWORD_DEFAULT);

    // Select all users except those with status 'Admin' or username 'admin'
    $stmt = $pdo->prepare("SELECT resident_id, username, status FROM residents WHERE status == 'Admin'");
    $stmt->execute();
    $users = $stmt->fetchAll();

    $updated = 0;
    foreach ($users as $user) {
        $updateStmt = $pdo->prepare("UPDATE residents SET password = :password WHERE resident_id = :resident_id");
        $updateStmt->bindParam(':password', $newPasswordHash);
        $updateStmt->bindParam(':resident_id', $user['resident_id']);
        $updateStmt->execute();
        $updated++;
    }

    echo "Passwords updated for $updated users.\n";
    if ($updated > 0) {
        echo "New password for all affected users: $newPasswordPlain\n";
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

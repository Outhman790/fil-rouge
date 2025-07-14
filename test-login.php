<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Step 1: Basic PHP test<br>";

// Test 1: Check if POST data exists
if (isset($_POST['login'])) {
    echo "Step 2: POST login data exists<br>";

    // Test 2: Get form data
    $email = $_POST['login-email'] ?? 'test@test.com';
    $password = $_POST['login-password'] ?? 'test123';

    echo "Step 3: Form data received - Email: $email<br>";

    // Test 3: Check if files exist
    echo "Step 4: Checking required files<br>";

    $files = [
        '../classes/db.class.php',
        '../classes/login.class.php',
        '../classes/logincontr.class.php'
    ];

    foreach ($files as $file) {
        if (file_exists($file)) {
            echo "✅ $file exists<br>";
        } else {
            echo "❌ $file missing<br>";
        }
    }

    // Test 4: Try to include files
    echo "Step 5: Trying to include files<br>";

    try {
        include('../classes/db.class.php');
        echo "✅ db.class.php included successfully<br>";
    } catch (Exception $e) {
        echo "❌ Error including db.class.php: " . $e->getMessage() . "<br>";
    }

    try {
        include('../classes/login.class.php');
        echo "✅ login.class.php included successfully<br>";
    } catch (Exception $e) {
        echo "❌ Error including login.class.php: " . $e->getMessage() . "<br>";
    }

    try {
        include('../classes/logincontr.class.php');
        echo "✅ logincontr.class.php included successfully<br>";
    } catch (Exception $e) {
        echo "❌ Error including logincontr.class.php: " . $e->getMessage() . "<br>";
    }

    // Test 5: Try database connection
    echo "Step 6: Testing database connection<br>";

    try {
        $db = new DB();
        echo "✅ DB class instantiated<br>";

        $conn = $db->connect();
        echo "✅ Database connection successful<br>";

        // Test a simple query
        $stmt = $conn->prepare('SELECT COUNT(*) as count FROM residents');
        $stmt->execute();
        $result = $stmt->fetch();
        echo "✅ Database query successful - Found " . $result['count'] . " residents<br>";
    } catch (Exception $e) {
        echo "❌ Database error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "Step 2: No POST login data - this is normal for direct access<br>";
    echo "<br>To test this properly, submit the login form from the main page.<br>";
}

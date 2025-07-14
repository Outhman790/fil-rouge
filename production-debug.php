<?php

/**
 * Production Debug Script for EC2
 * This script helps identify the cause of the 500 error on production
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "🔍 Production Debug Information\n";
echo "===============================\n\n";

// Test 1: Basic PHP functionality
echo "✅ PHP is working - Version: " . PHP_VERSION . "\n\n";

// Test 2: Check if required files exist
$requiredFiles = [
    'classes/db.class.php',
    'classes/login.class.php',
    'classes/logincontr.class.php'
];

echo "📁 Checking required files:\n";
foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "   ✅ $file exists\n";
    } else {
        echo "   ❌ $file missing\n";
    }
}
echo "\n";

// Test 3: Database connection test
echo "🗄️ Testing database connection:\n";
try {
    // Try to include the DB class
    require_once 'classes/db.class.php';
    echo "   ✅ DB class loaded successfully\n";

    $db = new DB();
    echo "   ✅ DB class instantiated\n";

    $conn = $db->connect();
    echo "   ✅ Database connection successful\n";

    // Test a simple query
    $stmt = $conn->prepare('SELECT COUNT(*) as count FROM residents');
    $stmt->execute();
    $result = $stmt->fetch();
    echo "   ✅ Query test successful - Found " . $result['count'] . " residents\n\n";
} catch (Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n\n";

    echo "🔧 Database Configuration Fix:\n";
    echo "==============================\n";
    echo "The database connection is failing. You need to update the database credentials\n";
    echo "in classes/db.class.php for your production environment.\n\n";

    echo "📝 Current configuration:\n";
    echo "   Host: localhost\n";
    echo "   Database: sandik\n";
    echo "   Username: outhman790\n";
    echo "   Password: outhman790..!!\n\n";

    echo "🔄 Update classes/db.class.php with your production database credentials:\n";
    echo "   protected \$host = 'your-production-host';\n";
    echo "   protected \$dbname = 'your-production-database';\n";
    echo "   protected \$username = 'your-production-username';\n";
    echo "   protected \$password = 'your-production-password';\n\n";
}

// Test 4: Session functionality
echo "🔐 Testing session functionality:\n";
try {
    session_start();
    echo "   ✅ Sessions working\n";
    $_SESSION['test'] = 'test_value';
    echo "   ✅ Session write working\n";
    session_destroy();
    echo "   ✅ Session destroy working\n\n";
} catch (Exception $e) {
    echo "   ❌ Session error: " . $e->getMessage() . "\n\n";
}

// Test 5: File permissions
echo "📂 Checking file permissions:\n";
$directories = ['classes', 'includes'];
foreach ($directories as $dir) {
    if (is_readable($dir)) {
        echo "   ✅ $dir is readable\n";
    } else {
        echo "   ❌ $dir is not readable\n";
    }
}
echo "\n";

echo "🔧 Common Production Issues & Solutions:\n";
echo "========================================\n";
echo "1. Database credentials mismatch - Update db.class.php\n";
echo "2. Missing PHP extensions - Install required extensions\n";
echo "3. File permissions - Ensure web server can read files\n";
echo "4. PHP version compatibility - Check PHP version\n";
echo "5. Missing .htaccess or rewrite rules\n\n";

echo "📋 Next Steps:\n";
echo "==============\n";
echo "1. Check your EC2 server's error logs\n";
echo "2. Update database credentials in classes/db.class.php\n";
echo "3. Ensure all required files are uploaded to production\n";
echo "4. Check file permissions on the server\n";
echo "5. Verify PHP extensions are installed\n";

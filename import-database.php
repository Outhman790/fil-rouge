<?php

/**
 * Database Import Script
 * This script imports the database schema directly using PHP
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once 'classes/db.class.php';

echo "🗄️ Database Import Script\n";
echo "=========================\n\n";

try {
    $db = new DB();
    $conn = $db->connect();
    echo "✅ Database connection successful\n\n";

    // Read the SQL file
    $sqlFile = 'db/sandik.sql';

    if (!file_exists($sqlFile)) {
        echo "❌ SQL file not found: $sqlFile\n";
        echo "   Make sure the db/sandik.sql file exists.\n";
        exit;
    }

    echo "📁 Reading SQL file: $sqlFile\n";
    $sql = file_get_contents($sqlFile);

    if (empty($sql)) {
        echo "❌ SQL file is empty\n";
        exit;
    }

    echo "✅ SQL file loaded (" . strlen($sql) . " characters)\n\n";

    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    echo "🔧 Executing SQL statements...\n";
    echo "==============================\n";

    $successCount = 0;
    $errorCount = 0;

    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue; // Skip comments and empty lines
        }

        try {
            $stmt = $conn->prepare($statement);
            $stmt->execute();
            $successCount++;

            // Show progress for table creation
            if (preg_match('/CREATE TABLE.*`(\w+)`/i', $statement, $matches)) {
                echo "✅ Created table: " . $matches[1] . "\n";
            }
        } catch (Exception $e) {
            $errorCount++;
            echo "❌ Error executing statement: " . substr($statement, 0, 50) . "...\n";
            echo "   Error: " . $e->getMessage() . "\n";
        }
    }

    echo "\n📊 Import Summary:\n";
    echo "==================\n";
    echo "✅ Successful statements: $successCount\n";
    echo "❌ Failed statements: $errorCount\n";

    if ($errorCount > 0) {
        echo "\n⚠️ Some statements failed. Check the errors above.\n";
    } else {
        echo "\n🎉 Database import completed successfully!\n";
    }

    // Verify tables were created
    echo "\n🔍 Verifying tables:\n";
    echo "===================\n";

    $stmt = $conn->prepare("SHOW TABLES");
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $requiredTables = ['residents', 'purchases', 'payments', 'announcements'];

    foreach ($requiredTables as $table) {
        if (in_array($table, $tables)) {
            echo "✅ $table exists\n";
        } else {
            echo "❌ $table missing\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n📋 Manual Import Alternative:\n";
echo "=============================\n";
echo "If this script doesn't work, try importing manually:\n";
echo "1. SSH into your EC2 server\n";
echo "2. Run: mysql -u outhman790 -p sandik < /path/to/sandik.sql\n";
echo "3. Enter your database password when prompted\n";

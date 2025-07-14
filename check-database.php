<?php

/**
 * Database Table Checker
 * This script checks what tables exist in the production database
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once 'classes/db.class.php';

echo "🔍 Database Table Checker\n";
echo "========================\n\n";

try {
    $db = new DB();
    $conn = $db->connect();
    echo "✅ Database connection successful\n\n";

    // Check what tables exist
    echo "📋 Existing Tables:\n";
    echo "==================\n";

    $stmt = $conn->prepare("SHOW TABLES");
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        echo "❌ No tables found in database!\n";
        echo "   The database is empty or the import failed.\n\n";
    } else {
        foreach ($tables as $table) {
            echo "✅ $table\n";
        }
        echo "\n";
    }

    // Check specific required tables
    $requiredTables = ['residents', 'purchases', 'payments', 'announcements', 'comments', 'likes'];

    echo "🔍 Checking Required Tables:\n";
    echo "============================\n";

    foreach ($requiredTables as $table) {
        if (in_array($table, $tables)) {
            echo "✅ $table - EXISTS\n";

            // Count records in each table
            try {
                $countStmt = $conn->prepare("SELECT COUNT(*) as count FROM `$table`");
                $countStmt->execute();
                $count = $countStmt->fetch()['count'];
                echo "   📊 Records: $count\n";
            } catch (Exception $e) {
                echo "   ❌ Error counting records: " . $e->getMessage() . "\n";
            }
        } else {
            echo "❌ $table - MISSING\n";
        }
    }

    echo "\n";

    // Check database name
    $dbNameStmt = $conn->prepare("SELECT DATABASE() as db_name");
    $dbNameStmt->execute();
    $dbName = $dbNameStmt->fetch()['db_name'];
    echo "🗄️ Current Database: $dbName\n";

    // Check if we can create tables
    echo "\n🔧 Testing Table Creation:\n";
    echo "==========================\n";

    try {
        $testStmt = $conn->prepare("CREATE TABLE IF NOT EXISTS test_table (id INT)");
        $testStmt->execute();
        echo "✅ Can create tables\n";

        // Clean up test table
        $dropStmt = $conn->prepare("DROP TABLE test_table");
        $dropStmt->execute();
        echo "✅ Can drop tables\n";
    } catch (Exception $e) {
        echo "❌ Cannot create tables: " . $e->getMessage() . "\n";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n📋 Next Steps:\n";
echo "==============\n";
echo "1. If no tables exist: Import the SQL file again\n";
echo "2. If some tables missing: Check SQL file for errors\n";
echo "3. If permissions issue: Check database user privileges\n";
echo "4. If wrong database: Verify you're importing to the correct database\n";

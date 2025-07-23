<?php
require_once 'vendor/autoload.php';

// Force local environment for WebSocket server
putenv('APP_ENV=local');

require_once 'config/db-config.php';
require_once 'classes/websocket.class.php';

// Debug: Show database configuration
global $dbConfig;
echo "Database Config:\n";
echo "Environment: " . (getenv('APP_ENV') ?: 'production') . "\n";
echo "Host: " . ($dbConfig['host'] ?? 'NOT SET') . "\n";
echo "Database: " . ($dbConfig['dbname'] ?? 'NOT SET') . "\n";
echo "Username: " . ($dbConfig['username'] ?? 'NOT SET') . "\n";
echo "Password: " . (isset($dbConfig['password']) ? '[SET]' : 'NOT SET') . "\n";
echo "------------------------\n";

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

// Create the WebSocket server
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new AnnouncementsWebSocket()
        )
    ),
    8080
);

echo "WebSocket server started on port 8080\n";
echo "Press Ctrl+C to stop the server\n";

$server->run();
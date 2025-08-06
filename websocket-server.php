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
use React\Socket\Server as ReactServer;
use React\EventLoop\Loop;

// Create event loop
$loop = Loop::get();

// Create the WebSocket server components
$webSocketComponent = new AnnouncementsWebSocket();
$webSocketServer = new WsServer($webSocketComponent);
$httpServer = new HttpServer($webSocketServer);

// Create React socket server that explicitly binds to all interfaces
$reactSocket = new ReactServer('0.0.0.0:8080', $loop);
$server = new IoServer($httpServer, $reactSocket, $loop);

echo "WebSocket server started on port 8080\n";
echo "Press Ctrl+C to stop the server\n";

$server->run();

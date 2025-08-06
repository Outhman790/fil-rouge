#!/bin/bash

echo "Starting WebSocket Server..."

# Get the directory where the script is located
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Start the WebSocket server
php websocket-server.php
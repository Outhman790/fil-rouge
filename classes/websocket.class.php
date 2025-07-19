<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

session_start();
require './vendor/autoload.php';

class AnnouncementsWebSocket implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        require_once 'user.class.php';
        // Process incoming messages and broadcast them to all connected clients
        $data = json_decode($msg, true);
        print_r($data);
        // Check if the received message is a comment
        if (isset($data['type']) && $data['type'] === 'comment') {
            $comment = $data['message'];
            $residentId = $data['resident_id'];
            $resident_fullName = $data['resident_fullName'];
            $announcementId = $data['announcement_id'];
            // Add comments to database
            $userObj = new User();
            $addComment = $userObj->addComment($announcementId, $residentId, $comment);
            $createdAt = $addComment['created_at'];
            // Create a new data object with the comment and additional data
            $responseData = [
                'type' => 'comment',
                'message' => $comment,
                'resident_id' => $residentId,
                'resident_fullName' => $resident_fullName,
                'announcement_id' => $announcementId,
                'created_at' => $createdAt
            ];
            // Broadcast the updated data to all connected clients
            foreach ($this->clients as $client) {
                $client->send(json_encode($responseData));
            }
        }
        if (isset($data['type']) && ($data['type'] === 'like')) {
            $announcementId = $data['announcement_id'];
            $residentId = $data['resident_id'];
            $action = $data['action'] ?? 'like'; // 'like' or 'unlike'
            $userObj = new User();

            // Check if the resident has already liked
            $alreadyLiked = $userObj->checkLike($announcementId, $residentId);
            $success = false;
            $isLiked = false;

            if ($action === 'like' && !$alreadyLiked) {
                $success = $userObj->addLike($announcementId, $residentId);
                $isLiked = $success;
            } elseif ($action === 'unlike' && $alreadyLiked) {
                $success = $userObj->removeLike($announcementId, $residentId);
                $isLiked = false;
            } else {
                // No change needed - already in desired state
                $isLiked = $alreadyLiked;
                $success = true;
            }

            // Get updated like count
            $countLikes = $userObj->countLikes($announcementId);

            // Send personalized response to the user who clicked
            $personalResponseData = [
                'type' => 'like',
                'announcement_id' => $announcementId,
                'resident_id' => $residentId,
                'count_likes' => $countLikes['like_count'],
                'is_liked' => $isLiked,
                'success' => $success
            ];

            // Send personalized response to the clicking user
            $from->send(json_encode($personalResponseData));

            // Broadcast general update (count only) to all other clients
            $generalResponseData = [
                'type' => 'like',
                'announcement_id' => $announcementId,
                'count_likes' => $countLikes['like_count']
            ];

            foreach ($this->clients as $client) {
                if ($client !== $from) {
                    $client->send(json_encode($generalResponseData));
                }
            }
        }

        if (isset($data['type']) && ($data['type'] === 'check_like')) {
            $announcementId = $data['announcement_id'];
            $residentId = $data['resident_id'];
            $userObj = new User();

            // Check if the resident has already liked
            $isLiked = $userObj->checkLike($announcementId, $residentId);
            $countLikes = $userObj->countLikes($announcementId);

            // Send like status back to the requesting client
            $responseData = [
                'type' => 'like_status',
                'announcement_id' => $announcementId,
                'resident_id' => $residentId,
                'is_liked' => $isLiked,
                'count_likes' => $countLikes['like_count']
            ];

            // Send only to the requesting client
            $from->send(json_encode($responseData));
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Run the WebSocket server
$server = new \Ratchet\App('localhost', 8080);
$server->route('/', new AnnouncementsWebSocket(), ['*']);
$server->run();

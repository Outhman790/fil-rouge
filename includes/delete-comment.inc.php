<?php
session_start();
require_once '../classes/user.class.php';

header('Content-Type: application/json');

if (!isset($_SESSION['resident_id']) || $_SESSION['status'] !== 'Resident') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$commentId = $data['comment_id'] ?? null;
$announcementId = $data['announcement_id'] ?? null;

if (!$commentId || !$announcementId) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit();
}

$userObj = new User();
$residentId = $_SESSION['resident_id'];

try {
    $result = $userObj->deleteComment($commentId, $residentId);

    if ($result) {
        // Get updated comment count
        $comments = $userObj->showComments($announcementId);

        echo json_encode([
            'success' => true,
            'comment_count' => count($comments)
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete comment or unauthorized']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

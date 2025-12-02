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
$announcementId = $data['announcement_id'] ?? null;
$commentText = $data['message'] ?? '';

if (!$announcementId || empty(trim($commentText))) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit();
}

$userObj = new User();
$residentId = $_SESSION['resident_id'];

try {
    $comment = $userObj->addComment($announcementId, $residentId, trim($commentText));

    if ($comment) {
        // Get comment count
        $comments = $userObj->showComments($announcementId);

        echo json_encode([
            'success' => true,
            'comment_id' => $comment['comment_id'],
            'comment_count' => count($comments),
            'created_at' => $comment['created_at'],
            'resident_fullName' => $_SESSION['fName'] . ' ' . $_SESSION['lName']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add comment']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

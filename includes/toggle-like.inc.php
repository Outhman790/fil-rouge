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
$action = $data['action'] ?? 'like';

if (!$announcementId) {
    echo json_encode(['success' => false, 'error' => 'Missing announcement ID']);
    exit();
}

$userObj = new User();
$residentId = $_SESSION['resident_id'];

try {
    if ($action === 'like') {
        $result = $userObj->addLike($announcementId, $residentId);
    } else {
        $result = $userObj->removeLike($announcementId, $residentId);
    }

    // Get updated like count
    $countLikes = $userObj->countLikes($announcementId);
    $isLiked = $userObj->checkLike($announcementId, $residentId) > 0;

    echo json_encode([
        'success' => true,
        'count_likes' => $countLikes['like_count'],
        'is_liked' => $isLiked
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

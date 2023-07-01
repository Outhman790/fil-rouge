<?php
require('../classes/user.class.php');
// Example comment text
$commentId = $commentManager->addComment($announcementId, $residentId, $commentText);

if ($commentId) {
    echo "Comment inserted successfully. Comment ID: $commentId";
} else {
    header('location: ../index.php');
    echo "Failed to insert comment.";
}

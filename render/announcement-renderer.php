<?php
// Announcement Renderer - Clean and Simple
// Handles rendering of announcement cards and related components

class AnnouncementRenderer
{
    public static function renderAnnouncementCard($announcement, $userObj) {
        $countLikes = $userObj->countLikes($announcement['announcement_id']);
        $allComments = $userObj->showComments($announcement['announcement_id']);
        
        // Check if announcement is recent (within last 7 days)
        $isRecent = (time() - strtotime($announcement['created_at'])) < (7 * 24 * 60 * 60);
?>
        <!-- Modern Announcement Card -->
        <div class="announcement-card-modern" data-announcement-id="<?php echo $announcement['announcement_id']; ?>">
            <!-- Card Header -->
            <div class="card-header-modern">
                <div class="announcement-meta">
                    <div class="announcement-date">
                        <i class="fas fa-calendar-alt"></i>
                        <?php echo date('M j, Y', strtotime($announcement['created_at'])) ?>
                    </div>
                    <div class="announcement-time">
                        <i class="fas fa-clock"></i>
                        <?php echo date('g:i A', strtotime($announcement['created_at'])) ?>
                    </div>
                </div>
                <?php if ($isRecent) : ?>
                <div class="announcement-badge-modern">
                    <i class="fas fa-star"></i>Featured
                </div>
                <?php endif; ?>
            </div>

            <!-- Card Content -->
            <div class="card-content-modern">
                <h3 class="announcement-title-modern">
                    <?php echo htmlspecialchars($announcement['title']) ?>
                </h3>
                <p class="announcement-description">
                    <?php echo nl2br(htmlspecialchars($announcement['description'])) ?>
                </p>
                
                <?php if (!empty($announcement['image'])) : ?>
                <div class="announcement-image-wrapper image-clickable"
                     data-image-src="uploads/announces/<?php echo htmlspecialchars($announcement['image']) ?>"
                     data-image-title="<?php echo htmlspecialchars($announcement['title']) ?>">
                    <img src="uploads/announces/<?php echo htmlspecialchars($announcement['image']) ?>"
                         class="announcement-image-modern"
                         alt="<?php echo htmlspecialchars($announcement['title']) ?>">
                </div>
                <?php endif; ?>
            </div>

            <!-- Card Interactions -->
            <div class="card-interactions-modern">
                <div class="interaction-stats">
                    <button class="interaction-btn like-btn" data-announcement-id="<?php echo $announcement['announcement_id']; ?>">
                        <i class="far fa-heart"></i>
                        <span class="like-count" id="like-count-<?php echo $announcement['announcement_id']; ?>"><?php echo $countLikes['like_count'] ?></span>
                    </button>

                    <div class="interaction-btn comment-info">
                        <i class="fas fa-comment"></i>
                        <span class="comment-count" id="comment-count-<?php echo $announcement['announcement_id']; ?>"><?php echo count($allComments) ?></span>
                    </div>
                </div>
            </div>

            <!-- Comments Section (Hidden, shown in modal) -->
            <div class="comments-section-modern">
                <?php if (!empty($allComments)) : ?>
                    <div class="comments-header">
                        <i class="fas fa-comments"></i> Comments (<?php echo count($allComments) ?>)
                    </div>
                    <div class="comments-list-modern" id="comments-list-<?php echo $announcement['announcement_id']; ?>">
                        <?php foreach ($allComments as $comment) : ?>
                            <?php self::renderCommentItem($comment); ?>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="no-comments-state">
                        <i class="fas fa-comment-slash"></i>
                        <p>No comments yet. Be the first to share your thoughts!</p>
                    </div>
                    <div class="comments-list-modern" id="comments-list-<?php echo $announcement['announcement_id']; ?>"></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Announcement Details Modal -->
        <div class="modal fade" id="announcementModal-<?php echo $announcement['announcement_id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content announcement-modal-content">
                    <div class="modal-header announcement-modal-header">
                        <h5 class="modal-title announcement-modal-title">
                            <i class="fas fa-bullhorn me-2"></i>
                            <?php echo htmlspecialchars($announcement['title']) ?>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body announcement-modal-body p-0">
                        <!-- Image -->
                        <?php if (!empty($announcement['image'])) : ?>
                        <div class="announcement-modal-image-wrapper">
                            <img src="uploads/announces/<?php echo htmlspecialchars($announcement['image']) ?>"
                                 class="announcement-modal-image"
                                 alt="<?php echo htmlspecialchars($announcement['title']) ?>">
                        </div>
                        <?php endif; ?>

                        <!-- Description -->
                        <div class="announcement-modal-description">
                            <div class="announcement-modal-meta">
                                <span><i class="fas fa-calendar-alt me-1"></i><?php echo date('M j, Y', strtotime($announcement['created_at'])) ?></span>
                                <span><i class="fas fa-clock me-1"></i><?php echo date('g:i A', strtotime($announcement['created_at'])) ?></span>
                            </div>
                            <p class="announcement-modal-text"><?php echo nl2br(htmlspecialchars($announcement['description'])) ?></p>
                        </div>

                        <!-- Interactions -->
                        <div class="announcement-modal-interactions">
                            <button class="interaction-btn like-btn" data-announcement-id="<?php echo $announcement['announcement_id']; ?>">
                                <i class="far fa-heart"></i>
                                <span class="like-count"><?php echo $countLikes['like_count'] ?></span>
                                <span class="interaction-label">Likes</span>
                            </button>
                            <div class="interaction-divider"></div>
                            <div class="interaction-info">
                                <i class="fas fa-comment"></i>
                                <span class="comment-count"><?php echo count($allComments) ?></span>
                                <span class="interaction-label">Comments</span>
                            </div>
                        </div>

                        <!-- Comment Input -->
                        <div class="announcement-modal-comment-input">
                            <div class="user-avatar-modal">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text"
                                   class="comment-input-modal"
                                   placeholder="Write a comment..."
                                   id="modal-comment-input-<?php echo $announcement['announcement_id']; ?>">
                            <button class="comment-submit-btn-modal"
                                    data-announcement-id="<?php echo $announcement['announcement_id']; ?>">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>

                        <!-- Comments List -->
                        <div class="announcement-modal-comments">
                            <?php if (!empty($allComments)) : ?>
                                <div class="comments-header-modal">
                                    <i class="fas fa-comments"></i> All Comments
                                </div>
                                <div class="comments-list-modal" id="modal-comments-list-<?php echo $announcement['announcement_id']; ?>">
                                    <?php foreach ($allComments as $comment) : ?>
                                        <?php self::renderCommentItem($comment); ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <div class="no-comments-state-modal">
                                    <i class="fas fa-comment-dots"></i>
                                    <p>No comments yet. Start the conversation!</p>
                                </div>
                                <div class="comments-list-modal" id="modal-comments-list-<?php echo $announcement['announcement_id']; ?>"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    private static function renderCommentItem($comment) {
        $isOwnComment = isset($_SESSION['resident_id']) && $_SESSION['resident_id'] == $comment['resident_id'];
?>
        <div class="comment-item-modern" data-comment-id="<?php echo $comment['comment_id']; ?>">
            <div class="comment-avatar-modern">
                <i class="fas fa-user"></i>
            </div>
            <div class="comment-content-modern">
                <div class="comment-header-modern">
                    <h6 class="comment-author-modern">
                        <?php echo htmlspecialchars($comment['fName'] . ' ' . $comment['lName']) ?>
                    </h6>
                    <span class="comment-date-modern">
                        <?php echo date('M j, Y \a\t g:i A', strtotime($comment['created_at'])) ?>
                    </span>
                </div>
                <p class="comment-text-modern">
                    <?php echo nl2br(htmlspecialchars($comment['comment_text'])) ?>
                </p>
            </div>
            <?php if ($isOwnComment) : ?>
                <button class="comment-delete-btn"
                        data-comment-id="<?php echo $comment['comment_id']; ?>"
                        data-announcement-id="<?php echo $comment['announcement_id']; ?>"
                        title="Delete your comment">
                    <i class="fas fa-trash-alt"></i>
                </button>
            <?php endif; ?>
        </div>
<?php
    }

    public static function renderEmptyState() {
?>
        <!-- Empty State -->
        <div class="empty-state-container">
            <div class="empty-state-card">
                <div class="empty-state-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <h3 class="empty-state-title">Welcome to Your Community Hub!</h3>
                <p class="empty-state-subtitle">This is where you'll find all the latest announcements, updates, and important information from your building management. New content will appear here as it becomes available.</p>
                <div class="empty-state-animation">
                    <div class="pulse-circle"></div>
                    <div class="pulse-circle"></div>
                    <div class="pulse-circle"></div>
                </div>
            </div>
        </div>
<?php
    }

    public static function renderImageModal() {
?>
        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 90vw; width: 90vw;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Announcement Image</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img id="modalImage" src="" alt="" class="modal-image">
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Comment Confirmation Modal -->
        <div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title text-danger" id="deleteCommentModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i>Delete Comment
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <p class="mb-0 text-center">Are you sure you want to delete this comment? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteComment">
                            <i class="fas fa-trash-alt me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>
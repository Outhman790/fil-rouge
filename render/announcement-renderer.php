<?php
// Announcement Renderer
// Handles rendering of announcement cards and related components

class AnnouncementRenderer
{
    public static function renderAnnouncementHero($totalAnnouncements) {
?>
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="hero-content">
                            <h1 class="hero-title">
                                <i class="fas fa-bullhorn hero-icon"></i>
                                Community Announcements
                            </h1>
                            <p class="hero-subtitle">Stay connected with your building community</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="hero-stats">
                            <div class="stat-item">
                                <i class="fas fa-newspaper"></i>
                                <div class="stat-info">
                                    <h3><?php echo $totalAnnouncements; ?></h3>
                                    <p>Total Announcements</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                <h3 class="empty-state-title">No Announcements Yet</h3>
                <p class="empty-state-subtitle">Check back later for important updates from building management.</p>
                <div class="empty-state-animation">
                    <div class="pulse-circle"></div>
                    <div class="pulse-circle"></div>
                    <div class="pulse-circle"></div>
                </div>
            </div>
        </div>
<?php
    }

    public static function renderAnnouncementCard($announcement, $userObj) {
        $countLikes = $userObj->countLikes($announcement['announcement_id']);
        $allComments = $userObj->showComments($announcement['announcement_id']);
?>
        <!-- Modern Announcement Card -->
        <div class="announcement-card-modern" data-announcement-id="<?php echo $announcement['announcement_id']; ?>">
            <?php self::renderCardHeader($announcement); ?>
            <?php self::renderCardContent($announcement); ?>
            <?php self::renderCardInteractions($announcement, $countLikes, $allComments); ?>
            <?php self::renderCommentsSection($announcement, $allComments); ?>
        </div>
<?php
    }

    private static function renderCardHeader($announcement) {
?>
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
            <div class="announcement-badge-modern">
                <span class="badge badge-featured">
                    <i class="fas fa-star"></i>Featured
                </span>
            </div>
        </div>
<?php
    }

    private static function renderCardContent($announcement) {
?>
        <!-- Card Content -->
        <div class="card-content-modern">
            <div class="row">
                <div class="col-lg-7">
                    <div class="announcement-text-content">
                        <h2 class="announcement-title-modern">
                            <?php echo htmlspecialchars($announcement['title']) ?>
                        </h2>
                        <div class="announcement-description">
                            <?php echo nl2br(htmlspecialchars($announcement['description'])) ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="announcement-image-wrapper">
                        <img src="includes/uploads/announces/<?php echo $announcement['image'] ?>" 
                             class="announcement-image-modern image-clickable" 
                             alt="<?php echo htmlspecialchars($announcement['title']) ?>"
                             data-image-src="includes/uploads/announces/<?php echo $announcement['image'] ?>"
                             data-image-title="<?php echo htmlspecialchars($announcement['title']) ?>"
                             style="cursor: pointer;">
                        <div class="image-overlay-modern"></div>
                        <div class="image-zoom-icon">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    private static function renderCardInteractions($announcement, $countLikes, $allComments) {
?>
        <!-- Card Interactions -->
        <div class="card-interactions-modern">
            <div class="interaction-stats">
                <div class="stat-item">
                    <button class="interaction-btn like-btn" data-announcement-id="<?php echo $announcement['announcement_id']; ?>">
                        <i class="far fa-heart"></i>
                        <span class="like-count" id="like-count-<?php echo $announcement['announcement_id']; ?>"><?php echo $countLikes['like_count'] ?></span>
                    </button>
                </div>
                <div class="stat-item">
                    <div class="interaction-btn comment-info">
                        <i class="fas fa-comment"></i>
                        <span class="comment-count" id="comment-count-<?php echo $announcement['announcement_id']; ?>"><?php echo count($allComments) ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Comment Input -->
            <div class="comment-input-wrapper">
                <div class="comment-input-group">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <input type="text" 
                           class="comment-input-modern" 
                           placeholder="Share your thoughts about this announcement..."
                           id="comment-input-<?php echo $announcement['announcement_id']; ?>">
                    <button class="comment-submit-btn" 
                            data-announcement-id="<?php echo $announcement['announcement_id']; ?>">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
<?php
    }

    private static function renderCommentsSection($announcement, $allComments) {
?>
        <!-- Comments Section -->
        <div class="comments-section-modern">
            <?php if (!empty($allComments)) : ?>
                <div class="comments-header">
                    <h6><i class="fas fa-comments"></i> Comments (<?php echo count($allComments) ?>)</h6>
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
            <?php endif; ?>
        </div>
<?php
    }

    private static function renderCommentItem($comment) {
?>
        <div class="comment-item-modern">
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
        </div>
<?php
    }

    public static function renderImageModal() {
?>
        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 90vw; width: 90vw;">
                <div class="modal-content image-modal-content">
                    <div class="modal-header image-modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Announcement Image</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body image-modal-body">
                        <img id="modalImage" src="" alt="" class="img-fluid modal-image">
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>
<?php
session_start();


if (isset($_SESSION['resident_id'])) :
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Obuildings</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="css/user-dashboard.css">
</head>

<body class="user-dashboard">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-modern">
        <div class="container">
            <a class="navbar-brand" href="homepage.php">
                <i class="fas fa-building mr-2"></i>Obuildings
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="homepage.php">
                            <i class="fas fa-home mr-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="announces.php">
                            <i class="fas fa-bullhorn mr-1"></i>Announces <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="payments.php">
                            <i class="fas fa-credit-card mr-1"></i>Payments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
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
                                <h3><?php echo count($allAnnouncements ?? []); ?></h3>
                                <p>Total Announcements</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="announcements-wrapper">
            <?php
                require_once 'classes/admin.class.php';
                require_once 'classes/user.class.php';
                $adminObj = new Admin();
                $allAnnouncements = $adminObj->getAllAnnouncements();
                $userObj = new User();
                
                if (empty($allAnnouncements)) :
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
                else :
                    foreach ($allAnnouncements as $key => $announce) :
                        $countLikes = $userObj->countLikes($announce['announcement_id']);
                        $allComments = $userObj->showComments($announce['announcement_id']);
            ?>
                <!-- Modern Announcement Card -->
                <div class="announcement-card-modern" data-announcement-id="<?php echo $announce['announcement_id']; ?>">
                    <!-- Card Header -->
                    <div class="card-header-modern">
                        <div class="announcement-meta">
                            <div class="announcement-date">
                                <i class="fas fa-calendar-alt"></i>
                                <?php echo date('M j, Y', strtotime($announce['created_at'])) ?>
                            </div>
                            <div class="announcement-time">
                                <i class="fas fa-clock"></i>
                                <?php echo date('g:i A', strtotime($announce['created_at'])) ?>
                            </div>
                        </div>
                        <div class="announcement-badge-modern">
                            <span class="badge badge-featured">
                                <i class="fas fa-star"></i>Featured
                            </span>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="card-content-modern">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="announcement-text-content">
                                    <h2 class="announcement-title-modern">
                                        <?php echo htmlspecialchars($announce['title']) ?>
                                    </h2>
                                    <div class="announcement-description">
                                        <?php echo nl2br(htmlspecialchars($announce['description'])) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="announcement-image-wrapper">
                                    <img src="includes/uploads/announces/<?php echo $announce['image'] ?>" 
                                         class="announcement-image-modern image-clickable" 
                                         alt="<?php echo htmlspecialchars($announce['title']) ?>"
                                         data-image-src="includes/uploads/announces/<?php echo $announce['image'] ?>"
                                         data-image-title="<?php echo htmlspecialchars($announce['title']) ?>"
                                         style="cursor: pointer;">
                                    <div class="image-overlay-modern"></div>
                                    <div class="image-zoom-icon">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Interactions -->
                    <div class="card-interactions-modern">
                        <div class="interaction-stats">
                            <div class="stat-item">
                                <button class="interaction-btn like-btn" data-announcement-id="<?php echo $announce['announcement_id']; ?>">
                                    <i class="far fa-heart"></i>
                                    <span class="like-count" id="like-count-<?php echo $announce['announcement_id']; ?>"><?php echo $countLikes['like_count'] ?></span>
                                </button>
                            </div>
                            <div class="stat-item">
                                <div class="interaction-btn comment-info">
                                    <i class="fas fa-comment"></i>
                                    <span class="comment-count" id="comment-count-<?php echo $announce['announcement_id']; ?>"><?php echo count($allComments) ?></span>
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
                                       id="comment-input-<?php echo $announce['announcement_id']; ?>">
                                <button class="comment-submit-btn" 
                                        data-announcement-id="<?php echo $announce['announcement_id']; ?>">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comments Section -->
                    <div class="comments-section-modern">
                        <?php if (!empty($allComments)) : ?>
                            <div class="comments-header">
                                <h6><i class="fas fa-comments"></i> Comments (<?php echo count($allComments) ?>)</h6>
                            </div>
                            <div class="comments-list-modern" id="comments-list-<?php echo $announce['announcement_id']; ?>">
                                <?php foreach ($allComments as $comment) : ?>
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
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div class="no-comments-state">
                                <i class="fas fa-comment-slash"></i>
                                <p>No comments yet. Be the first to share your thoughts!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php 
                    endforeach;
                endif;
            ?>
        </div>
    </div>

    <!-- WebSocket connection -->
    <script>
    // Establish WebSocket connection
    const socket = new WebSocket('ws://localhost:8080');

    // WebSocket event handlers
    socket.onopen = function(event) {
        console.log('WebSocket connection established.');
        // Initialize like button states after connection is established
        setTimeout(() => {
            initializeLikeStates();
        }, 1000);
    };

    socket.onmessage = function(event) {
        // Handle incoming messages from the server
        console.log('Received message:', event.data);

        try {
            const data = JSON.parse(event.data);
            console.log('Parsed data:', data);
            
            if (data.type === 'comment') {
                addCommentToDOM(data);
            } else if (data.type === 'like') {
                console.log('Received like update:', data);
                updateLikeCount(data);
            } else if (data.type === 'like_status') {
                console.log('Received like status:', data);
                updateLikeCount(data); // Use same function for consistency
            } else {
                console.log('Unknown message type:', data.type);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
        }
    };

    // Function to add comment to DOM
    function addCommentToDOM(data) {
        const announcementId = data.announcement_id;
        const comment = data.message;
        const residentFullName = data.resident_fullName;
        const createdAt = data.created_at;

        // Find the comments list for this specific announcement
        const commentsList = document.getElementById(`comments-list-${announcementId}`);
        
        if (!commentsList) {
            console.error('Comments list not found for announcement:', announcementId);
            return;
        }

        // Create new comment element
        const commentElement = document.createElement('div');
        commentElement.className = 'comment-item-modern new-comment';
        commentElement.innerHTML = `
            <div class="comment-avatar-modern">
                <i class="fas fa-user"></i>
            </div>
            <div class="comment-content-modern">
                <div class="comment-header-modern">
                    <h6 class="comment-author-modern">
                        ${residentFullName}
                    </h6>
                    <span class="comment-date-modern">
                        ${formatDate(createdAt)}
                    </span>
                </div>
                <p class="comment-text-modern">
                    ${comment}
                </p>
            </div>
        `;

        // Add to comments list
        commentsList.appendChild(commentElement);

        // Update comment count with actual count from server
        const commentCountElement = document.getElementById(`comment-count-${announcementId}`);
        if (commentCountElement) {
            // Use server-provided count if available, otherwise increment
            const newCount = data.total_comments || (parseInt(commentCountElement.textContent) + 1);
            commentCountElement.textContent = newCount;
        }

        // Hide "No comments" message if this is the first comment
        const noCommentsMessage = commentsList.parentElement.querySelector('.no-comments-state');
        if (noCommentsMessage) {
            noCommentsMessage.style.display = 'none';
        }

        // Update comments header count
        const commentsHeader = commentsList.parentElement.querySelector('.comments-header h6');
        if (commentsHeader) {
            const actualCount = commentsList.children.length;
            commentsHeader.innerHTML = `<i class="fas fa-comments"></i> Comments (${actualCount})`;
        }

        // Trigger pulse animation on announcement card
        triggerCardAnimation(announcementId);

        // Scroll to new comment
        commentElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Function to update like count
    function updateLikeCount(data) {
        const announcementId = data.announcement_id;
        const countLikes = data.count_likes;
        const isLiked = data.is_liked;
        const currentUserId = '<?php echo $_SESSION['resident_id']; ?>';
        
        console.log('Updating like count for announcement:', announcementId, 'Count:', countLikes, 'Is liked:', isLiked, 'For user:', data.resident_id);
        
        // Update both count and button state
        const likeButton = document.querySelector(`[data-announcement-id="${announcementId}"].like-btn`);
        if (likeButton) {
            const likeCountSpan = likeButton.querySelector('.like-count');
            if (likeCountSpan) {
                likeCountSpan.textContent = countLikes;
            }
            
            // Only update button state if this is for the current user or if we have explicit like status
            if (isLiked !== undefined && (data.resident_id === currentUserId || data.type === 'like_status')) {
                console.log('Updating button state for current user. Is liked:', isLiked);
                if (isLiked) {
                    likeButton.classList.add('liked');
                    likeButton.innerHTML = '<i class="fas fa-heart"></i> <span class="like-count" id="like-count-' + announcementId + '">' + countLikes + '</span>';
                } else {
                    likeButton.classList.remove('liked');
                    likeButton.innerHTML = '<i class="far fa-heart"></i> <span class="like-count" id="like-count-' + announcementId + '">' + countLikes + '</span>';
                }
            }
        }
        
        // Trigger pulse animation on announcement card
        triggerCardAnimation(announcementId);
    }

    // Function to toggle like button state immediately
    function toggleLikeButtonState(button, isLiked) {
        const announcementId = button.dataset.announcementId;
        const currentCountElement = button.querySelector('.like-count');
        const currentCount = parseInt(currentCountElement.textContent);
        
        // Calculate new count based on toggle action
        const newCount = Math.max(0, isLiked ? currentCount + 1 : currentCount - 1);
        
        console.log('Toggling like state:', isLiked, 'Current count:', currentCount, 'New count:', newCount);
        
        if (isLiked) {
            button.classList.add('liked');
            button.innerHTML = '<i class="fas fa-heart"></i> <span class="like-count" id="like-count-' + announcementId + '">' + newCount + '</span>';
        } else {
            button.classList.remove('liked');
            button.innerHTML = '<i class="far fa-heart"></i> <span class="like-count" id="like-count-' + announcementId + '">' + newCount + '</span>';
        }
    }

    // Function to update like button state
    function updateLikeButtonState(data) {
        const announcementId = data.announcement_id;
        const isLiked = data.is_liked;
        const countLikes = data.count_likes;
        
        const likeButton = document.querySelector(`[data-announcement-id="${announcementId}"].like-btn`);
        if (likeButton) {
            if (isLiked) {
                likeButton.classList.add('liked');
                likeButton.innerHTML = '<i class="fas fa-heart"></i> <span class="like-count" id="like-count-' + announcementId + '">' + countLikes + '</span>';
            } else {
                likeButton.classList.remove('liked');
                likeButton.innerHTML = '<i class="far fa-heart"></i> <span class="like-count" id="like-count-' + announcementId + '">' + countLikes + '</span>';
            }
        }
    }

    // Function to trigger card animation
    function triggerCardAnimation(announcementId) {
        const announcementCard = document.querySelector(`[data-announcement-id="${announcementId}"]`);
        if (announcementCard) {
            announcementCard.classList.add('new-activity');
            setTimeout(() => {
                announcementCard.classList.remove('new-activity');
            }, 600);
        }
    }

    // Function to format date
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit'
        });
    }


    socket.onclose = function(event) {
        console.log('WebSocket connection closed.');
        // Show user-friendly message
        showConnectionStatus('WebSocket connection closed. Real-time features may not work.', 'warning');
    };

    socket.onerror = function(error) {
        console.error('WebSocket error:', error);
        // Show user-friendly message
        showConnectionStatus('WebSocket server is not running. Real-time features are disabled.', 'error');
    };

    // Function to show connection status to user
    function showConnectionStatus(message, type) {
        const statusDiv = document.getElementById('websocket-status') || createStatusDiv();
        statusDiv.textContent = message;
        statusDiv.className = `alert alert-${type === 'error' ? 'danger' : 'warning'} mt-2`;
        statusDiv.style.display = 'block';
        
        // Hide after 5 seconds for non-error messages
        if (type !== 'error') {
            setTimeout(() => {
                statusDiv.style.display = 'none';
            }, 5000);
        }
    }

    // Create status div if it doesn't exist
    function createStatusDiv() {
        const statusDiv = document.createElement('div');
        statusDiv.id = 'websocket-status';
        statusDiv.style.display = 'none';
        document.querySelector('.container').insertBefore(statusDiv, document.querySelector('.container').firstChild);
        return statusDiv;
    }

    // Function to send a message to the server
    function sendMessage(message) {
        if (socket.readyState === WebSocket.OPEN) {
            console.log(message);
            socket.send(message);
        } else {
            console.warn('WebSocket is not connected. Message not sent:', message);
            showConnectionStatus('Cannot send message. WebSocket server is not running.', 'error');
        }
    }

    // Event delegation for multiple announcement cards
    document.addEventListener('click', function(event) {
        // Handle comment button clicks
        if (event.target.classList.contains('comment-submit-btn') || event.target.closest('.comment-submit-btn')) {
            const button = event.target.classList.contains('comment-submit-btn') ? event.target : event.target.closest('.comment-submit-btn');
            const announcementId = button.dataset.announcementId;
            const commentInput = document.getElementById(`comment-input-${announcementId}`);
            const comment = commentInput.value.trim();
            
            if (comment !== '') {
                const residentId = '<?php echo $_SESSION['resident_id']; ?>';
                const residentFullName = '<?php echo $_SESSION['fName'] . ' ' .  $_SESSION['lName'] ?>';
                
                // Send the comment to the server
                sendMessage(JSON.stringify({
                    type: 'comment',
                    message: comment,
                    resident_id: residentId,
                    resident_fullName: residentFullName,
                    announcement_id: announcementId,
                }));

                // Clear the comment input
                commentInput.value = '';
            }
        }
        
        // Handle like button clicks
        if (event.target.classList.contains('like-btn') || event.target.closest('.like-btn')) {
            const button = event.target.classList.contains('like-btn') ? event.target : event.target.closest('.like-btn');
            const announcementId = button.dataset.announcementId;
            const residentId = '<?php echo $_SESSION['resident_id']; ?>';
            
            // Prevent double-clicking
            if (button.dataset.processing === 'true') {
                return;
            }
            button.dataset.processing = 'true';
            
            // Check if currently liked
            const isCurrentlyLiked = button.classList.contains('liked');
            console.log('Like button clicked. Currently liked:', isCurrentlyLiked);
            
            // Send like/unlike request to server
            sendMessage(JSON.stringify({
                type: 'like',
                announcement_id: announcementId,
                resident_id: residentId,
                action: isCurrentlyLiked ? 'unlike' : 'like'
            }));
            
            // Reset processing flag after a short delay
            setTimeout(() => {
                button.dataset.processing = 'false';
                console.log('Like toggle completed for announcement:', announcementId);
            }, 1000);
        }
        
    });

    // Handle Enter key press in comment inputs
    document.addEventListener('keypress', function(event) {
        if (event.target.classList.contains('comment-input-modern') && event.key === 'Enter') {
            const commentInput = event.target;
            const announcementId = commentInput.id.replace('comment-input-', '');
            const comment = commentInput.value.trim();
            
            if (comment !== '') {
                const residentId = '<?php echo $_SESSION['resident_id']; ?>';
                const residentFullName = '<?php echo $_SESSION['fName'] . ' ' .  $_SESSION['lName'] ?>';
                
                // Send the comment to the server
                sendMessage(JSON.stringify({
                    type: 'comment',
                    message: comment,
                    resident_id: residentId,
                    resident_fullName: residentFullName,
                    announcement_id: announcementId,
                }));

                // Clear the comment input
                commentInput.value = '';
            }
        }
    });

    // Image Modal Functionality
    document.addEventListener('click', function(event) {
        console.log('Click detected on:', event.target);
        
        if (event.target.classList.contains('image-clickable')) {
            event.preventDefault();
            console.log('Image clicked!');
            
            const imageSrc = event.target.getAttribute('data-image-src');
            const imageTitle = event.target.getAttribute('data-image-title');
            
            console.log('Image src:', imageSrc);
            console.log('Image title:', imageTitle);
            
            // Update modal content
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalImage').alt = imageTitle;
            document.getElementById('imageModalLabel').textContent = imageTitle;
            
            // Show modal using Bootstrap 4 syntax
            $('#imageModal').modal('show');
        }
    });

    // Initialize like button states on page load
    function initializeLikeStates() {
        const likeButtons = document.querySelectorAll('.like-btn');
        likeButtons.forEach(button => {
            const announcementId = button.dataset.announcementId;
            const residentId = '<?php echo $_SESSION['resident_id']; ?>';
            
            // Check if user already liked this announcement
            sendMessage(JSON.stringify({
                type: 'check_like',
                announcement_id: announcementId,
                resident_id: residentId
            }));
        });
    }

    // Alternative method - direct click handler
    $(document).ready(function() {
        $('.image-clickable').click(function(e) {
            e.preventDefault();
            console.log('Image clicked via jQuery!');
            
            const imageSrc = $(this).attr('data-image-src');
            const imageTitle = $(this).attr('data-image-title');
            
            console.log('jQuery - Image src:', imageSrc);
            console.log('jQuery - Image title:', imageTitle);
            
            // Update modal content
            $('#modalImage').attr('src', imageSrc);
            $('#modalImage').attr('alt', imageTitle);
            $('#imageModalLabel').text(imageTitle);
            
            // Show modal
            $('#imageModal').modal('show');
        });

        // Also handle clicks on image wrapper and overlay
        $('.announcement-image-wrapper').click(function(e) {
            e.preventDefault();
            console.log('Image wrapper clicked!');
            
            const img = $(this).find('.image-clickable');
            const imageSrc = img.attr('data-image-src');
            const imageTitle = img.attr('data-image-title');
            
            console.log('Wrapper - Image src:', imageSrc);
            console.log('Wrapper - Image title:', imageTitle);
            
            // Update modal content
            $('#modalImage').attr('src', imageSrc);
            $('#modalImage').attr('alt', imageTitle);
            $('#imageModalLabel').text(imageTitle);
            
            // Show modal
            $('#imageModal').modal('show');
        });
    });

    // Handle modal close with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            $('#imageModal').modal('hide');
        }
    });

    // Close modal when clicking outside the image
    $('#imageModal').on('click', function(event) {
        if (event.target === this) {
            $(this).modal('hide');
        }
    });
    </script>

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

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </div>
    </div>
</body>

</html>
<?php
else :
    header('location: login.php');
endif;
?>
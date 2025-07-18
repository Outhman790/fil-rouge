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
    <div class="container my-4">
        <div class="glass-container p-4">
            <div class="welcome-text text-center mb-4">
                <h1><i class="fas fa-bullhorn mr-3"></i>Announcements</h1>
                <p class="lead">Stay updated with the latest building news</p>
            </div>
        <?php
            require_once 'classes/admin.class.php';
            require_once 'classes/user.class.php';
            $adminObj = new Admin();
            $allAnnouncements = $adminObj->getAllAnnouncements();
            $userObj = new User();
            
            if (empty($allAnnouncements)) :
        ?>
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-bullhorn fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Announcements Yet</h4>
                    <p class="text-muted">Check back later for important updates from building management.</p>
                </div>
            </div>
        <?php 
            else :
                foreach ($allAnnouncements as $key => $announce) :
                    $countLikes = $userObj->countLikes($announce['announcement_id']);
                    $allComments = $userObj->showComments($announce['announcement_id']);
        ?>
            <!-- Modern Announcement Card -->
            <div class="card announcement-card shadow-lg mb-4" data-announcement-id="<?php echo $announce['announcement_id']; ?>">
                <!-- Card Header -->
                <div class="card-header bg-gradient-primary text-white border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="card-title mb-1 font-weight-bold">
                                <i class="fas fa-bullhorn mr-2"></i><?php echo htmlspecialchars($announce['title']) ?>
                            </h4>
                            <small class="text-white-50">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                <?php echo date('F j, Y \a\t g:i A', strtotime($announce['created_at'])) ?>
                            </small>
                        </div>
                        <div class="announcement-badge">
                            <span class="badge badge-light">
                                <i class="fas fa-eye mr-1"></i>New
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Card Image -->
                <div class="position-relative overflow-hidden">
                    <img src="includes/uploads/announces/<?php echo $announce['image'] ?>" 
                         class="card-img-top announcement-image" 
                         alt="<?php echo htmlspecialchars($announce['title']) ?>">
                    <div class="image-overlay"></div>
                </div>
                
                <!-- Card Body -->
                <div class="card-body p-4">
                    <div class="announcement-content">
                        <p class="card-text text-muted mb-4" style="font-size: 1.1rem; line-height: 1.7;">
                            <?php echo nl2br(htmlspecialchars($announce['description'])) ?>
                        </p>
                    </div>
                    
                    <!-- Interaction Section -->
                    <div class="announcement-interactions">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <!-- Like/Unlike Section -->
                            <div class="like-section d-flex align-items-center mb-3 mb-md-0">
                                <button class="btn btn-outline-danger btn-sm rounded-pill like-button mr-3" 
                                        data-announcement-id="<?php echo $announce['announcement_id']; ?>">
                                    <i class="fas fa-heart mr-1"></i>Like
                                </button>
                                <div class="like-stats text-muted small">
                                    <span class="mr-3">
                                        <i class="fas fa-heart text-danger mr-1"></i>
                                        <span class="like-count font-weight-bold" id="like-count-<?php echo $announce['announcement_id']; ?>"><?php echo $countLikes['like_count'] ?></span> likes
                                    </span>
                                    <span>
                                        <i class="fas fa-comment text-primary mr-1"></i>
                                        <span class="font-weight-bold comment-count" id="comment-count-<?php echo $announce['announcement_id']; ?>"><?php echo count($allComments) ?></span> comments
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Comments Section -->
                <div class="card-footer bg-light border-0">
                    <!-- Add Comment Form -->
                    <div class="mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0">
                                    <i class="fas fa-comment text-muted"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control border-left-0 comment-input" 
                                   placeholder="Share your thoughts about this announcement..."
                                   id="comment-input-<?php echo $announce['announcement_id']; ?>">
                            <div class="input-group-append">
                                <button class="btn btn-primary comment-button" type="button"
                                        data-announcement-id="<?php echo $announce['announcement_id']; ?>">
                                    <i class="fas fa-paper-plane mr-1"></i>Post
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comments List -->
                    <div class="comments-section">
                        <?php if (!empty($allComments)) : ?>
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-comments mr-2"></i>Comments (<?php echo count($allComments) ?>)
                            </h6>
                            <div class="comments-list" id="comments-list-<?php echo $announce['announcement_id']; ?>">
                                <?php foreach ($allComments as $comment) : ?>
                                    <div class="comment-item bg-white rounded p-3 mb-3 shadow-sm">
                                        <div class="d-flex align-items-start">
                                            <div class="comment-avatar mr-3">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            </div>
                                            <div class="comment-content flex-grow-1">
                                                <div class="comment-header d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="comment-author mb-0 text-primary font-weight-bold">
                                                        <?php echo htmlspecialchars($comment['fName'] . ' ' . $comment['lName']) ?>
                                                    </h6>
                                                    <small class="comment-date text-muted">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        <?php echo date('M j, Y \a\t g:i A', strtotime($comment['created_at'])) ?>
                                                    </small>
                                                </div>
                                                <p class="comment-text mb-0 text-dark">
                                                    <?php echo nl2br(htmlspecialchars($comment['comment_text'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div class="text-center py-4">
                                <i class="fas fa-comment-slash fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No comments yet. Be the first to share your thoughts!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php 
                endforeach;
            endif;
        ?>

    </div>

    <!-- WebSocket connection -->
    <script>
    // Establish WebSocket connection
    const socket = new WebSocket('ws://localhost:8080');

    // WebSocket event handlers
    socket.onopen = function(event) {
        console.log('WebSocket connection established.');
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
                updateLikeCount(data);
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
        commentElement.className = 'comment-item bg-white rounded p-3 mb-3 shadow-sm new-comment';
        commentElement.innerHTML = `
            <div class="d-flex align-items-start">
                <div class="comment-avatar mr-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 40px; height: 40px;">
                        <i class="fas fa-user text-white"></i>
                    </div>
                </div>
                <div class="comment-content flex-grow-1">
                    <div class="comment-header d-flex justify-content-between align-items-center mb-2">
                        <h6 class="comment-author mb-0 text-primary font-weight-bold">
                            ${residentFullName}
                        </h6>
                        <small class="comment-date text-muted">
                            <i class="fas fa-clock mr-1"></i>
                            ${formatDate(createdAt)}
                        </small>
                    </div>
                    <p class="comment-text mb-0 text-dark">
                        ${comment}
                    </p>
                </div>
            </div>
        `;

        // Add to comments list
        commentsList.appendChild(commentElement);

        // Update comment count
        const commentCountElement = document.getElementById(`comment-count-${announcementId}`);
        if (commentCountElement) {
            const currentCount = parseInt(commentCountElement.textContent);
            commentCountElement.textContent = currentCount + 1;
        }

        // Show "No comments" message if this is the first comment
        const noCommentsMessage = commentsList.parentElement.querySelector('.text-center.py-4');
        if (noCommentsMessage) {
            noCommentsMessage.style.display = 'none';
        }

        // Scroll to new comment
        commentElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Function to update like count
    function updateLikeCount(data) {
        const announcementId = data.announcement_id;
        const countLikes = data.count_likes;
        
        const likeCountElement = document.getElementById(`like-count-${announcementId}`);
        if (likeCountElement) {
            likeCountElement.textContent = countLikes;
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
        if (event.target.classList.contains('comment-button') || event.target.closest('.comment-button')) {
            const button = event.target.classList.contains('comment-button') ? event.target : event.target.closest('.comment-button');
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
        if (event.target.classList.contains('like-button') || event.target.closest('.like-button')) {
            const button = event.target.classList.contains('like-button') ? event.target : event.target.closest('.like-button');
            const announcementId = button.dataset.announcementId;
            const residentId = '<?php echo $_SESSION['resident_id']; ?>';
            
            sendMessage(JSON.stringify({
                type: 'like',
                announcement_id: announcementId,
                resident_id: residentId,
            }));
        }
        
    });

    // Handle Enter key press in comment inputs
    document.addEventListener('keypress', function(event) {
        if (event.target.classList.contains('comment-input') && event.key === 'Enter') {
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
    </script>

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
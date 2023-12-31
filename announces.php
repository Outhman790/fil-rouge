<?php
session_start();


if (isset($_SESSION['resident_id'])) :
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
    /* Add custom styles here */
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a href="homepage.php">
                <img class="navbar-brand" src="assets/img/logo white.png" style="width: 200px; height: 60px">
                </img>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="homepage.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="announces.php">Announces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="payments.php">Payments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1 class="mt-5 mb-3 text-center">Announcements</h1>
        <?php
            require_once 'classes/admin.class.php';
            require_once 'classes/user.class.php';
            $adminObj = new Admin();
            $allAnnouncements = $adminObj->getAllAnnouncements();
            $userObj = new User();
            foreach ($allAnnouncements as $key => $announce) :
                $countLikes = $userObj->countLikes($announce['announcement_id']);
            ?>
        <!-- Example Announcement Card -->
        <div class="card mb-3" data-announcement-id="<?php echo $announce['announcement_id']; ?>">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title"><?php echo $announce['title'] ?></h5>
                <small class="text-muted d-block"
                    style="margin-bottom:0.75rem;"><?php echo $announce['created_at'] ?></small>
            </div>
            <div class="text-center">
                <img src="includes/uploads/announces/<?php echo $announce['image'] ?>"
                    class="card-img-top img-thumbnail" alt="Announcement Image" style="width: 20rem;">
            </div>
            <div class="card-body">
                <p class="card-text text-justify"><?php echo $announce['description'] ?></p>
                <div class="card-footer d-flex justify-content-between align-items-center mb-sm-2">
                    <div class="d-flex flex-row  justify-content-start align-items-center mr-2">
                        <button class="btn btn-primary btn-sm mr-1 like-button">Like</button>
                        <button class="btn btn-primary btn-sm dislike-button">Unlike</button>
                        <span class="ml-2 text-muted">Likes: <span
                                class="like-count"><?php echo $countLikes['like_count'] ?></span></span>
                        <span class="ml-2 text-muted">Dislikes: <span class="dislike-count">1</span></span>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control comment-input" placeholder="Add a comment...">
                        <div class="input-group-append">
                            <button class="btn btn-primary comment-button" type="button">Comment</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    require_once 'classes/user.class.php';
                    $userObj = new User();
                    $allComments = $userObj->showComments($announce['announcement_id']);
                    ?>
            <div class="card-footer comments-container">
                <?php
                        foreach ($allComments as $key => $comment) :
                        ?>
                <div class="d-flex comment-container flex-column flex-sm-row mt-2"
                    data-created_at="<?php echo $announce['created_at']; ?>">
                    <div class="comment-author mb-2 mb-sm-3">
                        <strong><?php echo $comment['fName'] . ' ' . $comment['lName'] ?></strong>
                    </div>
                    <div class="flex-grow-1 comment-message ml-sm-3">
                        <?php echo $comment['comment_text'] ?>
                    </div>
                    <div class="comment-date small text-muted mt-sm-0">
                        <?php echo $comment['created_at'] ?>
                    </div>
                </div>
                <hr style="margin: .25rem .5rem;">
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <!-- End of Example Announcement Card -->

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

        // Parse and process the message as needed
        try {
            const data = JSON.parse(event.data);
            console.log(data);
            if (data.type === 'comment') {
                const comment = data.message;
                const residentId = data.resident_id;
                const announcementId = data.announcement_id;
                const residentFullName = data.resident_fullName;
                const createdAt = data.created_at;

                // Create HTML elements for the comment
                const commentContainer = document.createElement('div');
                commentContainer.classList.add('comment-container', 'd-flex');

                const commentAuthor = document.createElement('div');
                commentAuthor.classList.add('comment-author', 'mb-2', 'mb-sm-3');
                commentAuthor.innerHTML = `<strong>${residentFullName}</strong>`;

                const commentMessage = document.createElement('div');
                commentMessage.classList.add('flex-grow-1', 'comment-message', 'ml-3');
                commentMessage.textContent = comment;

                const commentDate = document.createElement('div');
                commentDate.classList.add('comment-date', 'small', 'text-muted');
                commentDate.textContent = createdAt;

                // Append the comment elements to the comment container
                commentContainer.appendChild(commentAuthor);
                commentContainer.appendChild(commentMessage);
                commentContainer.appendChild(commentDate);

                // Get the comment container element
                const commentsContainer = document.querySelector('.card-footer.comments-container');

                // Append the comment container to the comments container
                commentsContainer.appendChild(commentContainer);
                const hr = document.createElement('hr');
                hr.style = "margin: .25rem .5rem";
                commentsContainer.appendChild(hr);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
        }
        try {
            const data = JSON.parse(event.data);

            if (data.type === 'like') {
                let likeCount = document.querySelector('.like-count');
                const residentId = data.resident_id;
                const announcementId = data.announcement_id;
                const countLikes = data.count_likes;
                likeCount.innerHTML = countLikes;

            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
        }
    };


    socket.onclose = function(event) {
        console.log('WebSocket connection closed.');
    };

    socket.onerror = function(error) {
        console.error('WebSocket error:', error);
    };

    // Function to send a message to the server
    function sendMessage(message) {
        if (socket.readyState === WebSocket.OPEN) {
            console.log(message);
            socket.send(message);
        }
    }

    // Example usage: sending a comment
    const commentButton = document.querySelector('.comment-button');
    const commentInput = document.querySelector('.comment-input');

    commentButton.addEventListener('click', function() {
        const comment = commentInput.value;
        const residentId = '<?php echo $_SESSION['resident_id']; ?>';
        const residentFullName = '<?php echo $_SESSION['fName'] . ' ' .  $_SESSION['lName'] ?>';
        const announcementId = this.closest('.card').dataset.announcementId;
        if (comment !== '') {
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
    });
    const likeBtn = document.querySelector('.like-button');
    likeBtn.addEventListener('click', function() {
        const residentId = '<?php echo $_SESSION['resident_id']; ?>';
        const announcementId = this.closest('.card').dataset.announcementId;
        sendMessage(JSON.stringify({
            type: 'like',
            announcement_id: announcementId,
            resident_id: residentId,
        }));

    })
    </script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php
else :
    header('location: login.php');
endif;
?>
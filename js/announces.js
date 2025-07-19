// Announcements JavaScript Module
class AnnouncementsManager {
    constructor() {
        this.socket = null;
        this.currentUserId = null;
        this.init();
    }

    init() {
        this.currentUserId = document.querySelector('meta[name="resident-id"]')?.content;
        this.setupWebSocket();
        this.bindEvents();
    }

    // WebSocket Management
    setupWebSocket() {
        this.socket = new WebSocket('ws://localhost:8080');
        
        this.socket.onopen = (event) => {
            console.log('WebSocket connection established.');
            setTimeout(() => {
                this.initializeLikeStates();
            }, 1000);
        };

        this.socket.onmessage = (event) => {
            this.handleWebSocketMessage(event);
        };

        this.socket.onclose = (event) => {
            console.log('WebSocket connection closed.');
            this.showConnectionStatus('WebSocket connection closed. Real-time features may not work.', 'warning');
        };

        this.socket.onerror = (error) => {
            console.error('WebSocket error:', error);
            this.showConnectionStatus('WebSocket server is not running. Real-time features are disabled.', 'error');
        };
    }

    handleWebSocketMessage(event) {
        console.log('Received message:', event.data);

        try {
            const data = JSON.parse(event.data);
            console.log('Parsed data:', data);
            
            switch(data.type) {
                case 'comment':
                    this.addCommentToDOM(data);
                    break;
                case 'like':
                    console.log('Received like update:', data);
                    this.updateLikeCount(data);
                    break;
                case 'like_status':
                    console.log('Received like status:', data);
                    this.updateLikeCount(data);
                    break;
                default:
                    console.log('Unknown message type:', data.type);
            }
        } catch (error) {
            console.error('Error parsing JSON:', error);
        }
    }

    sendMessage(message) {
        if (this.socket.readyState === WebSocket.OPEN) {
            console.log(message);
            this.socket.send(message);
        } else {
            console.warn('WebSocket is not connected. Message not sent:', message);
            this.showConnectionStatus('Cannot send message. WebSocket server is not running.', 'error');
        }
    }

    // Comment Management
    addCommentToDOM(data) {
        const announcementId = data.announcement_id;
        const comment = data.message;
        const residentFullName = data.resident_fullName;
        const createdAt = data.created_at;

        const commentsList = document.getElementById(`comments-list-${announcementId}`);
        
        if (!commentsList) {
            console.error('Comments list not found for announcement:', announcementId);
            return;
        }

        const commentElement = this.createCommentElement(residentFullName, createdAt, comment);
        commentsList.appendChild(commentElement);

        this.updateCommentCount(announcementId, data.total_comments);
        this.hideNoCommentsMessage(commentsList);
        this.updateCommentsHeader(commentsList);
        this.triggerCardAnimation(announcementId);

        commentElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    createCommentElement(residentFullName, createdAt, comment) {
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
                        ${this.formatDate(createdAt)}
                    </span>
                </div>
                <p class="comment-text-modern">
                    ${comment}
                </p>
            </div>
        `;
        return commentElement;
    }

    updateCommentCount(announcementId, totalComments) {
        const commentCountElement = document.getElementById(`comment-count-${announcementId}`);
        if (commentCountElement) {
            const newCount = totalComments || (parseInt(commentCountElement.textContent) + 1);
            commentCountElement.textContent = newCount;
        }
    }

    hideNoCommentsMessage(commentsList) {
        const noCommentsMessage = commentsList.parentElement.querySelector('.no-comments-state');
        if (noCommentsMessage) {
            noCommentsMessage.style.display = 'none';
        }
    }

    updateCommentsHeader(commentsList) {
        const commentsHeader = commentsList.parentElement.querySelector('.comments-header h6');
        if (commentsHeader) {
            const actualCount = commentsList.children.length;
            commentsHeader.innerHTML = `<i class="fas fa-comments"></i> Comments (${actualCount})`;
        }
    }

    // Like Management
    updateLikeCount(data) {
        const announcementId = data.announcement_id;
        const countLikes = data.count_likes;
        const isLiked = data.is_liked;
        
        console.log('Updating like count for announcement:', announcementId, 'Count:', countLikes, 'Is liked:', isLiked, 'For user:', data.resident_id);
        
        const likeButton = document.querySelector(`[data-announcement-id="${announcementId}"].like-btn`);
        if (likeButton) {
            this.updateLikeCountDisplay(likeButton, countLikes);
            
            if (isLiked !== undefined && (data.resident_id === this.currentUserId || data.type === 'like_status')) {
                console.log('Updating button state for current user. Is liked:', isLiked);
                this.updateLikeButtonState(likeButton, isLiked, countLikes, announcementId);
            }
        }
        
        this.triggerCardAnimation(announcementId);
    }

    updateLikeCountDisplay(likeButton, countLikes) {
        const likeCountSpan = likeButton.querySelector('.like-count');
        if (likeCountSpan) {
            likeCountSpan.textContent = countLikes;
        }
    }

    updateLikeButtonState(likeButton, isLiked, countLikes, announcementId) {
        if (isLiked) {
            likeButton.classList.add('liked');
            likeButton.innerHTML = `<i class="fas fa-heart"></i> <span class="like-count" id="like-count-${announcementId}">${countLikes}</span>`;
        } else {
            likeButton.classList.remove('liked');
            likeButton.innerHTML = `<i class="far fa-heart"></i> <span class="like-count" id="like-count-${announcementId}">${countLikes}</span>`;
        }
    }

    initializeLikeStates() {
        const likeButtons = document.querySelectorAll('.like-btn');
        likeButtons.forEach(button => {
            const announcementId = button.dataset.announcementId;
            
            this.sendMessage(JSON.stringify({
                type: 'check_like',
                announcement_id: announcementId,
                resident_id: this.currentUserId
            }));
        });
    }

    // Event Handlers
    bindEvents() {
        document.addEventListener('click', (event) => this.handleDocumentClick(event));
        document.addEventListener('keypress', (event) => this.handleKeyPress(event));
        document.addEventListener('keydown', (event) => this.handleKeyDown(event));
        
        // jQuery events for image modal
        this.bindImageEvents();
    }

    handleDocumentClick(event) {
        this.handleCommentSubmit(event);
        this.handleLikeButton(event);
        this.handleImageClick(event);
    }

    handleCommentSubmit(event) {
        if (event.target.classList.contains('comment-submit-btn') || event.target.closest('.comment-submit-btn')) {
            const button = event.target.classList.contains('comment-submit-btn') ? event.target : event.target.closest('.comment-submit-btn');
            const announcementId = button.dataset.announcementId;
            const commentInput = document.getElementById(`comment-input-${announcementId}`);
            const comment = commentInput.value.trim();
            
            if (comment !== '') {
                const residentFullName = document.querySelector('meta[name="resident-fullname"]')?.content;
                
                this.sendMessage(JSON.stringify({
                    type: 'comment',
                    message: comment,
                    resident_id: this.currentUserId,
                    resident_fullName: residentFullName,
                    announcement_id: announcementId,
                }));

                commentInput.value = '';
            }
        }
    }

    handleLikeButton(event) {
        if (event.target.classList.contains('like-btn') || event.target.closest('.like-btn')) {
            const button = event.target.classList.contains('like-btn') ? event.target : event.target.closest('.like-btn');
            const announcementId = button.dataset.announcementId;
            
            if (button.dataset.processing === 'true') {
                return;
            }
            button.dataset.processing = 'true';
            
            const isCurrentlyLiked = button.classList.contains('liked');
            console.log('Like button clicked. Currently liked:', isCurrentlyLiked);
            
            this.sendMessage(JSON.stringify({
                type: 'like',
                announcement_id: announcementId,
                resident_id: this.currentUserId,
                action: isCurrentlyLiked ? 'unlike' : 'like'
            }));
            
            setTimeout(() => {
                button.dataset.processing = 'false';
                console.log('Like toggle completed for announcement:', announcementId);
            }, 1000);
        }
    }

    handleImageClick(event) {
        console.log('Click detected on:', event.target);
        
        // Check if clicked element or its parent is image-clickable
        const imageElement = event.target.closest('.image-clickable') || 
                           (event.target.classList.contains('image-clickable') ? event.target : null);
        
        if (imageElement) {
            event.preventDefault();
            console.log('Image clicked!');
            
            const imageSrc = imageElement.getAttribute('data-image-src');
            const imageTitle = imageElement.getAttribute('data-image-title');
            
            console.log('Image src:', imageSrc);
            console.log('Image title:', imageTitle);
            
            this.showImageModal(imageSrc, imageTitle);
        }
        
        // Also check for image wrapper clicks
        const wrapperElement = event.target.closest('.announcement-image-wrapper');
        if (wrapperElement && !imageElement) {
            event.preventDefault();
            console.log('Image wrapper clicked!');
            
            const img = wrapperElement.querySelector('.image-clickable');
            if (img) {
                const imageSrc = img.getAttribute('data-image-src');
                const imageTitle = img.getAttribute('data-image-title');
                
                console.log('Wrapper - Image src:', imageSrc);
                console.log('Wrapper - Image title:', imageTitle);
                
                this.showImageModal(imageSrc, imageTitle);
            }
        }
    }

    handleKeyPress(event) {
        if (event.target.classList.contains('comment-input-modern') && event.key === 'Enter') {
            const commentInput = event.target;
            const announcementId = commentInput.id.replace('comment-input-', '');
            const comment = commentInput.value.trim();
            
            if (comment !== '') {
                const residentFullName = document.querySelector('meta[name="resident-fullname"]')?.content;
                
                this.sendMessage(JSON.stringify({
                    type: 'comment',
                    message: comment,
                    resident_id: this.currentUserId,
                    resident_fullName: residentFullName,
                    announcement_id: announcementId,
                }));

                commentInput.value = '';
            }
        }
    }

    handleKeyDown(event) {
        if (event.key === 'Escape') {
            $('#imageModal').modal('hide');
        }
    }

    // Image Modal Management
    showImageModal(imageSrc, imageTitle) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('modalImage').alt = imageTitle;
        document.getElementById('imageModalLabel').textContent = imageTitle;
        
        $('#imageModal').modal('show');
    }

    bindImageEvents() {
        const self = this;
        $(document).ready(function() {
            // Use event delegation for dynamic content
            $(document).on('click', '.image-clickable', function(e) {
                e.preventDefault();
                console.log('Image clicked via jQuery!');
                
                const imageSrc = $(this).attr('data-image-src');
                const imageTitle = $(this).attr('data-image-title');
                
                console.log('Image src:', imageSrc);
                console.log('Image title:', imageTitle);
                
                self.showImageModal(imageSrc, imageTitle);
            });

            $(document).on('click', '.announcement-image-wrapper', function(e) {
                e.preventDefault();
                console.log('Image wrapper clicked!');
                
                const img = $(this).find('.image-clickable');
                const imageSrc = img.attr('data-image-src');
                const imageTitle = img.attr('data-image-title');
                
                console.log('Wrapper - Image src:', imageSrc);
                console.log('Wrapper - Image title:', imageTitle);
                
                self.showImageModal(imageSrc, imageTitle);
            });

            $('#imageModal').on('click', function(event) {
                if (event.target === this) {
                    $(this).modal('hide');
                }
            });
        });
    }

    // Utility Functions
    triggerCardAnimation(announcementId) {
        const announcementCard = document.querySelector(`[data-announcement-id="${announcementId}"]`);
        if (announcementCard) {
            announcementCard.classList.add('new-activity');
            setTimeout(() => {
                announcementCard.classList.remove('new-activity');
            }, 600);
        }
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit'
        });
    }

    showConnectionStatus(message, type) {
        const statusDiv = document.getElementById('websocket-status') || this.createStatusDiv();
        statusDiv.textContent = message;
        statusDiv.className = `alert alert-${type === 'error' ? 'danger' : 'warning'} mt-2`;
        statusDiv.style.display = 'block';
        
        if (type !== 'error') {
            setTimeout(() => {
                statusDiv.style.display = 'none';
            }, 5000);
        }
    }

    createStatusDiv() {
        const statusDiv = document.createElement('div');
        statusDiv.id = 'websocket-status';
        statusDiv.style.display = 'none';
        document.querySelector('.container').insertBefore(statusDiv, document.querySelector('.container').firstChild);
        return statusDiv;
    }
}

// Initialize the announcements manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AnnouncementsManager();
});
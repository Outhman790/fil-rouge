// Ultra-Minimal Announcements JavaScript - Maximum Performance
(function() {
    let socket, userId, userName;

    function init() {
        const userMeta = document.querySelector('meta[name="resident-id"]');
        const nameMeta = document.querySelector('meta[name="resident-fullname"]');
        
        userId = userMeta?.content;
        userName = nameMeta?.content;

        setupSocket();
        setupEvents();
    }

    function setupSocket() {
        try {
            const wsHost = window.location.hostname;
            const wsUrl = `ws://${wsHost}:8080`;
            socket = new WebSocket(wsUrl);

            socket.onopen = () => {
                console.log('✅ WebSocket connected - Real-time updates enabled');
                initLikes();
            };

            socket.onerror = () => {
                console.warn('⚠️ WebSocket unavailable - Using HTTP fallback (features will still work)');
            };

            socket.onclose = () => {
                console.log('ℹ️ WebSocket disconnected - Using HTTP fallback');
            };

            socket.onmessage = (e) => {
                try {
                    const data = JSON.parse(e.data);
                    if (data.type === 'comment') addComment(data);
                    else if (data.type === 'like' || data.type === 'like_status') updateLike(data);
                    else if (data.type === 'comment_deleted') removeComment(data);
                } catch (err) {}
            };
        } catch (err) {
            console.warn('⚠️ WebSocket unavailable - Using HTTP fallback (features will still work)');
        }
    }

    function send(msg) {
        if (socket?.readyState === 1) {
            socket.send(JSON.stringify(msg));
            return true;
        }
        // WebSocket not available, use HTTP fallback
        return sendHTTP(msg);
    }

    function sendHTTP(msg) {
        const isLike = msg.type === 'like';
        const endpoint = isLike ? 'includes/toggle-like.inc.php' : 'includes/add-comment-http.inc.php';

        fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(msg)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (isLike) {
                    updateLike({
                        announcement_id: msg.announcement_id,
                        count_likes: data.count_likes,
                        is_liked: data.is_liked,
                        resident_id: userId
                    });
                } else {
                    addComment({
                        announcement_id: msg.announcement_id,
                        message: msg.message,
                        resident_fullName: msg.resident_fullName,
                        created_at: data.created_at,
                        comment_id: data.comment_id,
                        resident_id: userId
                    });
                    // Update comment count
                    const countEl = document.getElementById(`comment-count-${msg.announcement_id}`);
                    if (countEl) countEl.textContent = data.comment_count;
                }
            }
        })
        .catch(err => console.error('HTTP fallback error:', err));

        return true;
    }

    function setupEvents() {
        document.addEventListener('click', (e) => {
            // Check for specific buttons first (these should not trigger card modal)
            const btn = e.target.closest('.like-btn, .comment-submit-btn, .comment-submit-btn-modal, .comment-delete-btn, .image-clickable, .announcement-image-wrapper');
            if (btn) {
                e.stopPropagation(); // Prevent card click

                if (btn.classList.contains('like-btn')) {
                    handleLike(btn);
                } else if (btn.classList.contains('comment-submit-btn') || btn.classList.contains('comment-submit-btn-modal')) {
                    handleComment(btn);
                } else if (btn.classList.contains('comment-delete-btn')) {
                    handleDeleteComment(btn);
                } else {
                    handleImage(btn);
                }
                return;
            }

            // Check if clicking on announcement card
            const card = e.target.closest('.announcement-card-modern');
            if (card) {
                const announcementId = card.dataset.announcementId;
                if (announcementId) {
                    openAnnouncementModal(announcementId);
                }
            }
        });

        document.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && (e.target.classList.contains('comment-input-modern') || e.target.classList.contains('comment-input-modal'))) {
                e.preventDefault();
                const btn = e.target.parentElement.querySelector('.comment-submit-btn, .comment-submit-btn-modal');
                if (btn) handleComment(btn);
            }
        });
    }

    function openAnnouncementModal(announcementId) {
        const modalEl = document.getElementById(`announcementModal-${announcementId}`);
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function handleLike(btn) {
        const id = btn.dataset.announcementId;
        if (!id) return;

        const isLiked = btn.classList.contains('liked');
        const icon = btn.querySelector('i');
        const count = btn.querySelector('.like-count');
        const currentCount = parseInt(count.textContent) || 0;

        if (isLiked) {
            btn.classList.remove('liked');
            icon.className = 'far fa-heart';
            count.textContent = Math.max(0, currentCount - 1);
        } else {
            btn.classList.add('liked');
            icon.className = 'fas fa-heart';
            count.textContent = currentCount + 1;
        }

        send({
            type: 'like',
            announcement_id: id,
            resident_id: userId,
            action: isLiked ? 'unlike' : 'like'
        });
    }

    function updateLike(data) {
        // Update all like buttons (card and modal) for this announcement
        const btns = document.querySelectorAll(`[data-announcement-id="${data.announcement_id}"].like-btn`);

        btns.forEach(btn => {
            const count = btn.querySelector('.like-count');
            if (count) count.textContent = data.count_likes || 0;

            if (data.resident_id === userId && data.is_liked !== undefined) {
                const icon = btn.querySelector('i');
                if (data.is_liked) {
                    btn.classList.add('liked');
                    if (icon) icon.className = 'fas fa-heart';
                } else {
                    btn.classList.remove('liked');
                    if (icon) icon.className = 'far fa-heart';
                }
            }
        });
    }

    function handleComment(btn) {
        const id = btn.dataset.announcementId;
        // Try modal input first, then regular input
        let input = document.getElementById(`modal-comment-input-${id}`);
        if (!input) {
            input = document.getElementById(`comment-input-${id}`);
        }

        if (!input || !id) return;

        const text = input.value.trim();
        if (!text) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        const success = send({
            type: 'comment',
            message: text,
            resident_id: userId,
            resident_fullName: userName,
            announcement_id: id
        });

        if (success) input.value = '';

        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        }, 500);
    }

    function handleDeleteComment(btn) {
        const commentId = btn.dataset.commentId;
        const announcementId = btn.dataset.announcementId;

        if (!commentId || !announcementId) return;

        // Store data for later use
        window.pendingCommentDelete = { btn, commentId, announcementId };

        // Show confirmation modal
        const modal = new bootstrap.Modal(document.getElementById('deleteCommentModal'));
        modal.show();
    }

    function performDeleteComment() {
        if (!window.pendingCommentDelete) return;

        const { btn, commentId, announcementId } = window.pendingCommentDelete;

        // Disable button and show loading
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteCommentModal'));
        if (modal) modal.hide();

        // Try WebSocket first, fallback to HTTP
        const sentViaWS = send({
            type: 'delete_comment',
            comment_id: commentId,
            announcement_id: announcementId,
            resident_id: userId
        });

        // If WebSocket is not available, use HTTP
        if (!sentViaWS || socket?.readyState !== 1) {
            fetch('includes/delete-comment.inc.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    comment_id: commentId,
                    announcement_id: announcementId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    removeComment({
                        comment_id: commentId,
                        announcement_id: announcementId,
                        comment_count: data.comment_count,
                        success: true
                    });
                } else {
                    console.error('Failed to delete comment:', data.error);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-trash-alt"></i>';
                }
            })
            .catch(err => {
                console.error('Delete comment error:', err);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-trash-alt"></i>';
            })
            .finally(() => {
                window.pendingCommentDelete = null;
            });
        } else {
            window.pendingCommentDelete = null;
        }
    }

    function removeComment(data) {
        if (!data.success) return;

        const { comment_id, announcement_id, comment_count } = data;

        // Remove comment from all instances in DOM (both regular and modal lists)
        const commentElements = document.querySelectorAll(`[data-comment-id="${comment_id}"]`);
        commentElements.forEach(commentElement => {
            commentElement.style.opacity = '0';
            commentElement.style.transform = 'translateX(-20px)';
            setTimeout(() => commentElement.remove(), 300);
        });

        // Update all comment counts
        const countEls = document.querySelectorAll(`#comment-count-${announcement_id}, [id^="comment-count-${announcement_id}"]`);
        countEls.forEach(el => {
            el.textContent = comment_count;
        });

        // Update regular comments list
        const list = document.getElementById(`comments-list-${announcement_id}`);
        if (list) {
            const header = list.parentElement.querySelector('.comments-header');
            if (header) {
                header.innerHTML = `<i class="fas fa-comments"></i> Comments (${comment_count})`;
            }

            if (comment_count === 0) {
                const empty = list.parentElement.querySelector('.no-comments-state');
                if (empty) empty.style.display = 'block';
            }
        }

        // Update modal comments list
        const modalList = document.getElementById(`modal-comments-list-${announcement_id}`);
        if (modalList) {
            const modalHeader = modalList.parentElement.querySelector('.comments-header-modal');
            if (modalHeader) {
                modalHeader.innerHTML = `<i class="fas fa-comments"></i> All Comments`;
            }

            if (comment_count === 0) {
                const modalEmpty = modalList.parentElement.querySelector('.no-comments-state-modal');
                if (modalEmpty) modalEmpty.style.display = 'block';
            }
        }
    }

    // Set up confirm delete button listener
    document.addEventListener('DOMContentLoaded', function() {
        const confirmBtn = document.getElementById('confirmDeleteComment');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', performDeleteComment);
        }
    });

    function addComment(data) {
        // Add to both regular and modal comments lists
        const list = document.getElementById(`comments-list-${data.announcement_id}`);
        const modalList = document.getElementById(`modal-comments-list-${data.announcement_id}`);

        const date = new Date(data.created_at).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit'
        });

        const safeUser = escape(data.resident_fullName);
        const safeText = escape(data.message).replace(/\n/g, '<br>');

        // Check if this is the current user's comment
        const isOwnComment = data.resident_id == userId;

        // Include delete button if it's the user's own comment
        const deleteButtonHTML = isOwnComment && data.comment_id
            ? `<button class="comment-delete-btn" data-comment-id="${data.comment_id}" data-announcement-id="${data.announcement_id}" title="Delete your comment"><i class="fas fa-trash-alt"></i></button>`
            : '';

        const commentHTML = `<div class="comment-avatar-modern"><i class="fas fa-user"></i></div><div class="comment-content-modern"><div class="comment-header-modern"><h6 class="comment-author-modern">${safeUser}</h6><span class="comment-date-modern">${date}</span></div><p class="comment-text-modern">${safeText}</p></div>${deleteButtonHTML}`;

        // Add to regular list
        if (list) {
            const empty = list.parentElement.querySelector('.no-comments-state');
            if (empty) empty.style.display = 'none';

            const div = document.createElement('div');
            div.className = 'comment-item-modern';
            if (data.comment_id) div.dataset.commentId = data.comment_id;
            div.innerHTML = commentHTML;
            list.appendChild(div);

            const header = list.parentElement.querySelector('.comments-header');
            if (header) header.innerHTML = `<i class="fas fa-comments"></i> Comments (${list.children.length})`;
        }

        // Add to modal list
        if (modalList) {
            const modalEmpty = modalList.parentElement.querySelector('.no-comments-state-modal');
            if (modalEmpty) modalEmpty.style.display = 'none';

            const modalDiv = document.createElement('div');
            modalDiv.className = 'comment-item-modern';
            if (data.comment_id) modalDiv.dataset.commentId = data.comment_id;
            modalDiv.innerHTML = commentHTML;
            modalList.appendChild(modalDiv);

            const modalHeader = modalList.parentElement.querySelector('.comments-header-modal');
            if (modalHeader) modalHeader.innerHTML = `<i class="fas fa-comments"></i> All Comments`;
        }

        // Update comment counts
        const countEls = document.querySelectorAll(`#comment-count-${data.announcement_id}, [id^="comment-count-${data.announcement_id}"]`);
        countEls.forEach(el => {
            el.textContent = data.total_comments || (parseInt(el.textContent) + 1);
        });
    }

    function handleImage(el) {
        const src = el.dataset.imageSrc;
        const title = el.dataset.imageTitle;
        if (!src) return;

        const img = document.getElementById('modalImage');
        const titleEl = document.getElementById('imageModalLabel');

        if (img) {
            img.src = src;
            img.alt = title || 'Image';
        }
        if (titleEl) titleEl.textContent = title || 'Image';

        // Use Bootstrap 5 modal API
        const modalEl = document.getElementById('imageModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function initLikes() {
        document.querySelectorAll('.like-btn').forEach(btn => {
            const id = btn.dataset.announcementId;
            if (id) {
                send({
                    type: 'check_like',
                    announcement_id: id,
                    resident_id: userId
                });
            }
        });
    }

    function escape(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
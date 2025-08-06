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
            socket.onopen = () => initLikes();
            socket.onmessage = (e) => {
                try {
                    const data = JSON.parse(e.data);
                    if (data.type === 'comment') addComment(data);
                    else if (data.type === 'like' || data.type === 'like_status') updateLike(data);
                } catch (err) {}
            };
        } catch (err) {}
    }

    function send(msg) {
        if (socket?.readyState === 1) {
            socket.send(JSON.stringify(msg));
            return true;
        }
        return false;
    }

    function setupEvents() {
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.like-btn, .comment-submit-btn, .image-clickable, .announcement-image-wrapper');
            if (!btn) return;
            
            e.preventDefault();
            
            if (btn.classList.contains('like-btn')) {
                handleLike(btn);
            } else if (btn.classList.contains('comment-submit-btn')) {
                handleComment(btn);
            } else {
                handleImage(btn);
            }
        });

        document.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && e.target.classList.contains('comment-input-modern')) {
                e.preventDefault();
                const btn = e.target.parentElement.querySelector('.comment-submit-btn');
                if (btn) handleComment(btn);
            }
        });
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
        const btn = document.querySelector(`[data-announcement-id="${data.announcement_id}"].like-btn`);
        if (!btn) return;

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
    }

    function handleComment(btn) {
        const id = btn.dataset.announcementId;
        const input = document.getElementById(`comment-input-${id}`);
        
        if (!input || !id) return;

        const text = input.value.trim();
        if (!text) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner"></i>';

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

    function addComment(data) {
        const list = document.getElementById(`comments-list-${data.announcement_id}`);
        if (!list) return;

        const empty = list.parentElement.querySelector('.no-comments-state');
        if (empty) empty.style.display = 'none';

        const div = document.createElement('div');
        div.className = 'comment-item-modern';
        
        const date = new Date(data.created_at).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit'
        });

        const safeUser = escape(data.resident_fullName);
        const safeText = escape(data.message).replace(/\n/g, '<br>');

        div.innerHTML = `<div class="comment-avatar-modern"><i class="fas fa-user"></i></div><div class="comment-content-modern"><div class="comment-header-modern"><h6 class="comment-author-modern">${safeUser}</h6><span class="comment-date-modern">${date}</span></div><p class="comment-text-modern">${safeText}</p></div>`;
        
        list.appendChild(div);

        const countEl = document.getElementById(`comment-count-${data.announcement_id}`);
        if (countEl) countEl.textContent = data.total_comments || (parseInt(countEl.textContent) + 1);

        const header = list.parentElement.querySelector('.comments-header');
        if (header) header.innerHTML = `<i class="fas fa-comments"></i> Comments (${list.children.length})`;
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
        
        $('#imageModal').modal('show');
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
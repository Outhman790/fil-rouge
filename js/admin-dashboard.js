// Admin Dashboard JavaScript - Handles expenses and announcements functionality

document.addEventListener('DOMContentLoaded', function() {
    // Pagination Variables
    let currentPage = 1;
    let itemsPerPage = 8;
    let allAnnouncementCards = [];

    // Initialize pagination
    function initPagination() {
        const container = document.getElementById('announcements-container');
        if (!container) return;

        allAnnouncementCards = Array.from(container.querySelectorAll('.col-lg-3'));

        if (allAnnouncementCards.length === 0) return;

        renderPage(currentPage);
        renderPagination();

        // Items per page selector
        const itemsPerPageSelect = document.getElementById('items-per-page');
        if (itemsPerPageSelect) {
            itemsPerPageSelect.addEventListener('change', function() {
                itemsPerPage = parseInt(this.value);
                currentPage = 1;
                renderPage(currentPage);
                renderPagination();
            });
        }
    }

    // Render current page
    function renderPage(page) {
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;

        // Hide all cards
        allAnnouncementCards.forEach(card => card.style.display = 'none');

        // Show only cards for current page
        allAnnouncementCards.slice(startIndex, endIndex).forEach(card => card.style.display = '');

        // Update showing text
        const showingStart = document.getElementById('showing-start');
        const showingEnd = document.getElementById('showing-end');
        const showingTotal = document.getElementById('showing-total');

        if (showingStart) showingStart.textContent = allAnnouncementCards.length > 0 ? startIndex + 1 : 0;
        if (showingEnd) showingEnd.textContent = Math.min(endIndex, allAnnouncementCards.length);
        if (showingTotal) showingTotal.textContent = allAnnouncementCards.length;

        // Scroll to top of announcements section
        const announcementsSection = document.getElementById('announcements-container');
        if (announcementsSection && page !== 1) {
            announcementsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Render pagination buttons
    function renderPagination() {
        const paginationContainer = document.getElementById('pagination');
        if (!paginationContainer) return;

        const totalPages = Math.ceil(allAnnouncementCards.length / itemsPerPage);

        // Hide pagination if only one page
        const paginationControls = document.getElementById('pagination-controls');
        if (paginationControls) {
            paginationControls.style.display = totalPages <= 1 ? 'none' : 'flex';
        }

        if (totalPages <= 1) return;

        paginationContainer.innerHTML = '';

        // Previous button
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="#" aria-label="Previous"><i class="fas fa-chevron-left"></i></a>`;
        prevLi.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
                renderPagination();
            }
        });
        paginationContainer.appendChild(prevLi);

        // Page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        // Adjust start if we're near the end
        if (endPage - startPage < maxVisiblePages - 1) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        // First page
        if (startPage > 1) {
            const firstLi = createPageButton(1);
            paginationContainer.appendChild(firstLi);

            if (startPage > 2) {
                const ellipsisLi = document.createElement('li');
                ellipsisLi.className = 'page-item disabled';
                ellipsisLi.innerHTML = '<a class="page-link">...</a>';
                paginationContainer.appendChild(ellipsisLi);
            }
        }

        // Page buttons
        for (let i = startPage; i <= endPage; i++) {
            const pageLi = createPageButton(i);
            paginationContainer.appendChild(pageLi);
        }

        // Last page
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const ellipsisLi = document.createElement('li');
                ellipsisLi.className = 'page-item disabled';
                ellipsisLi.innerHTML = '<a class="page-link">...</a>';
                paginationContainer.appendChild(ellipsisLi);
            }

            const lastLi = createPageButton(totalPages);
            paginationContainer.appendChild(lastLi);
        }

        // Next button
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="#" aria-label="Next"><i class="fas fa-chevron-right"></i></a>`;
        nextLi.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                renderPage(currentPage);
                renderPagination();
            }
        });
        paginationContainer.appendChild(nextLi);
    }

    // Create page button
    function createPageButton(pageNumber) {
        const li = document.createElement('li');
        li.className = `page-item ${currentPage === pageNumber ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#">${pageNumber}</a>`;
        li.addEventListener('click', function(e) {
            e.preventDefault();
            currentPage = pageNumber;
            renderPage(currentPage);
            renderPagination();
        });
        return li;
    }

    // Initialize pagination after DOM is ready
    initPagination();

    // Expense delete handlers
    let expenseId = null;
    document.querySelectorAll('.btn-delete-expense').forEach(function(btn) {
        btn.addEventListener('click', function() {
            expenseId = this.getAttribute('data-expense-id');
            document.getElementById('delete-expense-id').value = expenseId;
        });
    });

    // Announcement delete handlers
    let announceId = null;
    document.querySelectorAll('.btn-delete-announce').forEach(function(btn) {
        btn.addEventListener('click', function() {
            announceId = this.getAttribute('data-announce-id');
            document.getElementById('delete-announce-id').value = announceId;
        });
    });

    // Announcement card hover effects
    document.querySelectorAll('.announce-card').forEach(function(card) {
        const overlay = card.querySelector('.announce-overlay');

        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
            if (overlay) overlay.style.opacity = '1';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
            if (overlay) overlay.style.opacity = '0';
        });
    });

    // Announcement card click handlers - Open details modal
    document.querySelectorAll('.announce-card').forEach(function(card) {
        card.addEventListener('click', function() {
            const title = this.getAttribute('data-announce-title');
            const description = this.getAttribute('data-announce-description');
            const image = this.getAttribute('data-announce-image');
            const date = this.getAttribute('data-announce-date');
            const likes = this.getAttribute('data-announce-likes');
            const commentsJson = this.getAttribute('data-announce-comments');
            const comments = JSON.parse(commentsJson);

            // Populate modal with announcement data
            document.getElementById('modal-announce-title').textContent = title;
            document.getElementById('modal-announce-description').textContent = description;
            document.getElementById('modal-announce-date').textContent = formatDate(date);
            document.getElementById('modal-announce-likes').textContent = likes;
            document.getElementById('modal-announce-comments-count').textContent = comments.length;

            // Handle image
            const imageContainer = document.getElementById('modal-announce-image-container');
            if (image) {
                imageContainer.innerHTML = `<img src="uploads/announces/${image}" class="img-fluid rounded shadow-sm" alt="Announcement Image" style="max-height: 400px; width: 100%; object-fit: cover;">`;
            } else {
                imageContainer.innerHTML = '';
            }

            // Handle comments
            const commentsList = document.getElementById('modal-comments-list');
            if (comments.length > 0) {
                let commentsHTML = '';
                comments.forEach(function(comment) {
                    commentsHTML += `
                        <div class="card mb-2 border-start border-primary border-3">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <strong class="text-primary">
                                        <i class="fas fa-user-circle me-1"></i>
                                        ${escapeHtml(comment.fName + ' ' + comment.lName)}
                                    </strong>
                                    <small class="text-muted">
                                        ${formatDate(comment.created_at)}
                                    </small>
                                </div>
                                <p class="mb-0">${escapeHtml(comment.comment_text).replace(/\n/g, '<br>')}</p>
                            </div>
                        </div>
                    `;
                });
                commentsList.innerHTML = commentsHTML;
            } else {
                commentsList.innerHTML = `
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-comment-slash me-1"></i>
                        No comments yet
                    </div>
                `;
            }

            // Show the modal
            const detailsModal = new bootstrap.Modal(document.getElementById('announceDetailsModal'));
            detailsModal.show();
        });
    });

    // Show feedback modal if needed
    const urlParams = new URLSearchParams(window.location.search);
    const deleteExpense = urlParams.get('delete-expense');
    const deleteAnnounce = urlParams.get('delete-announce');

    if (deleteExpense === 'success') {
        const feedbackModal = new bootstrap.Modal(document.getElementById('expenseDeleteFeedbackModal'));
        document.querySelector('.expense-delete-feedback-msg').innerHTML = '<div class="text-center"><i class="fas fa-check-circle text-success fa-2x mb-2"></i><p class="text-success">Expense deleted successfully!</p></div>';
        feedbackModal.show();
    } else if (deleteExpense === 'error') {
        const feedbackModal = new bootstrap.Modal(document.getElementById('expenseDeleteFeedbackModal'));
        document.querySelector('.expense-delete-feedback-msg').innerHTML = '<div class="text-center"><i class="fas fa-exclamation-triangle text-danger fa-2x mb-2"></i><p class="text-danger">An error occurred while deleting the expense.</p></div>';
        feedbackModal.show();
    }

    if (deleteAnnounce === 'success') {
        const feedbackModal = new bootstrap.Modal(document.getElementById('announceDeleteFeedbackModal'));
        document.querySelector('.announce-delete-feedback-msg').innerHTML = '<div class="text-center"><i class="fas fa-check-circle text-success fa-2x mb-2"></i><p class="text-success">Announcement deleted successfully!</p></div>';
        feedbackModal.show();
    } else if (deleteAnnounce === 'error') {
        const feedbackModal = new bootstrap.Modal(document.getElementById('announceDeleteFeedbackModal'));
        document.querySelector('.announce-delete-feedback-msg').innerHTML = '<div class="text-center"><i class="fas fa-exclamation-triangle text-danger fa-2x mb-2"></i><p class="text-danger">An error occurred while deleting the announcement.</p></div>';
        feedbackModal.show();
    }

    // Invoice image modal logic
    document.querySelectorAll('.invoice-image-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var imgSrc = this.getAttribute('data-img-src');
            document.getElementById('invoiceImagePreview').src = imgSrc;
            var imgModal = new bootstrap.Modal(document.getElementById('invoiceImageModal'));
            imgModal.show();
        });
    });

    // Helper function to format dates
    function formatDate(dateString) {
        const date = new Date(dateString);
        const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        return date.toLocaleDateString('en-US', options);
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});

// UI Feedback and Loading States Manager
class UIFeedback {
    constructor() {
        this.toastContainer = null;
        this.loadingOverlay = null;
        this.init();
    }

    init() {
        this.createToastContainer();
        this.createLoadingOverlay();
    }

    createToastContainer() {
        if (!document.querySelector('.toast-container')) {
            this.toastContainer = document.createElement('div');
            this.toastContainer.className = 'toast-container';
            document.body.appendChild(this.toastContainer);
        } else {
            this.toastContainer = document.querySelector('.toast-container');
        }
    }

    createLoadingOverlay() {
        if (!document.querySelector('.loading-overlay')) {
            this.loadingOverlay = document.createElement('div');
            this.loadingOverlay.className = 'loading-overlay';
            this.loadingOverlay.innerHTML = '<div class="loading-spinner"></div>';
            document.body.appendChild(this.loadingOverlay);
        } else {
            this.loadingOverlay = document.querySelector('.loading-overlay');
        }
    }

    // Show global loading overlay
    showLoading() {
        if (this.loadingOverlay) {
            this.loadingOverlay.style.display = 'flex';
        }
    }

    // Hide global loading overlay
    hideLoading() {
        if (this.loadingOverlay) {
            this.loadingOverlay.style.display = 'none';
        }
    }

    // Add loading state to button
    setButtonLoading(button, loading = true) {
        if (loading) {
            button.classList.add('btn-loading');
            button.disabled = true;
            button.setAttribute('data-original-text', button.innerHTML);
            button.innerHTML = 'Loading...';
        } else {
            button.classList.remove('btn-loading');
            button.disabled = false;
            const originalText = button.getAttribute('data-original-text');
            if (originalText) {
                button.innerHTML = originalText;
                button.removeAttribute('data-original-text');
            }
        }
    }

    // Show toast notification
    showToast(message, type = 'success', title = null, duration = 5000) {
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        
        const iconMap = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        const titleText = title || this.getDefaultTitle(type);
        
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="${iconMap[type]}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${titleText}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" type="button">
                <i class="fas fa-times"></i>
            </button>
        `;

        // Add click handler for close button
        toast.querySelector('.toast-close').addEventListener('click', () => {
            this.hideToast(toast);
        });

        this.toastContainer.appendChild(toast);

        // Trigger animation
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        // Auto-hide after duration
        if (duration > 0) {
            setTimeout(() => {
                this.hideToast(toast);
            }, duration);
        }

        return toast;
    }

    hideToast(toast) {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    getDefaultTitle(type) {
        const titles = {
            success: 'Success',
            error: 'Error',
            warning: 'Warning',
            info: 'Information'
        };
        return titles[type] || 'Notification';
    }

    // Form validation feedback
    setFieldValid(field, message = null) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        
        this.removeFieldFeedback(field);
        
        if (message) {
            const feedback = document.createElement('div');
            feedback.className = 'valid-feedback';
            feedback.textContent = message;
            field.parentNode.appendChild(feedback);
        }
    }

    setFieldInvalid(field, message = null) {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        
        this.removeFieldFeedback(field);
        
        if (message) {
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = message;
            field.parentNode.appendChild(feedback);
        }
    }

    clearFieldValidation(field) {
        field.classList.remove('is-valid', 'is-invalid');
        this.removeFieldFeedback(field);
    }

    removeFieldFeedback(field) {
        const existingFeedback = field.parentNode.querySelectorAll('.valid-feedback, .invalid-feedback');
        existingFeedback.forEach(feedback => feedback.remove());
    }

    // Progress indicator
    showProgress(container, percentage = 0) {
        const existingProgress = container.querySelector('.progress-indicator');
        if (existingProgress) {
            existingProgress.remove();
        }

        const progressHTML = `
            <div class="progress-indicator">
                <div class="progress-bar-indicator" style="width: ${percentage}%"></div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', progressHTML);
    }

    updateProgress(container, percentage) {
        const progressBar = container.querySelector('.progress-bar-indicator');
        if (progressBar) {
            progressBar.style.width = `${percentage}%`;
        }
    }

    hideProgress(container) {
        const progress = container.querySelector('.progress-indicator');
        if (progress) {
            progress.remove();
        }
    }

    // Confirmation dialog
    showConfirmation(message, title = 'Confirm Action', onConfirm = null, onCancel = null) {
        // Create modal if it doesn't exist
        let modal = document.getElementById('confirmationModal');
        
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'confirmationModal';
            modal.className = 'modal fade';
            modal.setAttribute('tabindex', '-1');
            modal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="confirmation-message"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary confirm-btn">Confirm</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // Update content
        modal.querySelector('.modal-title').textContent = title;
        modal.querySelector('.confirmation-message').textContent = message;

        // Set up event handlers
        const confirmBtn = modal.querySelector('.confirm-btn');
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

        newConfirmBtn.addEventListener('click', () => {
            if (onConfirm) onConfirm();
            $(modal).modal('hide');
        });

        // Show modal
        $(modal).modal('show');

        // Handle cancel
        $(modal).off('hidden.bs.modal').on('hidden.bs.modal', () => {
            if (onCancel) onCancel();
        });
    }
}

// Global instance
window.uiFeedback = new UIFeedback();

// jQuery document ready
$(document).ready(function() {
    console.log('UI Feedback system initialized');
    
    // Example usage for forms
    $('form').on('submit', function(e) {
        const submitBtn = $(this).find('[type="submit"]');
        if (submitBtn.length) {
            window.uiFeedback.setButtonLoading(submitBtn[0], true);
        }
    });
    
    // Add smooth loading states to all buttons with data-loading attribute
    $('[data-loading]').on('click', function() {
        const btn = this;
        window.uiFeedback.setButtonLoading(btn, true);
        
        // Auto-restore after 3 seconds if not manually restored
        setTimeout(() => {
            window.uiFeedback.setButtonLoading(btn, false);
        }, 3000);
    });
});
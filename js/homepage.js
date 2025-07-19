// Homepage Manager Class
class HomepageManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.enhanceTable();
    }

    bindEvents() {
        // Image modal functionality
        this.bindImageModal();
        
        // Add smooth animations
        this.addAnimations();
        
        // Add hover effects
        this.addHoverEffects();
    }

    bindImageModal() {
        $(document).on('click', 'a[data-toggle="modal"]', function(e) {
            e.preventDefault();
            const imageSrc = $(this).find('img').attr('src');
            
            console.log('Opening image modal for:', imageSrc);
            
            $('#modalImage').attr('src', imageSrc);
            $('#imageModal').modal('show');
        });

        // Close modal on outside click
        $('#imageModal').on('click', function(event) {
            if (event.target === this) {
                $(this).modal('hide');
            }
        });

        // Close modal with escape key
        $(document).keydown(function(event) {
            if (event.key === 'Escape') {
                $('#imageModal').modal('hide');
            }
        });
    }

    enhanceTable() {
        // Add responsive data labels for mobile
        const headers = [];
        $('.table thead th').each(function() {
            headers.push($(this).text().trim());
        });

        $('.table tbody tr').each(function() {
            $(this).find('td').each(function(index) {
                if (headers[index]) {
                    $(this).attr('data-label', headers[index]);
                }
            });
        });

        // Add loading animation for images
        $('.table img').on('load', function() {
            $(this).addClass('loaded');
        });
    }

    addAnimations() {
        // Simple fade in welcome section (no delays)
        $('.welcome-text').fadeIn(500);
        
        // No table row animations to prevent scroll lag
        $('.table tbody tr').show();

        // Simple pagination fade
        $('.pagination').fadeIn(300);
    }

    addHoverEffects() {
        // Enhanced hover effects for table rows
        $('.table tbody tr').hover(
            function() {
                $(this).addClass('table-hover-effect');
            },
            function() {
                $(this).removeClass('table-hover-effect');
            }
        );

        // Add click animation to payment button
        $('.btn-pay').click(function() {
            $(this).addClass('btn-click-animation');
            setTimeout(() => {
                $(this).removeClass('btn-click-animation');
            }, 300);
        });
    }

    // Utility functions
    formatCurrency(amount) {
        return new Intl.NumberFormat('fr-MA', {
            style: 'currency',
            currency: 'MAD'
        }).format(amount);
    }

    showNotification(message, type = 'info') {
        // Simple notification system
        const notification = $(`
            <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999;">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.alert('close');
        }, 5000);
    }
}

// Initialize when DOM is ready
$(document).ready(function() {
    new HomepageManager();
    
    // Add any additional homepage-specific functionality here
    console.log('Homepage initialized successfully');
});

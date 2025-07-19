// Payments Dashboard Manager Class
class PaymentsDashboard {
    constructor() {
        this.debugMode = false;
        this.init();
    }

    init() {
        this.bindEvents();
        this.initializeFilters();
        this.addAnimations();
        this.initializeTooltips();
        this.startAutoRefresh();
    }

    bindEvents() {
        // Filter event handlers
        $('#statusFilter').on('change', () => this.applyFilters());
        $('#monthFilter').on('change', () => this.applyFilters());
        $('#searchFilter').on('input', () => this.applyFilters());
        
        // Quick filter buttons
        $('.quick-filters button[data-filter]').on('click', (e) => this.handleQuickFilter(e));
        
        // Clear filters
        $('#clearFilters').on('click', () => this.clearAllFilters());
        
        // Table hover effects
        this.bindTableEffects();
        
        // Payment button effects
        this.bindPaymentButtonEffects();
    }

    initializeFilters() {
        // Initialize counters
        const totalRows = $('#paymentTable tbody tr').length;
        $('#totalCount').text(totalRows);
        $('#visibleCount').text(totalRows);
        
        this.log('Filters initialized with', totalRows, 'total rows');
    }

    addAnimations() {
        // Animate payment cards on load
        $('.payment-status-card').hide().fadeIn(1000);
        
        // Stagger animation for stat cards
        $('.stat-card').each(function(index) {
            $(this).delay(index * 200).fadeIn(500);
        });
        
        // Animate progress bar
        setTimeout(() => {
            $('.progress-bar').each(function() {
                const width = $(this).attr('aria-valuenow') + '%';
                $(this).css('width', width);
            });
        }, 500);
    }

    initializeTooltips() {
        $('[data-toggle="tooltip"]').tooltip();
    }

    startAutoRefresh() {
        // Auto-refresh payment status every 5 minutes
        setInterval(() => {
            this.checkForUpdates();
        }, 300000);
    }

    // Filter Functions
    applyFilters() {
        const statusFilter = $('#statusFilter').val();
        const monthFilter = $('#monthFilter').val();
        const searchFilter = $('#searchFilter').val().toLowerCase();
        
        let visibleRows = 0;
        
        $('#paymentTable tbody tr').each(function() {
            const row = $(this);
            if (row.attr('id') === 'no-results-message') return;
            
            let showRow = true;
            
            // Status filter
            if (statusFilter !== 'all') {
                const rowStatus = row.find('.status-badge').hasClass(`status-${statusFilter}`);
                if (!rowStatus) showRow = false;
            }
            
            // Month filter
            if (monthFilter && showRow) {
                const rowDate = row.find('td:nth-child(1)').text();
                const [filterYear, filterMonth] = monthFilter.split('-');
                const monthMatch = rowDate.includes(filterMonth) && rowDate.includes(filterYear);
                if (!monthMatch) showRow = false;
            }
            
            // Search filter
            if (searchFilter && showRow) {
                const rowText = row.text().toLowerCase();
                if (!rowText.includes(searchFilter)) showRow = false;
            }
            
            // Show/hide row
            if (showRow) {
                row.show();
                visibleRows++;
            } else {
                row.hide();
            }
        });
        
        this.updateResultsDisplay(visibleRows);
        this.log('Applied filters. Visible rows:', visibleRows);
    }

    handleQuickFilter(event) {
        const button = $(event.target);
        const filterType = button.data('filter');
        
        // Reset all quick filter buttons
        $('.quick-filters button[data-filter]').removeClass('btn-primary btn-danger btn-success')
            .addClass('btn-outline-primary')
            .filter('[data-filter="overdue"]').addClass('btn-outline-danger')
            .filter('[data-filter="paid"]').addClass('btn-outline-success');
        
        // Update clicked button
        button.removeClass('btn-outline-primary btn-outline-danger btn-outline-success');
        
        // Apply specific filter
        switch(filterType) {
            case 'recent':
                button.addClass('btn-primary');
                this.filterRecent();
                break;
            case 'overdue':
                button.addClass('btn-danger');
                $('#statusFilter').val('overdue');
                this.resetOtherFilters();
                this.applyFilters();
                break;
            case 'paid':
                button.addClass('btn-success');
                $('#statusFilter').val('paid');
                this.resetOtherFilters();
                this.applyFilters();
                break;
        }
    }

    filterRecent() {
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth = currentDate.getMonth() + 1;
        let visibleRows = 0;
        
        $('#paymentTable tbody tr').each(function() {
            const row = $(this);
            if (row.attr('id') === 'no-results-message') return;
            
            const monthCell = row.find('td:first-child').text();
            const isRecent = monthCell.includes(currentYear.toString()) || 
                           monthCell.includes((currentYear - 1).toString());
            
            if (isRecent) {
                row.show();
                visibleRows++;
            } else {
                row.hide();
            }
        });
        
        this.resetOtherFilters();
        this.updateResultsDisplay(visibleRows);
    }

    resetOtherFilters() {
        $('#monthFilter').val('');
        $('#searchFilter').val('');
    }

    clearAllFilters() {
        $('#statusFilter').val('all');
        $('#monthFilter').val('');
        $('#searchFilter').val('');
        
        $('.quick-filters button[data-filter]').removeClass('btn-primary btn-danger btn-success')
            .addClass('btn-outline-primary')
            .filter('[data-filter="overdue"]').addClass('btn-outline-danger')
            .filter('[data-filter="paid"]').addClass('btn-outline-success');
        
        this.applyFilters();
    }

    updateResultsDisplay(visibleRows) {
        $('#visibleCount').text(visibleRows);
        
        // Show/hide no results message
        if (visibleRows === 0) {
            if ($('#no-results-message').length === 0) {
                $('#paymentTable tbody').append(`
                    <tr id="no-results-message">
                        <td colspan="6" class="text-center p-4">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No payments found</h5>
                            <p class="text-muted mb-0">
                                No payments found matching your criteria.
                                <br><small class="text-muted mt-2">Try adjusting your filters or search terms.</small>
                            </p>
                        </td>
                    </tr>
                `);
            }
            $('#no-results-message').show();
        } else {
            $('#no-results-message').hide();
        }
    }

    // UI Enhancement Functions
    bindTableEffects() {
        $('.table tbody tr').hover(
            function() {
                $(this).addClass('table-active');
            },
            function() {
                $(this).removeClass('table-active');
            }
        );
    }

    bindPaymentButtonEffects() {
        $('.btn-pay').click(function(e) {
            const button = $(this);
            button.addClass('btn-click-animation');
            
            // Add loading state
            const originalText = button.html();
            button.html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
            button.prop('disabled', true);
            
            // Simulate processing (remove this in production)
            setTimeout(() => {
                button.html(originalText);
                button.prop('disabled', false);
                button.removeClass('btn-click-animation');
            }, 2000);
        });
    }

    // Utility Functions
    checkForUpdates() {
        this.log('Checking for payment updates...');
        // Add AJAX call here to refresh payment status
        // Example:
        // $.ajax({
        //     url: 'api/check-payments.php',
        //     method: 'GET',
        //     success: (data) => this.updatePaymentStatus(data)
        // });
    }

    updatePaymentStatus(data) {
        // Update payment status based on server response
        this.log('Payment status updated:', data);
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('fr-MA', {
            style: 'currency',
            currency: 'MAD'
        }).format(amount);
    }

    showNotification(message, type = 'info') {
        const notification = $(`
            <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <strong>${type.charAt(0).toUpperCase() + type.slice(1)}!</strong> ${message}
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

    // Debug Functions
    log(...args) {
        if (this.debugMode) {
            console.log('[PaymentsDashboard]', ...args);
        }
    }

    enableDebugMode() {
        this.debugMode = true;
        this.log('Debug mode enabled');
    }

    // Export Functions
    exportToCSV() {
        const rows = [];
        const headers = ['Month/Year', 'Amount (MAD)', 'Status', 'Due Date', 'Transaction ID'];
        rows.push(headers);
        
        $('#paymentTable tbody tr:visible').each(function() {
            if ($(this).attr('id') !== 'no-results-message') {
                const row = [];
                $(this).find('td').each(function() {
                    row.push($(this).text().trim());
                });
                rows.push(row);
            }
        });
        
        const csvContent = rows.map(row => row.join(',')).join('\n');
        this.downloadFile(csvContent, 'payments-export.csv', 'text/csv');
    }

    downloadFile(content, fileName, contentType) {
        const blob = new Blob([content], { type: contentType });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = fileName;
        link.click();
        window.URL.revokeObjectURL(url);
    }
}

// Payment Analytics Helper
class PaymentAnalytics {
    static calculateTrends(payments) {
        // Calculate payment trends and statistics
        const trends = {
            monthlyAverage: 0,
            paymentFrequency: 0,
            completionRate: 0
        };
        
        // Add trend calculations here
        return trends;
    }
    
    static generateReport(payments) {
        // Generate detailed payment report
        return {
            summary: 'Payment report generated',
            data: payments
        };
    }
}

// Initialize when DOM is ready
$(document).ready(function() {
    // Initialize main dashboard
    window.paymentsDashboard = new PaymentsDashboard();
    
    // Add export functionality
    $('#exportBtn').on('click', () => {
        window.paymentsDashboard.exportToCSV();
    });
    
    // Enable debug mode if needed (remove in production)
    if (window.location.hostname === 'localhost') {
        window.paymentsDashboard.enableDebugMode();
    }
    
    console.log('Payments dashboard initialized successfully');
});
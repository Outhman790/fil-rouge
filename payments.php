<?php
session_start();

// Security Headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

require 'classes/user.class.php';

// Check if user is logged in and is a resident
if (!isset($_SESSION['resident_id']) || !isset($_SESSION['status']) || $_SESSION['status'] !== 'Resident') {
    header('location: login.php');
    exit();
}
// Include PageRenderer
require_once 'render/page-renderer.php';

// Page configuration
$pageConfig = [
    'title' => 'Payment Dashboard - Obuildings',
    'currentPage' => 'payments',
    'additionalCSS' => ['css/user-dashboard.css', 'css/payments.css'],
    'additionalMeta' => [],
    'additionalJS' => ['js/payments.js', 'js/pay-stripe.js']
];

// Render page header
PageRenderer::renderPageHeader($pageConfig['title'], $pageConfig['currentPage'], $pageConfig['additionalCSS'], $pageConfig['additionalMeta']);
?>
    <?php
    require_once 'classes/payments.class.php';
    require_once 'functions/extractMonthAndYear.php';
    require_once 'classes/admin.class.php';
    require 'functions/getUnpaidMonths.php';
    $paymentObj = new Payments();
    $adminObj = new Admin();
    $countPayments = $paymentObj->countPaymentsResident($_SESSION['resident_id']);
    $resident = $adminObj->selectResident($_SESSION['resident_id']);
    $joinedIn = extractMonthYear($resident['joinedIn']);

    $currentYear = intval(date("Y"));
    $currentMonth = intval(date("n"));

    // Calculate unpaid months using the new logic
    $latestPaymentObj = new User();
    $latestPayment = ($countPayments == 0) ? null : $latestPaymentObj->getLatestPayment($_SESSION['resident_id']);

    // Use the new calculateUnpaidMonths function
    $unpaidMonths = calculateUnpaidMonths($_SESSION['resident_id'], $joinedIn, $latestPayment);
    $nextPaymentMonth = getNextPaymentMonth($unpaidMonths);
    
    // Get user payments for display
    $paymentsObj = new User();
    $payments = $paymentsObj->getUserPayments($_SESSION['resident_id']);
    
    // Load application configuration
    $config = require_once 'config/app-config.php';
    
    // Create comprehensive payment data
    $allMonths = [];
    $paidMonths = [];
    $monthlyFee = $config['monthly_fee'];
    
    // Get all months from registration to current
    $registrationYear = (int)$joinedIn['year'];
    $registrationMonth = (int)$joinedIn['month'];
    $currentYear = (int)date('Y');
    $currentMonth = (int)date('n');
    
    // Create paid months array
    foreach ($payments as $payment) {
        $paidMonths[] = [
            'month' => (int)$payment['payment_month'],
            'year' => (int)$payment['payment_year'],
            'transaction_id' => $payment['transaction_id'],
            'status' => 'paid'
        ];
    }
    
    // Generate all months from registration to current
    for ($year = $registrationYear; $year <= $currentYear; $year++) {
        $startMonth = ($year == $registrationYear) ? $registrationMonth + 1 : 1;
        $endMonth = ($year == $currentYear) ? $currentMonth : 12;
        
        for ($month = $startMonth; $month <= $endMonth; $month++) {
            $isPaid = false;
            $transactionId = '';
            
            foreach ($paidMonths as $paidMonth) {
                if ($paidMonth['month'] == $month && $paidMonth['year'] == $year) {
                    $isPaid = true;
                    $transactionId = $paidMonth['transaction_id'];
                    break;
                }
            }
            
            $allMonths[] = [
                'month' => $month,
                'year' => $year,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'status' => $isPaid ? 'paid' : 'unpaid',
                'transaction_id' => $transactionId,
                'amount' => $monthlyFee,
                'due_date' => date('Y-m-d', mktime(0, 0, 0, $month, 5, $year)), // 5th of each month
                'is_overdue' => !$isPaid && (($year < $currentYear) || ($year == $currentYear && $month < $currentMonth))
            ];
        }
    }
    
    // Calculate comprehensive stats
    $totalUnpaidMonths = count($unpaidMonths);
    $totalAmountToPay = $totalUnpaidMonths * $monthlyFee;
    $totalPaidAmount = count($payments) * $monthlyFee;
    $overdueMonths = array_filter($allMonths, function($month) {
        return $month['is_overdue'];
    });
    $overdueAmount = count($overdueMonths) * $monthlyFee;
    
    // Payment completion percentage
    $totalRequiredMonths = count($allMonths);
    $completionPercentage = $totalRequiredMonths > 0 ? (count($payments) / $totalRequiredMonths) * 100 : 100;
    
    // Get recent payment trend (last 6 months)
    $recentPayments = array_slice(array_reverse($payments), 0, 6);
    
    // Debug information (can be removed in production)
    $debugInfo = [
        'resident_id' => $_SESSION['resident_id'],
        'total_months' => count($allMonths),
        'paid_months' => count($payments),
        'unpaid_months' => $totalUnpaidMonths,
        'completion_percentage' => round($completionPercentage, 1)
    ];
    echo "<script>console.log('Enhanced Payment Debug Info:', " . json_encode($debugInfo) . ");</script>";
    ?>
    
    <div class="container my-4">
        <div class="glass-container p-4">
            <div class="welcome-text text-center">
                <h1><i class="fas fa-credit-card me-3"></i>Payment Dashboard</h1>
                <p class="lead">Welcome back, <?php echo $_SESSION['fName'] . ' ' . $_SESSION['lName']; ?>!</p>
            </div>
            
            <!-- Enhanced Statistics Dashboard -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stat-card border-0 h-100 bg-gradient-success">
                        <div class="card-body text-center text-white">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <div class="display-4 fw-bold"><?php echo count($payments); ?></div>
                            <p class="mb-0">Paid Months</p>
                            <small class="text-white-50"><?php echo number_format($totalPaidAmount); ?> MAD</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stat-card border-0 h-100 bg-gradient-danger">
                        <div class="card-body text-center text-white">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <div class="display-4 fw-bold"><?php echo $totalUnpaidMonths; ?></div>
                            <p class="mb-0">Unpaid Months</p>
                            <small class="text-white-50"><?php echo number_format($totalAmountToPay); ?> MAD</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stat-card border-0 h-100 bg-gradient-warning">
                        <div class="card-body text-center text-white">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <div class="display-4 fw-bold"><?php echo count($overdueMonths); ?></div>
                            <p class="mb-0">Overdue Months</p>
                            <small class="text-white-50"><?php echo number_format($overdueAmount); ?> MAD</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stat-card border-0 h-100 bg-gradient-info">
                        <div class="card-body text-center text-white">
                            <i class="fas fa-percentage fa-2x mb-2"></i>
                            <div class="display-4 fw-bold"><?php echo round($completionPercentage); ?>%</div>
                            <p class="mb-0">Completion Rate</p>
                            <small class="text-white-50">Payment Progress</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Progress Bar -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Payment Progress</h6>
                        <small class="text-muted"><?php echo count($payments); ?> of <?php echo $totalRequiredMonths; ?> months paid</small>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-gradient-primary" role="progressbar" 
                             style="width: <?php echo $completionPercentage; ?>%" 
                             aria-valuenow="<?php echo $completionPercentage; ?>" 
                             aria-valuemin="0" aria-valuemax="100">
                            <?php echo round($completionPercentage, 1); ?>%
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Payment Actions -->
            <?php if (!empty($unpaidMonths)) : ?>
                <div class="card payment-status-card border-0 text-center p-4 mb-4">
                    <i class="fas fa-exclamation-triangle payment-icon mb-3"></i>
                    <h3 class="text-white">Payment Required</h3>
                    <p class="lead text-white">You have <strong><?php echo count($unpaidMonths) ?></strong> unpaid month<?php echo count($unpaidMonths) > 1 ? 's' : '' ?></p>
                    <span class="badge month-badge p-2 mb-3">
                        Next Payment: Month <?php echo $nextPaymentMonth ?>
                    </span>
                    <div class="payment-amount text-white"><?php echo number_format($totalAmountToPay); ?> MAD</div>
                    <div class="mt-3">
                        <a href="pay.php" class="btn btn-pay btn-lg px-4 me-3">
                            <i class="fas fa-credit-card me-2"></i>Pay Next Month
                        </a>
                        <?php if (count($unpaidMonths) > 1) : ?>
                            <a href="pay.php?pay_all=1" class="btn btn-success btn-lg px-4">
                                <i class="fas fa-credit-card me-2"></i>Pay All (<?php echo count($unpaidMonths) ?> months)
                            </a>
                        <?php endif; ?>
                    </div>
                    <small class="text-white-50 mt-2 d-block">
                        <?php if (count($overdueMonths) > 0) : ?>
                            <i class="fas fa-exclamation-circle me-1"></i>
                            <?php echo count($overdueMonths) ?> month<?php echo count($overdueMonths) > 1 ? 's' : '' ?> overdue
                        <?php endif; ?>
                    </small>
                </div>
            <?php else : ?>
                <div class="card payment-status-card success border-0 text-center p-4 mb-4">
                    <i class="fas fa-check-circle payment-icon mb-3"></i>
                    <h3 class="text-white">All Payments Complete!</h3>
                    <p class="lead text-white">You're all caught up with your payments</p>
                    <div class="payment-amount text-white">✓ Up to Date</div>
                    <small class="text-white-50 mt-2 d-block">
                        Next payment due: <?php echo date('F Y', mktime(0, 0, 0, $currentMonth + 1, 1, $currentYear)); ?>
                    </small>
                </div>
            <?php endif; ?>
            <!-- Enhanced Payment History Table -->
            <div class="card table-modern border-0">
                <div class="card-header table-header-solid">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">
                                <i class="fas fa-history me-2"></i>Complete Payment History
                            </h4>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="form-control form-control-sm" id="statusFilter">
                                        <option value="all">All Status</option>
                                        <option value="paid">Paid Only</option>
                                        <option value="unpaid">Unpaid Only</option>
                                        <option value="overdue">Overdue Only</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="month" class="form-control form-control-sm" id="monthFilter" 
                                           placeholder="Filter by month">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control form-control-sm" id="searchFilter" 
                                           placeholder="Search payments...">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="quick-filters">
                                            <button class="btn btn-sm btn-outline-primary me-1" data-filter="recent">Recent</button>
                                            <button class="btn btn-sm btn-outline-danger me-1" data-filter="overdue">Overdue</button>
                                            <button class="btn btn-sm btn-outline-success me-1" data-filter="paid">Paid</button>
                                            <button class="btn btn-sm btn-outline-secondary" id="clearFilters">Clear All</button>
                                        </div>
                                        <div class="search-counter">
                                            <small class="text-muted">Showing <span id="visibleCount">0</span> of <span id="totalCount">0</span> payments</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="paymentTable">
                            <thead>
                                <tr class="table-header-solid">
                                    <th class="border-0"><i class="fas fa-calendar me-2"></i>Month/Year</th>
                                    <th class="border-0"><i class="fas fa-info-circle me-2"></i>Status</th>
                                    <th class="border-0"><i class="fas fa-money-bill me-2"></i>Amount</th>
                                    <th class="border-0"><i class="fas fa-clock me-2"></i>Due Date</th>
                                    <th class="border-0"><i class="fas fa-receipt me-2"></i>Transaction ID</th>
                                    <th class="border-0"><i class="fas fa-cog me-2"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Sort months by year and month (most recent first)
                                usort($allMonths, function($a, $b) {
                                    if ($a['year'] == $b['year']) {
                                        return $b['month'] - $a['month'];
                                    }
                                    return $b['year'] - $a['year'];
                                });
                                
                                foreach ($allMonths as $month) : 
                                    $statusClass = $month['status'] == 'paid' ? 'success' : 
                                                  ($month['is_overdue'] ? 'danger' : 'warning');
                                    $statusText = $month['status'] == 'paid' ? 'Paid' : 
                                                 ($month['is_overdue'] ? 'Overdue' : 'Pending');
                                    $statusIcon = $month['status'] == 'paid' ? 'check' : 
                                                 ($month['is_overdue'] ? 'exclamation-triangle' : 'clock');
                                ?>
                                    <tr data-status="<?php echo $month['status']; ?>" 
                                        data-month="<?php echo $month['year'] . '-' . str_pad($month['month'], 2, '0', STR_PAD_LEFT); ?>" 
                                        data-overdue="<?php echo $month['is_overdue'] ? 'true' : 'false'; ?>">
                                        <td>
                                            <strong><?php echo $month['month_name'] . ' ' . $month['year']; ?></strong>
                                            <?php if ($month['is_overdue']) : ?>
                                                <br><small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Overdue</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $statusClass; ?>">
                                                <i class="fas fa-<?php echo $statusIcon; ?> me-1"></i><?php echo $statusText; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?php echo number_format($month['amount']); ?> MAD</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('M j, Y', strtotime($month['due_date'])); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if ($month['transaction_id']) : ?>
                                                <span class="badge badge-light transaction-id">
                                                    <?php echo substr($month['transaction_id'], 0, 15) . '...'; ?>
                                                </span>
                                            <?php else : ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($month['status'] == 'unpaid') : ?>
                                                <a href="pay.php?month=<?php echo $month['month']; ?>&year=<?php echo $month['year']; ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-credit-card me-1"></i>Pay
                                                </a>
                                            <?php else : ?>
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Completed
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts will be loaded by PageRenderer -->
    
    <!-- Enhanced JavaScript for advanced features -->
    <script>
        $(document).ready(function() {
            // Animate payment cards on load
            $('.payment-status-card').hide().fadeIn(1000);
            $('.stat-card').each(function(index) {
                $(this).delay(index * 200).fadeIn(500);
            });
            
            // Enhanced table filtering
            $('#statusFilter').on('change', function() {
                var filterValue = $(this).val();
                filterTable(filterValue, $('#monthFilter').val());
            });
            
            $('#monthFilter').on('change', function() {
                var filterValue = $(this).val();
                filterTable($('#statusFilter').val(), filterValue);
            });
            
            // Initialize counters
            var totalRows = $('#paymentTable tbody tr').length;
            $('#totalCount').text(totalRows);
            $('#visibleCount').text(totalRows);
            
            // Enhanced table filtering
            $('#statusFilter').on('change', function() {
                applyFilters();
            });
            
            $('#monthFilter').on('change', function() {
                applyFilters();
            });
            
            $('#searchFilter').on('input', function() {
                applyFilters();
            });
            
            // Quick filter buttons
            $('.quick-filters button[data-filter]').on('click', function() {
                var filterType = $(this).data('filter');
                
                // Remove active class from all quick filter buttons
                $('.quick-filters button[data-filter]').removeClass('btn-primary').addClass('btn-outline-primary');
                $('.quick-filters button[data-filter]').removeClass('btn-danger').addClass('btn-outline-danger');
                $('.quick-filters button[data-filter]').removeClass('btn-success').addClass('btn-outline-success');
                
                // Add active class to clicked button
                $(this).removeClass('btn-outline-primary btn-outline-danger btn-outline-success');
                
                if (filterType === 'recent') {
                    $(this).addClass('btn-primary');
                    $('#monthFilter').val('');
                    $('#statusFilter').val('all');
                    $('#searchFilter').val('');
                    filterRecent();
                } else if (filterType === 'overdue') {
                    $(this).addClass('btn-danger');
                    $('#statusFilter').val('overdue');
                    $('#monthFilter').val('');
                    $('#searchFilter').val('');
                    applyFilters();
                } else if (filterType === 'paid') {
                    $(this).addClass('btn-success');
                    $('#statusFilter').val('paid');
                    $('#monthFilter').val('');
                    $('#searchFilter').val('');
                    applyFilters();
                }
            });
            
            // Clear filters button
            $('#clearFilters').on('click', function() {
                $('#statusFilter').val('all');
                $('#monthFilter').val('');
                $('#searchFilter').val('');
                $('.quick-filters button[data-filter]').removeClass('btn-primary btn-danger btn-success')
                    .addClass('btn-outline-primary').filter('[data-filter="overdue"]').addClass('btn-outline-danger')
                    .filter('[data-filter="paid"]').addClass('btn-outline-success');
                applyFilters();
            });
            
            function filterRecent() {
                var currentDate = new Date();
                var currentYear = currentDate.getFullYear();
                var currentMonth = currentDate.getMonth() + 1;
                var visibleRows = 0;
                
                $('#paymentTable tbody tr').each(function() {
                    var row = $(this);
                    if (row.attr('id') === 'no-results-message') return;
                    
                    var monthData = row.data('month');
                    if (monthData) {
                        var parts = monthData.split('-');
                        var year = parseInt(parts[0]);
                        var month = parseInt(parts[1]);
                        
                        // Show if within last 3 months
                        var isRecent = false;
                        for (var i = 0; i < 3; i++) {
                            var checkMonth = currentMonth - i;
                            var checkYear = currentYear;
                            if (checkMonth <= 0) {
                                checkMonth += 12;
                                checkYear--;
                            }
                            if (year === checkYear && month === checkMonth) {
                                isRecent = true;
                                break;
                            }
                        }
                        
                        if (isRecent) {
                            row.show(200);
                            visibleRows++;
                        } else {
                            row.hide(200);
                        }
                    }
                });
                
                updateResultsMessage(visibleRows);
            }
            
            function applyFilters() {
                var statusFilter = $('#statusFilter').val();
                var monthFilter = $('#monthFilter').val();
                var searchFilter = $('#searchFilter').val().toLowerCase();
                var visibleRows = 0;
                
                $('#paymentTable tbody tr').each(function() {
                    var row = $(this);
                    
                    // Skip the no-results message row
                    if (row.attr('id') === 'no-results-message') return;
                    
                    var status = row.data('status');
                    var month = row.data('month');
                    var isOverdue = row.data('overdue');
                    var rowText = row.text().toLowerCase();
                    
                    var showRow = true;
                    
                    // Status filter logic
                    if (statusFilter !== 'all') {
                        if (statusFilter === 'paid' && status !== 'paid') {
                            showRow = false;
                        } else if (statusFilter === 'unpaid' && status !== 'unpaid') {
                            showRow = false;
                        } else if (statusFilter === 'overdue' && (isOverdue !== true && isOverdue !== 'true')) {
                            showRow = false;
                        }
                    }
                    
                    // Month filter logic
                    if (monthFilter && monthFilter.trim() !== '') {
                        if (month !== monthFilter) {
                            showRow = false;
                        }
                    }
                    
                    // Search filter logic
                    if (searchFilter && searchFilter.trim() !== '') {
                        if (rowText.indexOf(searchFilter) === -1) {
                            showRow = false;
                        }
                    }
                    
                    // Show/hide row with animation
                    if (showRow) {
                        row.show(200);
                        visibleRows++;
                    } else {
                        row.hide(200);
                    }
                });
                
                updateResultsMessage(visibleRows);
            }
            
            function updateResultsMessage(visibleRows) {
                // Update counter
                $('#visibleCount').text(visibleRows);
                
                // Show/hide "no results" message
                if (visibleRows === 0) {
                    if ($('#no-results-message').length === 0) {
                        $('#paymentTable tbody').append(
                            '<tr id="no-results-message">' +
                            '<td colspan="6" class="text-center text-muted py-4">' +
                            '<i class="fas fa-search fa-2x mb-2"></i>' +
                            '<br>No payments found matching your criteria.' +
                            '<br><small class="text-muted mt-2">Try adjusting your filters or search terms.</small>' +
                            '</td>' +
                            '</tr>'
                        );
                    }
                    $('#no-results-message').show();
                } else {
                    $('#no-results-message').hide();
                }
            }
            
            // Add hover effects to table rows
            $('.table tbody tr').hover(
                function() {
                    $(this).addClass('table-active');
                },
                function() {
                    $(this).removeClass('table-active');
                }
            );
            
            // Smooth scroll for pay button
            $('.btn-pay').click(function(e) {
                $(this).addClass('animate__animated animate__pulse');
            });
            
            // Add tooltips to overdue items
            // Bootstrap 5 tooltip initialization
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Auto-refresh payment status every 5 minutes
            setInterval(function() {
                // You can add AJAX call here to refresh payment status
                console.log('Checking for payment updates...');
            }, 300000);
        });
    </script>

<?php
    // Render page footer
    PageRenderer::renderPageFooter($pageConfig['additionalJS']);
?>
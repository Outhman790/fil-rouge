<?php
// Payments Renderer
// Handles rendering of payments dashboard components

class PaymentsRenderer
{
    public static function renderWelcomeSection($firstName, $lastName) {
?>
        <div class="welcome-text text-center">
            <h1><i class="fas fa-credit-card mr-3"></i>Payment Dashboard</h1>
            <p class="lead">Welcome back, <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>!</p>
        </div>
<?php
    }

    public static function renderStatsDashboard($stats) {
        extract($stats);
?>
        <!-- Enhanced Statistics Dashboard -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card border-0 h-100 bg-gradient-success">
                    <div class="card-body text-center text-white">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <div class="display-4 font-weight-bold"><?php echo $paidMonths; ?></div>
                        <p class="mb-0">Paid Months</p>
                        <small class="text-white-50"><?php echo number_format($totalPaidAmount); ?> MAD</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card border-0 h-100 bg-gradient-danger">
                    <div class="card-body text-center text-white">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <div class="display-4 font-weight-bold"><?php echo $unpaidMonths; ?></div>
                        <p class="mb-0">Unpaid Months</p>
                        <small class="text-white-50"><?php echo number_format($totalAmountToPay); ?> MAD</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card border-0 h-100 bg-gradient-warning">
                    <div class="card-body text-center text-white">
                        <i class="fas fa-clock fa-2x mb-2"></i>
                        <div class="display-4 font-weight-bold"><?php echo $overdueMonths; ?></div>
                        <p class="mb-0">Overdue Months</p>
                        <small class="text-white-50"><?php echo number_format($overdueAmount); ?> MAD</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card border-0 h-100 bg-gradient-info">
                    <div class="card-body text-center text-white">
                        <i class="fas fa-percentage fa-2x mb-2"></i>
                        <div class="display-4 font-weight-bold"><?php echo round($completionPercentage); ?>%</div>
                        <p class="mb-0">Completion Rate</p>
                        <small class="text-white-50">Payment Progress</small>
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    public static function renderProgressBar($completionPercentage, $paidMonths, $totalMonths) {
?>
        <!-- Payment Progress Bar -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Payment Progress</h6>
                    <small class="text-muted"><?php echo $paidMonths; ?> of <?php echo $totalMonths; ?> months paid</small>
                </div>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-gradient-primary" role="progressbar" 
                         style="width: 0%" 
                         aria-valuenow="<?php echo $completionPercentage; ?>" 
                         aria-valuemin="0" aria-valuemax="100">
                        <?php echo round($completionPercentage, 1); ?>%
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    public static function renderPaymentStatus($paymentStatus) {
        extract($paymentStatus);
        
        if (!empty($unpaidMonths)) :
?>
            <div class="card payment-status-card border-0 text-center p-4 mb-4">
                <i class="fas fa-exclamation-triangle payment-icon mb-3"></i>
                <h3 class="text-white">Payment Required</h3>
                <p class="lead text-white">You have <strong><?php echo count($unpaidMonths); ?></strong> unpaid month<?php echo count($unpaidMonths) > 1 ? 's' : ''; ?></p>
                <span class="badge month-badge p-2 mb-3">
                    Next Payment: Month <?php echo $nextPaymentMonth; ?>
                </span>
                <div class="payment-amount text-white"><?php echo number_format($totalAmountToPay); ?> MAD</div>
                <div class="mt-3">
                    <a href="pay.php" class="btn btn-pay btn-lg px-4 mr-3">
                        <i class="fas fa-credit-card mr-2"></i>Pay Next Month
                    </a>
                    <?php if (count($unpaidMonths) > 1) : ?>
                        <a href="pay.php?pay_all=1" class="btn btn-success btn-lg px-4">
                            <i class="fas fa-credit-card mr-2"></i>Pay All (<?php echo count($unpaidMonths); ?> months)
                        </a>
                    <?php endif; ?>
                </div>
                <small class="text-white-50 mt-2 d-block">
                    <?php if ($overdueCount > 0) : ?>
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?php echo $overdueCount; ?> month<?php echo $overdueCount > 1 ? 's' : ''; ?> overdue
                    <?php endif; ?>
                </small>
            </div>
<?php
        else :
?>
            <div class="card payment-status-card success border-0 text-center p-4 mb-4">
                <i class="fas fa-check-circle payment-icon mb-3"></i>
                <h3 class="text-white">All Payments Complete!</h3>
                <p class="lead text-white">You're all caught up with your payments</p>
                <div class="payment-amount text-white">✓ Up to Date</div>
                <small class="text-white-50 mt-2 d-block">
                    Next payment due: <?php echo $nextDueDate; ?>
                </small>
            </div>
<?php
        endif;
    }

    public static function renderFilterControls() {
?>
        <div class="filter-controls">
            <!-- Quick Filters -->
            <div class="quick-filters mb-3">
                <h6 class="mb-2">Quick Filters:</h6>
                <button class="btn btn-outline-primary" data-filter="recent">
                    <i class="fas fa-clock mr-1"></i>Recent
                </button>
                <button class="btn btn-outline-danger" data-filter="overdue">
                    <i class="fas fa-exclamation-triangle mr-1"></i>Overdue
                </button>
                <button class="btn btn-outline-success" data-filter="paid">
                    <i class="fas fa-check mr-1"></i>Paid
                </button>
                <button class="btn btn-outline-secondary" id="clearFilters">
                    <i class="fas fa-times mr-1"></i>Clear
                </button>
            </div>

            <!-- Advanced Filters -->
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
        </div>
<?php
    }

    public static function renderPaymentTable($allMonths) {
?>
        <!-- Enhanced Payment History Table -->
        <div class="card table-modern border-0">
            <div class="card-header table-header-solid">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-0">
                            <i class="fas fa-history mr-2"></i>Complete Payment History
                        </h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-outline-light btn-sm" id="exportBtn">
                            <i class="fas fa-download mr-1"></i>Export
                        </button>
                    </div>
                </div>
                
                <?php self::renderFilterControls(); ?>
                
                <div class="mt-2">
                    <small class="text-white-50">
                        Showing <span id="visibleCount">0</span> of <span id="totalCount">0</span> payments
                    </small>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="paymentTable">
                    <thead class="thead-light">
                        <tr>
                            <th><i class="fas fa-calendar mr-1"></i>Month/Year</th>
                            <th><i class="fas fa-money-bill mr-1"></i>Amount (MAD)</th>
                            <th><i class="fas fa-info-circle mr-1"></i>Status</th>
                            <th><i class="fas fa-clock mr-1"></i>Due Date</th>
                            <th><i class="fas fa-receipt mr-1"></i>Transaction ID</th>
                            <th><i class="fas fa-cogs mr-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allMonths as $month) : ?>
                            <?php self::renderPaymentRow($month); ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
<?php
    }

    private static function renderPaymentRow($month) {
        $statusClass = 'status-' . $month['status'];
        if ($month['is_overdue']) {
            $statusClass = 'status-overdue';
        }
?>
        <tr data-month="<?php echo $month['month']; ?>" data-year="<?php echo $month['year']; ?>">
            <td>
                <strong><?php echo $month['month_name'] . ' ' . $month['year']; ?></strong>
            </td>
            <td>
                <span class="font-weight-bold"><?php echo number_format($month['amount']); ?> MAD</span>
            </td>
            <td>
                <span class="status-badge <?php echo $statusClass; ?>">
                    <?php if ($month['is_overdue']) : ?>
                        <i class="fas fa-exclamation-triangle mr-1"></i>Overdue
                    <?php else : ?>
                        <i class="fas fa-<?php echo $month['status'] === 'paid' ? 'check' : 'clock'; ?> mr-1"></i>
                        <?php echo ucfirst($month['status']); ?>
                    <?php endif; ?>
                </span>
            </td>
            <td>
                <small class="text-muted"><?php echo date('M j, Y', strtotime($month['due_date'])); ?></small>
            </td>
            <td>
                <?php if (!empty($month['transaction_id'])) : ?>
                    <code><?php echo htmlspecialchars($month['transaction_id']); ?></code>
                <?php else : ?>
                    <span class="text-muted">-</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if ($month['status'] === 'unpaid') : ?>
                    <a href="pay.php?month=<?php echo $month['month']; ?>&year=<?php echo $month['year']; ?>" 
                       class="btn btn-sm btn-outline-success">
                        <i class="fas fa-credit-card mr-1"></i>Pay
                    </a>
                <?php else : ?>
                    <span class="text-muted">-</span>
                <?php endif; ?>
            </td>
        </tr>
<?php
    }

    public static function renderPageContent($data) {
        extract($data);
?>
        <div class="container my-4">
            <div class="glass-container p-4">
                <?php self::renderWelcomeSection($firstName, $lastName); ?>
                
                <?php self::renderStatsDashboard($stats); ?>
                
                <?php self::renderProgressBar($stats['completionPercentage'], $stats['paidMonths'], $stats['totalMonths']); ?>

                <?php self::renderPaymentStatus($paymentStatus); ?>

                <?php self::renderPaymentTable($allMonths); ?>
            </div>
        </div>
<?php
    }
}
?>
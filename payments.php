<?php
session_start();
require 'classes/user.class.php';

// Check if user is logged in and is a resident
if (!isset($_SESSION['resident_id']) || !isset($_SESSION['status']) || $_SESSION['status'] !== 'Resident') {
    header('location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Dashboard - Obuildings</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="css/user-dashboard.css">
</head>

<body class="user-dashboard">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-modern">
        <div class="container">
            <a class="navbar-brand" href="homepage.php">
                <i class="fas fa-building mr-2"></i>Obuildings
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="homepage.php">
                            <i class="fas fa-home mr-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="payments.php">
                            <i class="fas fa-credit-card mr-1"></i>Payments<span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="announces.php">
                            <i class="fas fa-bullhorn mr-1"></i>Announces
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
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

    // Debug information (can be removed in production)
    $debugInfo = [
        'resident_id' => $_SESSION['resident_id'],
        'joined_in' => $joinedIn,
        'latest_payment' => $latestPayment,
        'unpaid_months' => $unpaidMonths,
        'next_payment_month' => $nextPaymentMonth
    ];
    echo "<script>console.log('Payment Debug Info:', " . json_encode($debugInfo) . ");</script>";
    ?>
    
    <div class="container my-4">
        <div class="glass-container p-4">
            <div class="welcome-text text-center">
                <h1><i class="fas fa-credit-card mr-3"></i>Payment Dashboard</h1>
                <p class="lead">Welcome back, <?php echo $_SESSION['fName'] . ' ' . $_SESSION['lName']; ?>!</p>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card stat-card border-0 h-100">
                        <div class="card-body text-center">
                            <div class="display-4 text-primary font-weight-bold"><?php echo count($payments); ?></div>
                            <p class="text-muted mb-0">Total Payments</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card border-0 h-100">
                        <div class="card-body text-center">
                            <div class="display-4 text-<?php echo count($unpaidMonths) > 0 ? 'danger' : 'success'; ?> font-weight-bold"><?php echo count($unpaidMonths); ?></div>
                            <p class="text-muted mb-0">Unpaid Months</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card border-0 h-100">
                        <div class="card-body text-center">
                            <div class="display-4 text-info font-weight-bold">300</div>
                            <p class="text-muted mb-0">Monthly Fee (MAD)</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($unpaidMonths)) : ?>
                <div class="card payment-status-card border-0 text-center p-4 mb-4">
                    <i class="fas fa-exclamation-triangle payment-icon mb-3"></i>
                    <h3 class="text-white">Payment Required</h3>
                    <p class="lead text-white">You have <strong><?php echo count($unpaidMonths) ?></strong> unpaid month<?php echo count($unpaidMonths) > 1 ? 's' : '' ?></p>
                    <span class="badge month-badge p-2 mb-3">
                        Next Payment: Month <?php echo $nextPaymentMonth ?>
                    </span>
                    <div class="payment-amount text-white">300 MAD</div>
                    <a href="pay.php" class="btn btn-pay btn-lg px-5">
                        <i class="fas fa-credit-card mr-2"></i>Pay Now
                    </a>
                </div>
            <?php else : ?>
                <div class="card payment-status-card success border-0 text-center p-4 mb-4">
                    <i class="fas fa-check-circle payment-icon mb-3"></i>
                    <h3 class="text-white">All Payments Complete!</h3>
                    <p class="lead text-white">You're all caught up with your payments</p>
                    <div class="payment-amount text-white">✓ Paid</div>
                </div>
            <?php endif; ?>
            <div class="card table-modern border-0">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <h4 class="mb-0">
                        <i class="fas fa-history mr-2"></i>Payment History
                    </h4>
                </div>
                <?php if (!empty($payments)) : ?>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                                    <th class="border-0"><i class="fas fa-receipt mr-2"></i>Transaction ID</th>
                                    <th class="border-0"><i class="fas fa-calendar mr-2"></i>Payment Date</th>
                                    <th class="border-0"><i class="fas fa-money-bill mr-2"></i>Amount</th>
                                    <th class="border-0"><i class="fas fa-check-circle mr-2"></i>Status</th>
                                </tr>
                            </thead>
                        <tbody>
                            <?php foreach ($payments as $payment) : ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-light transaction-id">
                                            <?php echo substr($payment['transaction_id'], 0, 20) . '...'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="payment-date">
                                            <?php 
                                            $monthNames = [
                                                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                                                5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                                                9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
                                            ];
                                            echo $monthNames[$payment['payment_month']] . ' ' . $payment['payment_year'];
                                            ?>
                                        </span>
                                    </td>
                                    <td><strong>300 MAD</strong></td>
                                    <td>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i>Paid
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Payment History</h4>
                        <p class="text-muted">You haven't made any payments yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Custom JavaScript for animations -->
    <script>
        $(document).ready(function() {
            // Animate payment cards on load
            $('.payment-status-card').hide().fadeIn(1000);
            $('.stat-card').each(function(index) {
                $(this).delay(index * 200).fadeIn(500);
            });
            
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
            $('.pay-button').click(function(e) {
                $(this).addClass('animate__animated animate__pulse');
            });
        });
    </script>
</body>

</html>
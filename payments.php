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
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .payment-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            padding: 2rem;
        }
        .payment-status-card {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            border: none;
            border-radius: 15px;
            color: white;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        .payment-status-card.success {
            background: linear-gradient(135deg, #56ab2f, #a8e6cf);
            box-shadow: 0 10px 30px rgba(86, 171, 47, 0.3);
        }
        .payment-status-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        .payment-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        .payment-amount {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 1rem 0;
        }
        .pay-button {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 1rem 3rem;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        .pay-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
            color: white;
            text-decoration: none;
        }
        .payments-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .table-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 1.5rem;
            margin: 0;
            border-radius: 15px 15px 0 0;
        }
        .table thead th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
        }
        .table tbody tr {
            transition: all 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9ff;
            transform: scale(1.01);
        }
        .table tbody td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid #e9ecef;
        }
        .transaction-id {
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 0.5rem;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        .payment-date {
            font-weight: 600;
            color: #667eea;
        }
        .navbar {
            background: linear-gradient(135deg, #2c3e50, #34495e) !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            transform: translateY(-2px);
        }
        .month-badge {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: bold;
            display: inline-block;
            margin: 0.5rem 0;
        }
        .welcome-text {
            color: #2c3e50;
            margin-bottom: 2rem;
        }
        .stats-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            flex: 1;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
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
    
    <div class="container">
        <div class="payment-container">
            <div class="welcome-text text-center">
                <h1><i class="fas fa-credit-card mr-3"></i>Payment Dashboard</h1>
                <p class="lead">Welcome back, <?php echo $_SESSION['fName'] . ' ' . $_SESSION['lName']; ?>!</p>
            </div>
            
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($payments); ?></div>
                    <div class="stat-label">Total Payments</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo count($unpaidMonths); ?></div>
                    <div class="stat-label">Unpaid Months</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">300 MAD</div>
                    <div class="stat-label">Monthly Fee</div>
                </div>
            </div>

            <?php if (!empty($unpaidMonths)) : ?>
                <div class="payment-status-card">
                    <i class="fas fa-exclamation-triangle payment-icon"></i>
                    <h3>Payment Required</h3>
                    <p class="lead">You have <strong><?php echo count($unpaidMonths) ?></strong> unpaid month<?php echo count($unpaidMonths) > 1 ? 's' : '' ?></p>
                    <div class="month-badge">
                        Next Payment: Month <?php echo $nextPaymentMonth ?>
                    </div>
                    <div class="payment-amount">300 MAD</div>
                    <a href="pay.php" class="pay-button">
                        <i class="fas fa-credit-card mr-2"></i>Pay Now
                    </a>
                </div>
            <?php else : ?>
                <div class="payment-status-card success">
                    <i class="fas fa-check-circle payment-icon"></i>
                    <h3>All Payments Complete!</h3>
                    <p class="lead">You're all caught up with your payments</p>
                    <div class="payment-amount">✓ Paid</div>
                </div>
            <?php endif; ?>
            <div class="payments-table">
                <h2 class="table-header">
                    <i class="fas fa-history mr-2"></i>Payment History
                </h2>
                <?php if (!empty($payments)) : ?>
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th><i class="fas fa-receipt mr-2"></i>Transaction ID</th>
                                <th><i class="fas fa-calendar mr-2"></i>Payment Date</th>
                                <th><i class="fas fa-money-bill mr-2"></i>Amount</th>
                                <th><i class="fas fa-check-circle mr-2"></i>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment) : ?>
                                <tr>
                                    <td>
                                        <span class="transaction-id">
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
                <?php else : ?>
                    <div class="text-center p-5">
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
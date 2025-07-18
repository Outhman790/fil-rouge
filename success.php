<?php
session_start();
require_once "classes/db.class.php";
require_once "classes/StripeHelper.class.php";
require_once 'functions/getUnpaidMonths.php';
require_once "classes/payments.class.php";
require_once 'classes/user.class.php';
require_once 'functions/extractMonthAndYear.php';
require_once 'classes/admin.class.php';

$stripeHelper = new StripeHelper();
$sessionId = $_GET['session_id'];
$checkoutSession = $stripeHelper->getSession($sessionId);

// Get user's registration and payment info
$adminObj = new Admin();
$resident = $adminObj->selectResident($_SESSION['resident_id']);
$joinedIn = extractMonthYear($resident['joinedIn']);

$paymentObj = new Payments();
$countPayments = $paymentObj->countPaymentsResident($_SESSION['resident_id']);

$latestPaymentObj = new User();
$latestPayment = ($countPayments == 0) ? null : $latestPaymentObj->getLatestPayment($_SESSION['resident_id']);

// Calculate unpaid months and get the next payment month
$unpaidMonths = calculateUnpaidMonths($_SESSION['resident_id'], $joinedIn, $latestPayment);
$nextPaymentMonth = getNextPaymentMonth($unpaidMonths);

if ($nextPaymentMonth !== null) {
    // Determine the year for this payment
    $currentYear = (int)date('Y');
    $currentMonth = (int)date('n');
    
    // If paying for a month that's already passed this year, or future months, use current year
    // If paying for months from previous years, we need to determine the correct year
    $paymentYear = $currentYear;
    
    // If the next payment month is greater than current month, it could be from previous year
    if ($latestPayment && $nextPaymentMonth > $currentMonth) {
        // Check if this month should be from previous year
        $lastPaidYear = (int)$latestPayment['payment_year'];
        if ($lastPaidYear < $currentYear) {
            $paymentYear = $lastPaidYear;
        }
    }
    
    // For new users, if paying for a future month in registration year
    if (!$latestPayment && $nextPaymentMonth > $currentMonth && $joinedIn['year'] < $currentYear) {
        $paymentYear = $joinedIn['year'];
    }
    
    // Add the payment
    $paymentsInsert = new Payments();
    $success = $paymentsInsert->addPayment($nextPaymentMonth, $paymentYear, $checkoutSession->payment_intent, $_SESSION['resident_id']);
    
    // Debug info
    $debugInfo = [
        'next_payment_month' => $nextPaymentMonth,
        'payment_year' => $paymentYear,
        'transaction_id' => $checkoutSession->payment_intent,
        'success' => $success
    ];
    echo "<script>console.log('Payment Processing:', " . json_encode($debugInfo) . ");</script>";
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon">
    <!-- Animate.css for animation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap JS (after jQuery) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <title>Payment made successfully</title>
</head>

<body>
    <!-- Payment Success Modal -->
    <div class="modal fade" id="paymentSuccessModal" tabindex="-1" role="dialog" aria-labelledby="paymentSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-success text-white border-0 d-flex flex-column align-items-center" style="background: linear-gradient(90deg, #23e323 0%, #0d6efd 100%);">
                    <div class="rounded-circle bg-white mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                        <i class="fas fa-check-circle fa-3x text-success animate__animated animate__bounceIn"></i>
                    </div>
                    <h4 class="modal-title w-100 text-center font-weight-bold" id="paymentSuccessModalLabel">Payment Successful!</h4>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="lead mb-2">Thank you for your payment for Month <?php echo $nextPaymentMonth; ?>.</p>
                    <div class="mb-3">
                        <span class="badge badge-success p-2" style="font-size: 1rem;">Transaction ID:</span>
                        <span class="font-weight-bold text-dark ml-2" style="word-break: break-all;"> <?php echo $checkoutSession->payment_intent; ?> </span>
                    </div>
                    <a href="homepage.php" class="btn btn-lg btn-primary mt-2 px-5 shadow-sm">Back to Homepage</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#paymentSuccessModal').modal('show');
        });
    </script>
</body>

</html>
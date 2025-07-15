<?php
echo '<br>';
require_once "classes/db.class.php";
require_once "classes/StripeHelper.class.php";
require_once 'functions/getUnpaidMonths.php';
require_once "classes/payments.class.php";
require_once 'classes/user.class.php';

$stripeHelper = new StripeHelper();
$sessionId = $_GET['session_id'];
$paymentsInsert = new Payments();
$checkoutSession = $stripeHelper->getSession($sessionId);
$latestPaymentObj = new User();
$latestPayment = $latestPaymentObj->getLatestPayment($_SESSION['resident_id']);
$currentMonth = (int)date('n');
require_once 'functions/extractMonthAndYear.php';
require_once 'classes/admin.class.php';
$adminObj = new Admin();
$resident = $adminObj->selectResident($_SESSION['resident_id']);

$joinedIn = extractMonthYear($resident['joinedIn']);
if (!isset($latestPayment['payment_year'])) {
    $latestPayment['payment_year'] = $joinedIn['year'];
}

if (!isset($latestPayment['payment_year'])) $latestPayment['payment_year'] = 0;

$currentYear = intval(date("Y"));
if (isset($latestPayment)) {
    if ($currentYear - $latestPayment['payment_year']  == 1) {
        $UnpaidYears = getUnpaidYears($currentYear, $joinedIn['month'], $latestPayment['payment_year'] + 1);
    } else {
        $UnpaidYears = getUnpaidYears($currentYear, $joinedIn['month'], $latestPayment['payment_year']);
    }
}



$paymentObj = new Payments();
$countPayments = $paymentObj->countPaymentsResident($_SESSION['resident_id']);

$a = json_encode($countPayments);
echo "<script>console.log(JSON.parse('$a'));</script>";
if ($countPayments == 0) :
    $UnpaidYears = getUnpaidYears($currentYear, $joinedIn['month'], $joinedIn['year']);
    $unpaidMonths = getUnpaidMonthsOfAllUnpaidYears($joinedIn['month'], $UnpaidYears, true);
    $paymentsInsert->addPayment($unpaidMonths[0], $UnpaidYears[0], $checkoutSession->payment_intent, $_SESSION['resident_id']);
else :
    $UnpaidYears = getUnpaidYears($currentYear, $joinedIn['month'], $latestPayment['payment_year']);
    $unpaidMonths = getUnpaidMonthsOfAllUnpaidYears($latestPayment['payment_month'], getUnpaidYears($currentYear, $joinedIn['month'], $latestPayment['payment_year']), false);
    $jsontest = json_encode($latestPayment['payment_year']);
    $paymentsInsert->addPayment($unpaidMonths[0], $UnpaidYears[0], $checkoutSession->payment_intent, $_SESSION['resident_id']);
    $latestPayment = $latestPaymentObj->getLatestPayment($_SESSION['resident_id']);
endif;
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
                    <p class="lead mb-2">Thank you for your payment.</p>
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
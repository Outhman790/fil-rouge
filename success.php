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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js">
    </script>
    <title>Payment made successfully</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body text-center" style="outline: 2px solid #23e323">
                        <h5 class="card-title text-center">Payment made successfully</h5>
                        <p>Transaction ID: <?php echo $checkoutSession->payment_intent; ?> </p>
                        <a href="homepage.php" class="btn btn-primary">Back to Homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
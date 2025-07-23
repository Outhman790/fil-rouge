<?php
session_start();
require_once 'classes/payments.class.php';
require_once 'classes/admin.class.php';
require_once 'classes/user.class.php';
require_once 'functions/extractMonthAndYear.php';
require_once 'functions/getUnpaidMonths.php';

if (isset($_SESSION['status'])  && $_SESSION['status'] === 'Admin') :
    header('location: index.php');
else :
    // Get user's registration info and payment history
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
    
    // Check if user wants to pay all unpaid months
    $payAll = isset($_GET['pay_all']) && $_GET['pay_all'] == '1';
    $specificMonth = isset($_GET['month']) ? (int)$_GET['month'] : null;
    $specificYear = isset($_GET['year']) ? (int)$_GET['year'] : null;
    
    // Determine if user has unpaid months
    $hasUnpaidMonths = !empty($unpaidMonths);
    
    // Calculate payment details
    $monthlyFee = 300;
    if ($payAll) {
        $totalAmount = count($unpaidMonths) * $monthlyFee;
        $paymentDescription = count($unpaidMonths) . ' months';
    } else {
        $totalAmount = $monthlyFee;
        $paymentDescription = '1 month';
    }
    
    // Get month name for display
    $monthNames = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
    
    if ($specificMonth && $specificYear) {
        $paymentMonthName = $monthNames[$specificMonth] . ' ' . $specificYear;
    } elseif ($payAll) {
        $paymentMonthName = 'All Unpaid Months';
    } else {
        $paymentMonthName = $nextPaymentMonth ? $monthNames[$nextPaymentMonth] : 'Current';
    }
?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="icon" href="assets/img/logo.png" type="image/x-icon">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <title>Make a payment</title>
        <style>
            .required {
                color: red;
                font-weight: bold
            }
        </style>
    </head>

    <body>
        <?php if ($hasUnpaidMonths) : ?>
            <div class="container">
                <div class="row">
                    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                        <div class="card card-signin my-5">
                            <div class="card-body">
                                <center>
                                    <img src="assets/img/logo.png" />
                                </center> <br />
                                <h5 class="card-title text-center mb-4">Payment for <?php echo $paymentMonthName ?></h5>
                                
                                <?php if ($payAll) : ?>
                                    <div class="alert alert-info text-center mb-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Bulk Payment:</strong> You are paying for <?php echo count($unpaidMonths) ?> months
                                    </div>
                                <?php endif; ?>
                                
                                <form action="./classes/checkout.class.php" method="post">
                                    <input type="hidden" name="pay_all" value="<?php echo $payAll ? '1' : '0' ?>">
                                    <input type="hidden" name="unpaid_months" value="<?php echo htmlspecialchars(json_encode($unpaidMonths)) ?>">
                                    
                                    <div class="form-group">
                                        <label for="fullName">Name <span class="required">*</span></label>
                                        <input type="text" name="fullName" id="fullName" class="form-control" placeholder="full Name" value="<?php echo $_SESSION['fName'] . ' ' . $_SESSION['lName'] ?>" readonly required />
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email <span class="required">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="<?php echo $_SESSION['email'] ?>" readonly required />
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username <span class="required">*</span></label>
                                        <input type="text" name="username" id="username" class="form-control" value="<?php echo $_SESSION['username'] ?>" placeholder="Contact" maxlength="10" readonly required />
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Payment Description</label>
                                        <input type="text" name="description" id="description" class="form-control" value="<?php echo $paymentDescription; ?>" readonly />
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Total Amount <span class="required">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount" value="<?php echo $totalAmount; ?>" readonly required />
                                            <span class="input-group-text">MAD</span>
                                        </div>
                                        <?php if ($payAll) : ?>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-calculator me-1"></i>
                                                <?php echo count($unpaidMonths) ?> months × <?php echo $monthlyFee ?> MAD = <?php echo $totalAmount ?> MAD
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    <button type="submit" name="payNowBtn" class="btn btn-lg btn-primary btn-block">
                                        <i class="fas fa-credit-card me-2"></i>Pay <?php echo $totalAmount ?> MAD
                                        <span class="fa fa-angle-right ms-2"></span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        else : ?>
            <div class="container">
                <div class="row mt-5">
                    <div class="col-md-6 offset-md-3">
                        <div class="alert alert-info text-center" role="alert">
                            <h4 class="alert-heading">All Payments Up to Date!</h4>
                            <p class="mb-0">Your next payment will be due in <?php echo $monthNames[date('n') + 1] ?? 'the next month' ?>.</p>
                        </div>
                        <div class="text-center">
                            <a href="homepage.php" class="btn btn-primary">Back to Homepage</a>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        endif;
        ?>
    </body>

    </html>
<?php
endif;
?>
<?php
require 'classes/user.class.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="homepage.php">Obuildings</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="homepage.php">Home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="payments.php">Payments<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="announces.php">Announces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
    </nav>
    </div>
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
    // If resident didn't pay any month he'll pay from the month he joined until now
    if ($countPayments == 0) :
        if (getUnpaidYears($currentYear, $joinedIn['month'], $joinedIn['year']) == NULL) echo "NULL";
        $unpaidMonths = getUnpaidMonthsOfAllUnpaidYears($joinedIn['month'], getUnpaidYears($currentYear, $joinedIn['month'], $joinedIn['year']), true);
        $a = json_encode($unpaidMonths);
        echo "<script>console.log(JSON.parse('$a'));</script>";
        $b = json_encode($_SESSION['resident_id']);
        echo "<script>console.log(JSON.parse('$b'));</script>";
    ?>
        <div class="alert alert-danger text-center" role="alert">
            You didn't pay
            <?php echo count($unpaidMonths) ?>
            months.
        </div>
        <!-- Tell the user to pay from the first month he didn't pay -->
        <a href="pay.php" class="btn btn-primary btn-block mx-auto mt-3" style="width: 200px;">Pay Month
            <?php if (count($unpaidMonths) > 12) echo $unpaidMonths[0];
            else echo $unpaidMonths[0]  ?></a>
        <?php
    else :
        $latestPaymentObj = new User();
        $latestPayment = $latestPaymentObj->getLatestPayment($_SESSION['resident_id']);
        $currentMonth = (int)date('n');
        // if there's two unpaid years ( current year and previous one )
        if ($currentYear - $latestPayment['payment_year']  == 1) {

            $UnpaidYears = getUnpaidYears($currentYear, $joinedIn['month'], $latestPayment['payment_year']);
        }
        // ======== !!!!!!!! =========
        elseif ($currentYear - $latestPayment['payment_year']  == 0) {
            $UnpaidYears = [$currentYear];
        }
        // 
        else {
            $UnpaidYears = getUnpaidYears($currentYear, $joinedIn['month'], $latestPayment['payment_year']);
        }
        // See if the latest payment month it's not the current month
        if (($latestPayment['payment_month'] != $currentMonth && $latestPayment['payment_year'] != $currentYear) || ($latestPayment['payment_month'] == $currentMonth && $latestPayment['payment_year'] != $currentYear) || ($latestPayment['payment_month'] != $currentMonth && $latestPayment['payment_year'] == $currentYear)) :
            $unpaidMonths = getUnpaidMonthsOfAllUnpaidYears($latestPayment['payment_month'], $UnpaidYears, false);
        ?>
            <div class="alert alert-danger text-center" role="alert">
                You didn't pay
                <?php echo count($unpaidMonths) ?>
                months.
            </div>
            <a href="pay.php" class="btn btn-primary btn-block mx-auto mt-3" style="width: 200px;">Pay Month
                <?php echo $unpaidMonths[0]  ?></a>
        <?php endif; ?>
        <!-- See if all months are paid -->
        <?php if (count(getUnpaidMonthsOfAllUnpaidYears($latestPayment['payment_month'], $UnpaidYears, false)) == 0) :

            $jsontest = json_encode(getUnpaidMonthsOfAllUnpaidYears($latestPayment['payment_month'], $UnpaidYears, false));
            echo "<script>console.log('See if all months are paid condition: ');</script>";
            echo "<script>console.log(JSON.parse('$jsontest'));</script>";
        ?>
            <div class="alert alert-success text-center" role="alert">
                You paid all the months.
            </div>
    <?php
        endif;
    endif; ?>
    <div class="container mt-4">
        <?php
        $paymentsObj = new User();
        $payments = $paymentsObj->getUserPayments($_SESSION['resident_id']);
        ?>
        <h2>All your payments</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment) : ?>
                    <tr>
                        <td><?php echo $payment['transaction_id']; ?></td>
                        <td><?php if ($payment['payment_month'] < 10) {
                                echo '0' . $payment['payment_month'] . '-' . $payment['payment_year'];
                            } else {
                                echo $payment['payment_month'] . '-' . $payment['payment_year'];
                            } ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
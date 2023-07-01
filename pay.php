<?php
session_start();
require_once 'classes/payments.class.php';
if (isset($_SESSION['status'])  && $_SESSION['status'] === 'Admin') :
    header('location: index.php');
else :
    $paymentObj = new Payments();
    $iscurrentPaid = $paymentObj->hasPaidCurrentMonth($_SESSION['resident_id']);
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js">
        </script>
        <title>Make a payment</title>
        <style>
            .required {
                color: red;
                font-weight: bold
            }
        </style>
    </head>

    <body>
        <?php if (!$iscurrentPaid) : ?>
            <div class="container">
                <div class="row">
                    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                        <div class="card card-signin my-5">
                            <div class="card-body">
                                <center>
                                    <img src="assets/img/logo.png" />
                                </center> <br />
                                <h5 class="card-title text-center">Payment of month <?php echo date("F") ?></h5>
                                <form action="./classes/checkout.class.php" method="post">
                                    <div class="form-group">
                                        <label for="fullName">Name <span class="required">*</span></label>
                                        <input type="text" name="fullName" id="fullName" class="form-control" placeholder="full Name" value=<?php echo $_SESSION['fName'] . ' ' . $_SESSION['lName'] ?> readonly required />
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email <span class="required">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" value=<?php echo $_SESSION['email'] ?> readonly required />
                                    </div>
                                    <div class="form-group">
                                        <label for="username">Username <span class="required">*</span></label>
                                        <input type="text" name="username" id="username" class="form-control" value=<?php echo $_SESSION['username'] ?> placeholder="Contact" maxlength="10" readonly required />
                                    </div>
                                    <div class="form-group">
                                        <label for="amount">Fee Amount <span class="required">*</span></label>
                                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount" value="300" readonly required />
                                    </div>
                                    <button type="submit" name="payNowBtn" class="btn btn-lg btn-primary btn-block">Pay Now
                                        <span class="fa fa-angle-right"></span></button>
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
                            <h4 class="alert-heading">You've already paid this month.</h4>
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
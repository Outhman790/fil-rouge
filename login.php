<?php
session_start();

if (isset($_SESSION['status']) && $_SESSION['status'] === 'Admin') :
    header('location: index.php');
elseif (isset($_SESSION['status']) && $_SESSION['status'] === 'Resident') :
    header('location: homepage.php');
else :
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - Obuildings Management</title>
        <link href="css/styles.css" rel="stylesheet" />
        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Include Bootstrap JavaScript -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="css/login-styles.css" rel="stylesheet" />
    </head>

    <body>
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>

        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <h3><i class="fas fa-building me-2"></i>Obuildings</h3>
                    <p class="subtitle">Management System</p>
                </div>

                <div class="login-body">
                    <form method="POST" action="includes/login.inc.php">
                        <div class="form-floating">
                            <input class="form-control" id="inputEmail" type="email" placeholder="name@example.com" name="login-email" required />
                            <label for="inputEmail">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                        </div>

                        <div class="form-floating">
                            <input class="form-control" id="inputPassword" type="password" placeholder="Password" name="login-password" required />
                            <label for="inputPassword">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                        </div>

                        <button class="btn btn-login" type="submit" name="login">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </form>
                </div>

                <div class="login-footer">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-muted">© 2023 Obuildings</div>
                        <div>
                            <a href="#" class="me-3">Privacy</a>
                            <a href="#">Terms</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LOGIN MODAL RESPONSE-->
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" id="errorModal">
                    <div class="modal-header bg-gradient-primary text-white">
                        <h5 class="modal-title" id="errorModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i>Error Message
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="errorMessage" class="mb-0"></p>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>

    </html>
<?php
endif;
// Retrieve the URL parameter value
if (isset($_GET['error'])) {
    $error = $_GET['error'];

    // Check the value and set the corresponding message
    if ($error == 'stmtfailed') {
        $message = "Statement execution failed.";
    } else if ($error == 'wrongpassword') {
        $message = "Wrong password";
    } else if ($error == 'usernotfound') {
        $message = "User doesn't exists";
    } else {
        // Default message if the parameter doesn't match any specific value
        $message = "An error occurred.";
    }
    // JavaScript code to display the message in the modal
    echo '<script>';
    echo 'document.getElementById("errorMessage").textContent = "' . $message . '";';
    echo '$("#errorModal").modal("show");';
    echo '</script>';
}
?>
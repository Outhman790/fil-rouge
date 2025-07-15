<?php
session_start();

if (isset($_SESSION['resident_id'])) :
    if (isset($_SESSION['status'])  && $_SESSION['status'] === 'Admin') :
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="utf-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
            <meta name="description" content="" />
            <meta name="author" content="" />
            <title>Dashboard - Admin</title>
            <linkindex href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
            <link href="css/styles.css" rel="stylesheet" />


            <!-- Include jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- Include Bootstrap CSS -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
            <!-- Include Font Awesome -->
            <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        </head>

        <body class="sb-nav-fixed">
            <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
                <!-- Navbar Brand-->
                <a class="navbar-brand ps-3" href="index.php">Obuildings</a>
                <!-- Sidebar Toggle-->
                <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
                    <i class="fas fa-bars"></i>
                </button>
                <!-- Navbar-->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user fa-fw"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#!">Settings</a></li>
                            <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <div id="layoutSidenav">
                <div id="layoutSidenav_nav">
                    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                        <div class="sb-sidenav-menu">
                            <div class="nav">
                                <div class="sb-sidenav-menu-heading">Core</div>
                                <a class="nav-link" href="index.php">
                                    <div class="sb-nav-link-icon">
                                        <i class="fas fa-tachometer-alt"></i>
                                    </div>
                                    Dashboard
                                </a>
                                <div class="sb-sidenav-menu-heading">Interface</div>

                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages" style="margin-left:0.75rem">
                                    <a class="nav-link" href="add-expense.php">
                                        Add Expense
                                    </a>
                                    <a class="nav-link" href="add-announce.php">
                                        Add Announce
                                    </a>
                                </nav>
                            </div>
                        </div>
                        <div class="sb-sidenav-footer">
                            <div class="small text-center"><?php echo date('Y-m-d H:i:s') ?></div>
                        </div>
                    </nav>
                </div>
                <div id="layoutSidenav_content">
                    <main>
                        <div class="container-fluid px-4">
                            <h1 class="mt-4">Admin Dashboard</h1>
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item active">Info</li>
                            </ol>
                            <div class="row">
                                <div class="col-xl-4 col-md-6">
                                    <div class="card bg-primary text-white mb-4">
                                        <?php
                                        require 'classes/admin.class.php';
                                        $adminObj = new Admin();
                                        $amountSum = $adminObj->getSumOfAmount();
                                        ?>
                                        <div class="card-body">Total of expenses</div>
                                        <div class="card-footer d-flex align-items-center justify-content-between">
                                            <p class="small text-white stretched-link">
                                                <?php echo $amountSum['total_sum'] . " MAD" ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    <?php
                                    $residentsSum = $adminObj->countResidents();
                                    ?>
                                    <div class="card bg-success text-white mb-4">
                                        <div class="card-body">Number of residents</div>
                                        <div class="card-footer d-flex align-items-center justify-content-between">
                                            <p class="small text-white stretched-link">
                                                <?php echo $residentsSum['residents_count'] . " residents" ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    <div class="card bg-danger text-white mb-4">
                                        <?php
                                        $cRNPCM = $adminObj->countresidentNotPaidCurrentMonth();
                                        ?>
                                        <div class="card-body">Didn't pay current month</div>
                                        <div class="card-footer d-flex align-items-center justify-content-between">
                                            <p class="small text-white stretched-link">
                                                <?php echo $cRNPCM['resident_count'] . " residents" ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <i class="fas fa-chart-area me-1"></i>
                                            Building expenses
                                        </div>
                                        <div class="card-body">
                                            <canvas id="myAreaChart" width="100%" height="40"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <i class="fas fa-chart-bar me-1"></i>
                                            Residents payments
                                        </div>
                                        <div class="card-body">
                                            <canvas id="myBarChart" width="100%" height="40"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between">
                                    <disv>
                                        <i class="fas fa-table me-1"></i>
                                        Residents Info
                                    </disv>
                                    <button type="button" class="btn btn-success btn-add" data-bs-toggle="modal" data-bs-target="#addResidentModal">Add Resident</button>
                                </div>
                                <div class="card-body">
                                    <?php
                                    require_once 'includes/get-all-residents.inc.php';
                                    ?>
                                    <table id="datatablesSimple">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Username</th>
                                                <th>Status</th>
                                                <th>Last paiment</th>
                                                <th>Delete / update</th>
                                            </tr>
                                        </thead>
                                        <tfoot class="text-center">
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Username</th>
                                                <th>Status</th>
                                                <th>Last paiment</th>
                                                <th>Delete / update</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            foreach ($residents as $resident) :
                                                $lastPaiment = $adminObj->lastPaiment($resident['resident_id']);
                                                $lastPaimentMonth = isset($lastPaiment['payment_month']) ? $lastPaiment['payment_month'] : 'Never';
                                                $lastPaimentYear = isset($lastPaiment['payment_year']) ? $lastPaiment['payment_year'] : 'paid';
                                            ?>
                                                <tr>
                                                    <td><?php echo $resident['fName'] . ' ' . $resident['lName']; ?></td>
                                                    <td><?php echo $resident['email']; ?></td>
                                                    <td><?php echo $resident['username']; ?></td>
                                                    <td><?php echo $resident['status']; ?></td>
                                                    <td><?php echo $lastPaimentMonth . '-' . $lastPaimentYear; ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <button type="button" class="btn btn-danger btn-sm btn-icon mr-2" data-bs-toggle="modal" data-bs-target="#deleteModal" data-resident-id="<?php echo $resident['resident_id']; ?>">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                            <button class="btn btn-success btn-sm btn-icon update-btn-resident" data-bs-toggle="modal" data-bs-target="#updateModal" data-resident-id="<?php echo $resident['resident_id']; ?>"><i class="fas fa-wrench"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Add Resident Modal -->
                        <div class="modal fade" id="addResidentModal" tabindex="-1" role="dialog" aria-labelledby="addResidentModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addResidentModalLabel">Add Resident</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="add-resident-form">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="firstNameInput">First Name</label>
                                                <input name="fName" type="text" class="form-control" id="firstNameInput" placeholder="Enter first name" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="lastNameInput">Last Name</label>
                                                <input name="lName" type="text" class="form-control" id="lastNameInput" placeholder="Enter last name" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="emailInput">Email</label>
                                                <input name="email" type="email" class="form-control" id="emailInput" placeholder="Enter email" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="passwordInput">Password</label>
                                                <input name="password" type="password" class="form-control" id="passwordInput" placeholder="Enter password" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="usernameInput">Username</label>
                                                <input name="username" type="text" class="form-control" id="usernameInput" placeholder="Enter username" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button name="add-resident" type="submit" class="btn btn-success" id="addResidentBtn">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Success or Error Modal (add) -->
                        <div class="modal fade" id="successModal-add-resident" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body mb-add-resident">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of Success or Error modal (add) -->
                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Delete Resident</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this resident?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger"> <a class="text-decoration-none text-reset">Delete</a></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delete Modal end -->
                        <!-- Delete Feedback Modal -->
                        <div class="modal fade" id="deleteFeedbackModal" tabindex="-1" role="dialog" aria-labelledby="deleteFeedbackModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteFeedbackModalLabel">Delete Result</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body delete-feedback-msg">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delete Feedback Modal End -->
                        <!-- Update Resident Modal -->
                        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateResidentModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addResidentModalLabel">Update Resident</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="add-resident-form" method="POST" action="includes/update-resident.inc.php">
                                        <input type="hidden" name="resident_id" id="residentIdInput">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="firstNameInput">First Name</label>
                                                <input name="fName" type="text" class="form-control" id="fNameInput" placeholder="Enter first name" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="lastNameInput">Last Name</label>
                                                <input name="lName" type="text" class="form-control" id="lNameInput" placeholder="Enter last name" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="emailInput">Email</label>
                                                <input name="email" type="email" class="form-control" id="email_Input" placeholder="Enter email" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="passwordInput">Password</label>
                                                <input name="password" type="password" class="form-control" id="password_Input" placeholder="Enter password" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="usernameInput">Username</label>
                                                <input name="username" type="text" class="form-control" id="username_Input" placeholder="Enter username" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button name="update-resident" type="submit" class="btn btn-success" id="updateResidentBtn">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Update Resident Modal End-->
                        <!-- Update Resident Success Modal -->
                        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body mb-resident-update-msg">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="close-btn btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Update Resident Success Modal End-->
                </div>
            </div>
            </main>
            <footer class="py-4 bg-light mt-4">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-danger">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
            </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
            </script>
            <script src="js/scripts.js"></script>
            <script src="js/add-resident.js"></script>
            <script src="js/delete-resident.js"></script>
            <script src="js/update-resident.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
            <script src="assets/demo/chart-area-demo.js"></script>
            <script src="assets/demo/chart-bar-demo.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
            <script src="js/datatables-simple-demo.js"></script>
        </body>

        </html>
<?php
    else :
        header("location: homepage.php");
    endif;
else :
    header('location: login.php');
endif;

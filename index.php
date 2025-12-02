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
            <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
            <link href="css/styles.css" rel="stylesheet" />
            <link href="css/admin-dashboard.css" rel="stylesheet" />
            <style>
                /* Bootstrap 5 compatibility fixes */
                .form-group { margin-bottom: 1rem; }
                .btn-block { width: 100%; }
                .ml-auto { margin-left: auto !important; }
                .mr-auto { margin-right: auto !important; }
            </style>


            <!-- Include jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- Include Bootstrap 5 CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
            <!-- Include Font Awesome -->
            <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        </head>

        <body class="sb-nav-fixed">
            <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
                <!-- Navbar Brand-->
                <a class="navbar-brand ps-3" href="index.php">Obuildings</a>
                <!-- Sidebar Toggle-->
                <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <!-- Navbar-->
                <ul class="navbar-nav ms-auto me-3 me-lg-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user fa-fw"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
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
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                        Add Expense
                                    </a>
                                    <a class="nav-link" href="add-announce.php">
                                        <div class="sb-nav-link-icon">
                                            <i class="fas fa-bullhorn"></i>
                                        </div>
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
                                    <div>
                                        <i class="fas fa-table me-1"></i>
                                        Residents Info
                                    </div>
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
                                                            <button type="button" class="btn btn-danger btn-sm btn-icon me-2" data-bs-toggle="modal" data-bs-target="#deleteModal" data-resident-id="<?php echo $resident['resident_id']; ?>">
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
                        <div class="modal fade" id="addResidentModal" tabindex="-1" aria-labelledby="addResidentModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addResidentModalLabel">Add Resident</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form id="add-resident-form">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="firstNameInput">First Name</label>
                                                <input name="fName" type="text" class="form-control" id="firstNameInput" placeholder="Enter first name" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="lastNameInput">Last Name</label>
                                                <input name="lName" type="text" class="form-control" id="lastNameInput" placeholder="Enter last name" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="emailInput">Email</label>
                                                <input name="email" type="email" class="form-control" id="emailInput" placeholder="Enter email" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="passwordInput">Password</label>
                                                <input name="password" type="password" class="form-control" id="passwordInput" placeholder="Enter password" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="usernameInput">Username</label>
                                                <input name="username" type="text" class="form-control" id="usernameInput" placeholder="Enter username" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Delete Resident</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this resident?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger"> <a class="text-decoration-none text-reset">Delete</a></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delete Modal end -->
                        <!-- Delete Feedback Modal -->
                        <div class="modal fade" id="deleteFeedbackModal" tabindex="-1" aria-labelledby="deleteFeedbackModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteFeedbackModalLabel">Delete Result</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body delete-feedback-msg">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delete Feedback Modal End -->
                        <!-- Update Resident Modal -->
                        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateResidentModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addResidentModalLabel">Update Resident</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form id="add-resident-form" method="POST" action="includes/update-resident.inc.php">
                                        <input type="hidden" name="resident_id" id="residentIdInput">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="firstNameInput">First Name</label>
                                                <input name="fName" type="text" class="form-control" id="fNameInput" placeholder="Enter first name" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="lastNameInput">Last Name</label>
                                                <input name="lName" type="text" class="form-control" id="lNameInput" placeholder="Enter last name" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="emailInput">Email</label>
                                                <input name="email" type="email" class="form-control" id="email_Input" placeholder="Enter email" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="passwordInput">Password</label>
                                                <input name="password" type="password" class="form-control" id="password_Input" placeholder="Enter password" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                            <div class="mb-3">
                                                <label for="usernameInput">Username</label>
                                                <input name="username" type="text" class="form-control" id="username_Input" placeholder="Enter username" required>
                                                <span class="form-text text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button name="update-resident" type="submit" class="btn btn-success" id="updateResidentBtn">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Update Resident Modal End-->
                        <!-- Update Resident Success Modal -->
                        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body mb-resident-update-msg">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="close-btn btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Update Resident Success Modal End-->
                        <!-- All Expenses Section -->
                        <?php $allExpenses = $adminObj->getAllExpenses(); ?>
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-money-bill-wave me-1"></i>
                                    All Expenses
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover text-center">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Invoice</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($allExpenses as $expense) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($expense['name']) ?></td>
                                                <td><?= htmlspecialchars($expense['description']) ?></td>
                                                <td>
                                                    <?php if (!empty($expense['invoice'])): ?>
                                                        <a href="#" class="invoice-image-link" data-img-src="uploads/invoices/<?= htmlspecialchars($expense['invoice']) ?>">
                                                            <img src="uploads/invoices/<?= htmlspecialchars($expense['invoice']) ?>" alt="Invoice" style="height:50px;max-width:80px;object-fit:cover;" />
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">No image</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= number_format($expense['amount'], 2, '.', ',') ?> MAD</td>
                                                <td><?= str_pad($expense['purchase_month'], 2, '0', STR_PAD_LEFT) . '-' . $expense['purchase_year'] ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm btn-delete-expense" data-expense-id="<?= htmlspecialchars($expense['purchase_id']) ?>" data-bs-toggle="modal" data-bs-target="#deleteExpenseModal">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- All Announcements Section -->
                        <?php
                        require_once 'classes/user.class.php';
                        $allAnnouncements = $adminObj->getAllAnnouncements();
                        $userObj = new User();
                        ?>
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-bullhorn me-1"></i>
                                    All Announcements (<span id="total-announcements"><?= count($allAnnouncements) ?></span>)
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <label class="mb-0 me-2 small text-muted">Per page:</label>
                                    <select id="items-per-page" class="form-select form-select-sm" style="width: auto;">
                                        <option value="8" selected>8</option>
                                        <option value="12">12</option>
                                        <option value="16">16</option>
                                        <option value="20">20</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (empty($allAnnouncements)): ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No announcements yet.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="row" id="announcements-container">
                                        <?php foreach ($allAnnouncements as $announce) :
                                            $countLikes = $userObj->countLikes($announce['announcement_id']);
                                            $allComments = $userObj->showComments($announce['announcement_id']);
                                        ?>
                                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                                <div class="card h-100 border-0 shadow-sm announce-card position-relative overflow-hidden"
                                                     style="cursor: pointer; transition: all 0.3s ease;"
                                                     data-announce-id="<?= $announce['announcement_id'] ?>"
                                                     data-announce-title="<?= htmlspecialchars($announce['title']) ?>"
                                                     data-announce-description="<?= htmlspecialchars($announce['description']) ?>"
                                                     data-announce-image="<?= htmlspecialchars($announce['image']) ?>"
                                                     data-announce-date="<?= htmlspecialchars($announce['created_at']) ?>"
                                                     data-announce-likes="<?= $countLikes['like_count'] ?>"
                                                     data-announce-comments='<?= json_encode($allComments) ?>'>

                                                    <!-- Image Section -->
                                                    <div class="position-relative">
                                                        <?php if (!empty($announce['image'])): ?>
                                                            <img src="uploads/announces/<?= htmlspecialchars($announce['image']) ?>"
                                                                 class="card-img-top"
                                                                 alt="Announcement"
                                                                 style="height: 140px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center"
                                                                 style="height: 140px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                                <i class="fas fa-bullhorn fa-2x text-white opacity-75"></i>
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- Hover Overlay -->
                                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center announce-overlay"
                                                             style="background: rgba(0,0,0,0.7); opacity: 0; transition: opacity 0.3s ease;">
                                                            <span class="text-white fw-bold">
                                                                <i class="fas fa-eye me-2"></i>View Details
                                                            </span>
                                                        </div>

                                                        <!-- Delete Button -->
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 btn-delete-announce"
                                                                data-announce-id="<?= htmlspecialchars($announce['announcement_id']) ?>"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#deleteAnnounceModal"
                                                                onclick="event.stopPropagation()"
                                                                style="opacity: 0.9; z-index: 10;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Card Body -->
                                                    <div class="card-body p-3 d-flex flex-column" style="min-height: 140px;">
                                                        <h6 class="card-title mb-2 fw-bold text-truncate"
                                                            title="<?= htmlspecialchars($announce['title']) ?>"
                                                            style="font-size: 0.95rem;">
                                                            <?= htmlspecialchars($announce['title']) ?>
                                                        </h6>

                                                        <p class="card-text text-muted small flex-grow-1 mb-2"
                                                           style="font-size: 0.8rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">
                                                            <?= htmlspecialchars($announce['description']) ?>
                                                        </p>

                                                        <!-- Date -->
                                                        <div class="border-top pt-2 mt-auto">
                                                            <small class="text-muted d-block mb-2" style="font-size: 0.75rem;">
                                                                <i class="fas fa-calendar-alt me-1"></i>
                                                                <?= date('M j, Y', strtotime($announce['created_at'])) ?>
                                                            </small>

                                                            <!-- Engagement Stats -->
                                                            <div class="d-flex gap-2">
                                                                <span class="badge bg-danger-subtle text-danger border border-danger" style="font-size: 0.7rem;">
                                                                    <i class="fas fa-heart"></i> <?= $countLikes['like_count'] ?>
                                                                </span>
                                                                <span class="badge bg-primary-subtle text-primary border border-primary" style="font-size: 0.7rem;">
                                                                    <i class="fas fa-comment"></i> <?= count($allComments) ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Pagination Controls -->
                                    <div class="d-flex justify-content-between align-items-center mt-4" id="pagination-controls">
                                        <div class="text-muted small">
                                            Showing <span id="showing-start">1</span> to <span id="showing-end">8</span> of <span id="showing-total"><?= count($allAnnouncements) ?></span> announcements
                                        </div>
                                        <nav aria-label="Announcements pagination">
                                            <ul class="pagination pagination-sm mb-0" id="pagination">
                                                <!-- Pagination will be generated by JavaScript -->
                                            </ul>
                                        </nav>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Announcement Details Modal -->
                        <div class="modal fade" id="announceDetailsModal" tabindex="-1" aria-labelledby="announceDetailsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="announceDetailsModalLabel">
                                            <i class="fas fa-bullhorn text-primary me-2"></i>
                                            <span id="modal-announce-title"></span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="modal-announce-image-container" class="mb-3"></div>

                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                <span id="modal-announce-date"></span>
                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <p id="modal-announce-description" class="text-dark"></p>
                                        </div>

                                        <div class="d-flex gap-4 mb-3 pb-3 border-bottom">
                                            <div>
                                                <i class="fas fa-heart text-danger me-1"></i>
                                                <strong id="modal-announce-likes">0</strong> Likes
                                            </div>
                                            <div>
                                                <i class="fas fa-comment text-primary me-1"></i>
                                                <strong id="modal-announce-comments-count">0</strong> Comments
                                            </div>
                                        </div>

                                        <div id="modal-comments-section">
                                            <h6 class="mb-3">
                                                <i class="fas fa-comments me-2"></i>Comments
                                            </h6>
                                            <div id="modal-comments-list"></div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add this modal after the expenses table -->
                        <div class="modal fade" id="invoiceImageModal" tabindex="-1" aria-labelledby="invoiceImageModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="invoiceImageModalLabel">Invoice Image</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img id="invoiceImagePreview" src="" alt="Invoice Image" class="img-fluid rounded shadow" style="max-height:70vh;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delete Expense Modal -->
                        <div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-labelledby="deleteExpenseModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteExpenseModalLabel">Delete Expense</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this expense?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form id="delete-expense-form" method="POST" action="includes/delete-expense.inc.php">
                                            <input type="hidden" name="expense_id" id="delete-expense-id" value="">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Delete Expense Modal -->
                        <!-- Delete Announcement Modal -->
                        <div class="modal fade" id="deleteAnnounceModal" tabindex="-1" aria-labelledby="deleteAnnounceModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAnnounceModalLabel">Delete Announcement</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this announcement?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form id="delete-announce-form" method="POST" action="includes/delete-announce.inc.php">
                                            <input type="hidden" name="announce_id" id="delete-announce-id" value="">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Delete Announcement Modal -->
                        <!-- Announcement Image Modal -->
                        <div class="modal fade" id="announceImageModal" tabindex="-1" aria-labelledby="announceImageModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="announceImageModalLabel">Announcement Image</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img id="announceImagePreview" src="" alt="Announcement Image" class="img-fluid rounded shadow" style="max-height:70vh;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Expense Delete Feedback Modal -->
                        <div class="modal fade" id="expenseDeleteFeedbackModal" tabindex="-1" aria-labelledby="expenseDeleteFeedbackModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="expenseDeleteFeedbackModalLabel">Delete Expense</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body expense-delete-feedback-msg">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Announcement Delete Feedback Modal -->
                        <div class="modal fade" id="announceDeleteFeedbackModal" tabindex="-1" aria-labelledby="announceDeleteFeedbackModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="announceDeleteFeedbackModalLabel">Delete Announcement</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body announce-delete-feedback-msg">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </main>
                </div>
            </div>
            <footer class="py-4 bg-light">
                <div class="container-fluid px-4">
                    <div class="text-center small">
                        <div class="text-danger">Copyright &copy; Obuilding <?php echo date('Y'); ?> - <?php echo date('F j, Y'); ?></div>
                    </div>
                </div>
            </footer>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
            <script src="js/scripts.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
            <script src="assets/demo/chart-area-demo.js"></script>
            <script src="assets/demo/chart-bar-demo.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
            <script src="js/datatables-simple-demo.js"></script>
            <script src="js/add-resident.js"></script>
            <script src="js/delete-resident.js"></script>
            <script src="js/update-resident.js"></script>
            <script src="js/admin-dashboard.js"></script>
        </body>

        </html>
<?php
    else :
        header("location: homepage.php");
    endif;
else :
    header('location: login.php');
endif;

<?php
session_start();
require_once 'classes/user.class.php';
$purchasesObj = new User();
$totalPurchases = $purchasesObj->getTotalPurchases();
$itemsPerPage = 4;
$totalPages = ceil($totalPurchases / $itemsPerPage);

$currentpage = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($currentpage - 1) * $itemsPerPage;

$purchases = $purchasesObj->getAllPurchases($offset, $itemsPerPage);
if (isset($_SESSION['status']) && $_SESSION['status'] === 'Resident') {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home - Obuildings</title>
        <!-- Include Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <!-- Custom Styles -->
        <link rel="stylesheet" href="css/user-dashboard.css">
    </head>

    <body class="user-dashboard">
        <nav class="navbar navbar-expand-lg navbar-dark navbar-modern">
            <div class="container">
                <a class="navbar-brand" href="homepage.php">
                    <i class="fas fa-building mr-2"></i>Obuildings
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="homepage.php">
                                <i class="fas fa-home mr-1"></i>Home <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="payments.php">
                                <i class="fas fa-credit-card mr-1"></i>Payments
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

        <div class="container my-4">
            <div class="glass-container p-4">
                <div class="welcome-text text-center mb-4">
                    <h1><i class="fas fa-home mr-3"></i>Welcome Home!</h1>
                    <p class="lead">Hello, <?php echo $_SESSION['fName'] . ' ' . $_SESSION['lName'] ?>!</p>
                    <p class="text-muted">Thank you for using Obuildings.</p>
                    <a href="payments.php" class="btn btn-pay btn-lg px-4">
                        <i class="fas fa-credit-card mr-2"></i>View Payments
                    </a>
                </div>
                
                <hr class="my-4">
                
                <h2 class="text-center mb-4">
                    <i class="fas fa-shopping-cart mr-2"></i>Building Expenses
                </h2>
            <div class="card table-modern border-0">
                <table class="table table-hover mb-0">
                <thead>
                    <tr class="table-header-solid">
                        <th class="border-0"><i class="fas fa-tag mr-2"></i>Name</th>
                        <th class="border-0"><i class="fas fa-info-circle mr-2"></i>Description</th>
                        <th class="border-0"><i class="fas fa-image mr-2"></i>Invoice Image</th>
                        <th class="border-0"><i class="fas fa-money-bill mr-2"></i>Amount</th>
                        <th class="border-0"><i class="fas fa-calendar mr-2"></i>Spending Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purchases as $purchase) : ?>
                        <tr>
                            <td><?php echo $purchase['name']; ?></td>
                            <td><?php echo $purchase['description']; ?></td>
                            <td>
                                <a href="includes/uploads/<?php echo $purchase['invoice'] ?>" data-toggle="modal" data-target="#imageModal">
                                    <img src="includes/uploads/<?php echo $purchase['invoice'] ?>" alt="Invoice Image" height="100">
                                </a>
                            </td>
                            <td class="text-center"><?php echo number_format($purchase['amount'], 0, ',', '.') ?>
                            </td>
                            <td class="text-center">
                                <?php echo '0' . $purchase['purchase_month'] . '-' . $purchase['purchase_year']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

                </table>
            </div>
            <!-- Pagination links -->
            <nav aria-label="Pagination" class="d-flex justify-content-center mt-4">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php echo ($currentpage == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            </div>
        </div>
        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <img src="" alt="Modal Image" id="modalImage" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
        <!--End Image Modal -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
        <script src="js/homepage.js"></script>
    </body>

    </html>
<?php
} elseif (isset($_SESSION['status']) && $_SESSION['status'] === 'Admin') {
    header('location: index.php');
} else {
    header('location: login.php');
}
?>
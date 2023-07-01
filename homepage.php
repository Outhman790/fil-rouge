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
        <title>Welcome <?php echo $_SESSION['fName'] . $_SESSION['lName'] ?></title>
        <!-- Include Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <img class="navbar-brand" src="assets/img/logo white.png" style="width: 200px; height: 60px"></img>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="payments.php">Payments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="announces.php">Announces</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            <h1>Welcome, <?php echo $_SESSION['fName'] . ' ' . $_SESSION['lName'] ?>!</h1>
            <p>Thank you for using Obuildings.</p>
            <a href="payments.php" class="btn btn-primary">See payments</a>
        </div>
        <div class="container mt-4">
            <h1>Sandik Purchases List</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Invoice Image</th>
                        <th>Amount</th>
                        <th>Spending Date</th>
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
            <!-- Pagination links -->
            <nav aria-label="Pagination" class="d-flex justify-content-center">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php echo ($currentpage == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
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
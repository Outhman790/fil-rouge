<?php
session_start();

// Security Headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY'); 
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

require_once 'classes/user.class.php';

// Load application configuration
$config = require_once 'config/app-config.php';

$purchasesObj = new User();
$totalPurchases = $purchasesObj->getTotalPurchases();
$itemsPerPage = $config['items_per_page'];
$totalPages = ceil($totalPurchases / $itemsPerPage);

$currentpage = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($currentpage - 1) * $itemsPerPage;

$purchases = $purchasesObj->getAllPurchases($offset, $itemsPerPage);
if (isset($_SESSION['status']) && $_SESSION['status'] === 'Resident') {
    // Include PageRenderer
    require_once 'render/page-renderer.php';
    
    // Page configuration
    $pageConfig = [
        'title' => 'Home - Obuildings',
        'currentPage' => 'home',
        'additionalCSS' => ['css/user-dashboard.css'],
        'additionalMeta' => [],
        'additionalJS' => ['js/homepage.js']
    ];
    
    // Render page header
    PageRenderer::renderPageHeader($pageConfig['title'], $pageConfig['currentPage'], $pageConfig['additionalCSS'], $pageConfig['additionalMeta']);
?>

        <div class="container my-4">
            <div class="glass-container p-4">
                <div class="welcome-text text-center mb-4">
                    <h1><i class="fas fa-home me-3"></i>Welcome Home!</h1>
                    <p class="lead">Hello, <?php echo htmlspecialchars($_SESSION['fName'] . ' ' . $_SESSION['lName']) ?>!</p>
                    <p class="text-muted">Thank you for using Obuildings.</p>
                    <a href="payments.php" class="btn btn-pay btn-lg px-4">
                        <i class="fas fa-credit-card me-2"></i>View Payments
                    </a>
                </div>

                <hr class="my-4">

                <h2 class="text-center mb-4">
                    <i class="fas fa-shopping-cart me-2"></i>Building Expenses
                </h2>
                <div class="card table-modern border-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="table-header-solid">
                                <th class="border-0"><i class="fas fa-tag me-2"></i>Name</th>
                                <th class="border-0"><i class="fas fa-info-circle me-2"></i>Description</th>
                                <th class="border-0"><i class="fas fa-image me-2"></i>Invoice Image</th>
                                <th class="border-0"><i class="fas fa-money-bill me-2"></i>Amount</th>
                                <th class="border-0"><i class="fas fa-calendar me-2"></i>Spending Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($purchases as $purchase) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($purchase['name']); ?></td>
                                    <td><?php echo htmlspecialchars($purchase['description']); ?></td>
                                    <td>
                                        <a href="includes/uploads/<?php echo htmlspecialchars($purchase['invoice']) ?>" data-bs-toggle="modal" data-bs-target="#imageModal">
                                            <img src="includes/uploads/<?php echo htmlspecialchars($purchase['invoice']) ?>" alt="Invoice Image" height="100">
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
        
<?php
    // Render page footer
    PageRenderer::renderPageFooter($pageConfig['additionalJS']);
} elseif (isset($_SESSION['status']) && $_SESSION['status'] === 'Admin') {
    header('location: index.php');
} else {
    header('location: login.php');
}
?>
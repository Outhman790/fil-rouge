<?php
// Homepage Renderer
// Handles rendering of homepage components

class HomepageRenderer
{
    public static function renderWelcomeSection($firstName, $lastName) {
?>
        <div class="welcome-text text-center mb-4">
            <h1><i class="fas fa-home mr-3"></i>Welcome Home!</h1>
            <p class="lead">Hello, <?php echo htmlspecialchars($firstName . ' ' . $lastName); ?>!</p>
            <p class="text-muted">Thank you for using Obuildings.</p>
            <a href="payments.php" class="btn btn-pay btn-lg px-4">
                <i class="fas fa-credit-card mr-2"></i>View Payments
            </a>
        </div>
<?php
    }

    public static function renderExpensesSection($purchases, $currentPage, $totalPages) {
?>
        <hr class="my-4">

        <h2 class="text-center mb-4 expenses-title">
            <i class="fas fa-shopping-cart mr-2"></i>Building Expenses
        </h2>
        
        <?php if (empty($purchases)) : ?>
            <?php self::renderEmptyExpenses(); ?>
        <?php else : ?>
            <?php self::renderExpensesTable($purchases); ?>
            <?php self::renderPagination($currentPage, $totalPages); ?>
        <?php endif; ?>
<?php
    }

    private static function renderEmptyExpenses() {
?>
        <div class="text-center p-5">
            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No Expenses Found</h4>
            <p class="text-muted">There are currently no building expenses to display.</p>
        </div>
<?php
    }

    private static function renderExpensesTable($purchases) {
?>
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
                        <?php self::renderExpenseRow($purchase); ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
<?php
    }

    private static function renderExpenseRow($purchase) {
?>
        <tr>
            <td data-label="Name"><?php echo htmlspecialchars($purchase['name']); ?></td>
            <td data-label="Description"><?php echo htmlspecialchars($purchase['description']); ?></td>
            <td data-label="Invoice Image">
                <a href="includes/uploads/<?php echo htmlspecialchars($purchase['invoice']); ?>" data-toggle="modal" data-target="#imageModal">
                    <img src="includes/uploads/<?php echo htmlspecialchars($purchase['invoice']); ?>" 
                         alt="Invoice Image" 
                         height="100"
                         loading="lazy">
                </a>
            </td>
            <td data-label="Amount" class="text-center">
                <?php echo number_format($purchase['amount'], 0, ',', '.'); ?> MAD
            </td>
            <td data-label="Spending Date" class="text-center">
                <?php echo str_pad($purchase['purchase_month'], 2, '0', STR_PAD_LEFT) . '-' . $purchase['purchase_year']; ?>
            </td>
        </tr>
<?php
    }

    private static function renderPagination($currentPage, $totalPages) {
?>
        <nav aria-label="Pagination" class="d-flex justify-content-center mt-4">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?php echo ($currentPage == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
<?php
    }

    public static function renderImageModal() {
?>
        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Invoice Image</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img src="" alt="Modal Image" id="modalImage" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    public static function renderPageContent($data) {
        extract($data);
?>
        <div class="container my-4">
            <div class="glass-container p-4">
                <?php self::renderWelcomeSection($firstName, $lastName); ?>
                <?php self::renderExpensesSection($purchases, $currentPage, $totalPages); ?>
            </div>
        </div>
        
        <?php self::renderImageModal(); ?>
<?php
    }
}
?>
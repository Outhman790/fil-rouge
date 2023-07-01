<?php
session_start();
if (isset($_SESSION['resident_id']) && $_SESSION['status'] == 'Admin') {

?>
  <!DOCTYPE html>
  <html>

  <head>
    <title>Add Expense</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" />
    <link href="css/styles.css" rel="stylesheet" />
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
      < <div id="layoutSidenav_nav">
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
                </a> <a class="nav-link" href="add-announce.php">
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
    <div style="max-width: 400px; margin: 5rem auto 0 auto">
      <h1 class="text-center mb-3">Add Expense</h1>
      <form id="add-expense-form" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="name" class="form-label">Name:</label>
          <input type="text" id="name" name="name" class="form-control" required />
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description:</label>
          <textarea id="description" name="description" rows="4" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
          <label for="invoice" class="form-label">Invoice (image):</label>
          <input type="file" id="invoice" name="invoice" accept="image/*" class="form-control" required />
        </div>
        <div class="mb-3">
          <label for="amount" class="form-label">Amount:</label>
          <input type="number" id="amount" name="amount" step="0.01" min="0" class="form-control" required />
        </div>
        <div class="mb-3">
          <label for="spending_date" class="form-label">Spending date:</label>
          <input type="date" id="spending_date" name="spending_date" class="form-control" required />
        </div>
        <div class="text-center">
          <button type="submit" name="add-expense" class="btn btn-primary">Add Expense</button>
        </div>
      </form>
    </div>
    <!-- Success or Error Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="successModalLabel">Success</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End of Success or Error modal -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/add-expense.js"></script>

  </body>

  </html>
<?php
} else {
  header('location: index.php');
}
?>
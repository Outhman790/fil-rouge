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
    <link href="css/add-expense.css" rel="stylesheet" />
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
                  <div class="sb-nav-link-icon">
                    <i class="fas fa-receipt"></i>
                  </div>
                  Add Expense
                </a> <a class="nav-link" href="add-announce.php">
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
        <div class="expense-form-container">
        <div class="form-card">
          <div class="form-header">
            <div class="form-icon">
              <i class="fas fa-receipt" style="font-size: 2rem; color: white;"></i>
            </div>
            <h1 class="form-title">Add Expense</h1>
            <p class="form-subtitle">Record your business expenses</p>
          </div>
          <div class="form-body">
            <form id="add-expense-form" enctype="multipart/form-data">
              <div class="form-floating">
                <input type="text" id="name" name="name" class="form-control" placeholder="Expense Name" required />
                <label for="name">Expense Name</label>
                <i class="fas fa-tag input-icon"></i>
              </div>
              <div class="form-floating">
                <textarea id="description" name="description" class="form-control" placeholder="Description" style="height: 100px" required></textarea>
                <label for="description">Description</label>
                <i class="fas fa-align-left input-icon"></i>
              </div>
              <div class="form-floating">
                <div class="file-upload-area" onclick="document.getElementById('invoice').click()">
                  <div class="file-upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                  </div>
                  <p style="margin: 0; color: #667eea; font-weight: 600;">Click to upload invoice</p>
                  <small style="color: #6c757d;">JPG, PNG files only</small>
                </div>
                <input type="file" id="invoice" name="invoice" accept="image/*" style="display: none;" required />
              </div>
              <div class="form-floating">
                <input type="number" id="amount" name="amount" step="0.01" min="0" class="form-control" placeholder="Amount" required />
                <label for="amount">Amount</label>
                <span class="amount-currency">₺</span>
              </div>
              <div class="form-floating">
                <input type="date" id="spending_date" name="spending_date" class="form-control" placeholder="Spending Date" required />
                <label for="spending_date">Spending Date</label>
                <i class="fas fa-calendar input-icon"></i>
              </div>
              <div class="text-center" style="margin-top: 2rem;">
                <button type="submit" name="add-expense" class="btn btn-submit text-white">
                  <i class="fas fa-plus-circle me-2"></i>Add Expense
                </button>
              </div>
            </form>
          </div>
        </div>
        </div>
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
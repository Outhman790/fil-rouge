<?php
// Page Header Template
// Reusable header component for all pages

function renderPageHeader($pageTitle, $currentPage, $additionalCSS = [], $additionalMeta = []) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Obuildings</title>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Base Styles -->
    <link rel="stylesheet" href="css/user-dashboard.css">
    
    <?php 
    // Additional CSS files
    foreach ($additionalCSS as $cssFile) {
        echo '<link rel="stylesheet" href="' . htmlspecialchars($cssFile) . '">' . "\n    ";
    }
    
    // Additional meta tags
    foreach ($additionalMeta as $name => $content) {
        echo '<meta name="' . htmlspecialchars($name) . '" content="' . htmlspecialchars($content) . '">' . "\n    ";
    }
    ?>
</head>

<body class="user-dashboard">
    <?php renderNavigation($currentPage); ?>
<?php
}

function renderNavigation($currentPage) {
?>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-modern">
        <div class="container">
            <a class="navbar-brand" href="homepage.php">
                <i class="fas fa-building mr-2"></i>Obuildings
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item <?php echo $currentPage === 'home' ? 'active' : ''; ?>">
                        <a class="nav-link" href="homepage.php">
                            <i class="fas fa-home mr-1"></i>Home
                            <?php if ($currentPage === 'home') echo '<span class="sr-only">(current)</span>'; ?>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $currentPage === 'announces' ? 'active' : ''; ?>">
                        <a class="nav-link" href="announces.php">
                            <i class="fas fa-bullhorn mr-1"></i>Announces
                            <?php if ($currentPage === 'announces') echo '<span class="sr-only">(current)</span>'; ?>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $currentPage === 'payments' ? 'active' : ''; ?>">
                        <a class="nav-link" href="payments.php">
                            <i class="fas fa-credit-card mr-1"></i>Payments
                            <?php if ($currentPage === 'payments') echo '<span class="sr-only">(current)</span>'; ?>
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
<?php
}

function renderPageFooter($additionalJS = []) {
?>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <?php 
    // Additional JavaScript files
    foreach ($additionalJS as $jsFile) {
        echo '<script src="' . htmlspecialchars($jsFile) . '"></script>' . "\n    ";
    }
    ?>
</body>
</html>
<?php
}
?>
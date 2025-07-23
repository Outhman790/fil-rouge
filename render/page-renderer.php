<?php
// Page Renderer
// Reusable page components for consistent layout across application

class PageRenderer
{
    public static function renderPageHeader($pageTitle, $currentPage, $additionalCSS = [], $additionalMeta = []) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Obuildings</title>
    
    <?php
    // Security Headers
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    ?>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap CSS (Optimized CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Shared Variables and Animations -->
    <link rel="stylesheet" href="css/_variables.css">
    <link rel="stylesheet" href="css/_animations.css">
    
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
    <?php self::renderNavigation($currentPage); ?>
<?php
    }

    private static function renderNavigation($currentPage) {
?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="homepage.php">
                <i class="fas fa-building me-2"></i>Obuildings
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'home' ? 'active' : ''; ?>" href="homepage.php">
                            <i class="fas fa-home me-1"></i>Home
                            <?php if ($currentPage === 'home') echo '<span class="visually-hidden">(current)</span>'; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'announces' ? 'active' : ''; ?>" href="announces.php">
                            <i class="fas fa-bullhorn me-1"></i>Announces
                            <?php if ($currentPage === 'announces') echo '<span class="visually-hidden">(current)</span>'; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'payments' ? 'active' : ''; ?>" href="payments.php">
                            <i class="fas fa-credit-card me-1"></i>Payments
                            <?php if ($currentPage === 'payments') echo '<span class="visually-hidden">(current)</span>'; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<?php
    }

    public static function renderPageFooter($additionalJS = []) {
?>
    <!-- Bootstrap JS (Bootstrap 5) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <!-- UI Feedback System -->
    <script src="js/ui-feedback.js"></script>
    
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
}
?>
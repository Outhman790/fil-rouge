<?php
session_start();

// Set some dummy session data for testing
if (!isset($_SESSION['resident_id'])) {
    $_SESSION['resident_id'] = 1;
    $_SESSION['fName'] = 'Test';
    $_SESSION['lName'] = 'User';
    $_SESSION['status'] = 'Resident';
}

require_once 'render/page-renderer.php';

$pageConfig = [
    'title' => 'Debug Test - Navbar Icons',
    'currentPage' => 'home',
    'additionalCSS' => [],
    'additionalMeta' => [],
    'additionalJS' => []
];

PageRenderer::renderPageHeader($pageConfig['title'], $pageConfig['currentPage'], $pageConfig['additionalCSS'], $pageConfig['additionalMeta']);
?>

<div class="container mt-4">
    <div class="alert alert-info">
        <h4>Debugging Font Awesome Icons</h4>
        <p>If you can see the icons below, Font Awesome is working:</p>
        
        <div class="row mt-3">
            <div class="col-12">
                <h5>Navbar Icons Test:</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-building"></i> Building Icon</li>
                    <li><i class="fas fa-home"></i> Home Icon</li>
                    <li><i class="fas fa-bullhorn"></i> Bullhorn Icon</li>
                    <li><i class="fas fa-credit-card"></i> Credit Card Icon</li>
                    <li><i class="fas fa-sign-out-alt"></i> Sign Out Icon</li>
                </ul>
            </div>
            
            <div class="col-12 mt-3">
                <h5>Announcement Icons Test:</h5>
                <ul class="list-unstyled">
                    <li><i class="far fa-heart"></i> Heart Regular (like)</li>
                    <li><i class="fas fa-heart"></i> Heart Solid (liked)</li>
                    <li><i class="fas fa-comment"></i> Comment Icon</li>
                    <li><i class="fas fa-comments"></i> Comments Icon</li>
                </ul>
            </div>
        </div>
        
        <div class="mt-4">
            <h5>CSS Loading Order:</h5>
            <ol>
                <li>Bootstrap CSS</li>
                <li>Font Awesome CSS</li>
                <li>Variables CSS</li>
                <li>Animations CSS</li>
                <li>User Dashboard CSS</li>
            </ol>
        </div>
        
        <div class="mt-4">
            <h5>Current Font Awesome Version:</h5>
            <p>Using Font Awesome 6.4.0 from CDNJS</p>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="homepage.php" class="btn btn-primary">
            <i class="fas fa-home me-2"></i>Back to Homepage
        </a>
        <a href="announces.php" class="btn btn-success">
            <i class="fas fa-bullhorn me-2"></i>Test Announces Page
        </a>
    </div>
</div>

<?php
PageRenderer::renderPageFooter($pageConfig['additionalJS']);
?>
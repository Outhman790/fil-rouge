<?php
session_start();

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

if (isset($_SESSION['resident_id'])) :
    // Include required files
    require_once 'classes/admin.class.php';
    require_once 'classes/user.class.php';
    require_once 'render/page-renderer.php';
    require_once 'render/announcement-renderer.php';
    
    // Initialize objects and data
    $adminObj = new Admin();
    $allAnnouncements = $adminObj->getAllAnnouncements();
    $userObj = new User();
    
    // Page configuration
    $pageConfig = [
        'title' => 'Community Announcements',
        'currentPage' => 'announces',
        'additionalCSS' => ['css/announces.css'],
        'additionalMeta' => [
            'resident-id' => $_SESSION['resident_id'],
            'resident-fullname' => $_SESSION['fName'] . ' ' . $_SESSION['lName']
        ],
        'additionalJS' => ['js/announces.js']
    ];
    
    // Render page header
    PageRenderer::renderPageHeader($pageConfig['title'], $pageConfig['currentPage'], $pageConfig['additionalCSS'], $pageConfig['additionalMeta']);
    
    // Calculate stats
    $totalAnnouncements = count($allAnnouncements ?? []);
    $recentAnnouncements = min($totalAnnouncements, 5);
    $currentMonth = date('F');
?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">
                <i class="fas fa-bullhorn" style="color: #f093fb; margin-right: 1rem;"></i>
                Community Hub
            </h1>
            <p class="hero-subtitle">Stay connected with your community - discover the latest news, events, and important announcements</p>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $totalAnnouncements; ?></span>
                    <span class="stat-label">Total Announcements</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $recentAnnouncements; ?></span>
                    <span class="stat-label">Recent</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $currentMonth; ?></span>
                    <span class="stat-label">Current Month</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="announcements-section">
    <div class="container">
        <div class="section-header fade-in-up">
            <h2 class="section-title">Latest Community Updates</h2>
            <p class="section-subtitle">Stay informed with the latest news and important information from your building management</p>
        </div>
        
        <div class="announcements-wrapper">
            <?php if (empty($allAnnouncements)) : ?>
                <?php AnnouncementRenderer::renderEmptyState(); ?>
            <?php else : ?>
                <?php foreach ($allAnnouncements as $announcement) : ?>
                    <?php AnnouncementRenderer::renderAnnouncementCard($announcement, $userObj); ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
AnnouncementRenderer::renderImageModal(); 
PageRenderer::renderPageFooter($pageConfig['additionalJS']); 
?>
<?php
else :
    header('location: login.php');
endif;
?>
<?php
session_start();

if (isset($_SESSION['resident_id'])) :
    // Include required files
    require_once 'classes/admin.class.php';
    require_once 'classes/user.class.php';
    require_once 'classes/page-renderer.class.php';
    require_once 'classes/announcement-renderer.class.php';
    
    // Initialize objects and data
    $adminObj = new Admin();
    $allAnnouncements = $adminObj->getAllAnnouncements();
    $userObj = new User();
    
    // Page configuration
    $pageConfig = [
        'title' => 'Announcements',
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
    
    // Render hero section
    AnnouncementRenderer::renderAnnouncementHero(count($allAnnouncements ?? []));

    <!-- Main Content -->
    <div class="container">
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

    <?php AnnouncementRenderer::renderImageModal(); ?>
    <?php PageRenderer::renderPageFooter($pageConfig['additionalJS']); ?>
</body>

</html>
<?php
else :
    header('location: login.php');
endif;
?>
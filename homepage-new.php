<?php
session_start();

if (isset($_SESSION['status']) && $_SESSION['status'] === 'Resident') :
    // Include required files
    require_once 'classes/user.class.php';
    require_once 'render/page-renderer.php';
    require_once 'render/homepage-renderer.php';
    
    // Initialize data and pagination
    $purchasesObj = new User();
    $totalPurchases = $purchasesObj->getTotalPurchases();
    $itemsPerPage = 4;
    $totalPages = ceil($totalPurchases / $itemsPerPage);
    
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($currentPage - 1) * $itemsPerPage;
    
    $purchases = $purchasesObj->getAllPurchases($offset, $itemsPerPage);
    
    // Page configuration
    $pageConfig = [
        'title' => 'Home',
        'currentPage' => 'home',
        'additionalCSS' => ['css/homepage.css'],
        'additionalMeta' => [
            'description' => 'Obuildings Home Dashboard',
            'keywords' => 'building management, expenses, home'
        ],
        'additionalJS' => ['js/homepage.js']
    ];
    
    // Prepare data for renderer
    $pageData = [
        'firstName' => $_SESSION['fName'],
        'lastName' => $_SESSION['lName'],
        'purchases' => $purchases,
        'currentPage' => $currentPage,
        'totalPages' => $totalPages
    ];
    
    // Render page header
    PageRenderer::renderPageHeader($pageConfig['title'], $pageConfig['currentPage'], $pageConfig['additionalCSS'], $pageConfig['additionalMeta']);
    
    // Render page content
    HomepageRenderer::renderPageContent($pageData);
    
    // Render page footer
    PageRenderer::renderPageFooter($pageConfig['additionalJS']);

elseif (isset($_SESSION['status']) && $_SESSION['status'] === 'Admin') :
    header('location: index.php');
    exit();
else :
    header('location: login.php');
    exit();
endif;
?>
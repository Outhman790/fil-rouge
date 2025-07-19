<?php
session_start();

// Check if user is logged in and is a resident
if (!isset($_SESSION['resident_id']) || !isset($_SESSION['status']) || $_SESSION['status'] !== 'Resident') {
    header('location: login.php');
    exit();
}

// Include required files
require_once 'classes/payments.class.php';
require_once 'classes/user.class.php';
require_once 'classes/admin.class.php';
require_once 'functions/extractMonthAndYear.php';
require_once 'functions/getUnpaidMonths.php';
require_once 'render/page-renderer.php';
require_once 'render/payments-renderer.php';

// Initialize objects and get data
$paymentObj = new Payments();
$adminObj = new Admin();
$userObj = new User();

$countPayments = $paymentObj->countPaymentsResident($_SESSION['resident_id']);
$resident = $adminObj->selectResident($_SESSION['resident_id']);
$joinedIn = extractMonthYear($resident['joinedIn']);

$currentYear = intval(date("Y"));
$currentMonth = intval(date("n"));

// Calculate unpaid months using the new logic
$latestPayment = ($countPayments == 0) ? null : $userObj->getLatestPayment($_SESSION['resident_id']);
$unpaidMonths = calculateUnpaidMonths($_SESSION['resident_id'], $joinedIn, $latestPayment);
$nextPaymentMonth = getNextPaymentMonth($unpaidMonths);

// Get user payments for display
$payments = $userObj->getUserPayments($_SESSION['resident_id']);

// Create comprehensive payment data
$allMonths = [];
$paidMonths = [];
$monthlyFee = 300;

// Get all months from registration to current
$registrationYear = (int)$joinedIn['year'];
$registrationMonth = (int)$joinedIn['month'];

// Create paid months array
foreach ($payments as $payment) {
    $paidMonths[] = [
        'month' => (int)$payment['payment_month'],
        'year' => (int)$payment['payment_year'],
        'transaction_id' => $payment['transaction_id'],
        'status' => 'paid'
    ];
}

// Generate all months from registration to current
for ($year = $registrationYear; $year <= $currentYear; $year++) {
    $startMonth = ($year == $registrationYear) ? $registrationMonth + 1 : 1;
    $endMonth = ($year == $currentYear) ? $currentMonth : 12;
    
    for ($month = $startMonth; $month <= $endMonth; $month++) {
        $isPaid = false;
        $transactionId = '';
        
        foreach ($paidMonths as $paidMonth) {
            if ($paidMonth['month'] == $month && $paidMonth['year'] == $year) {
                $isPaid = true;
                $transactionId = $paidMonth['transaction_id'];
                break;
            }
        }
        
        $allMonths[] = [
            'month' => $month,
            'year' => $year,
            'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
            'status' => $isPaid ? 'paid' : 'unpaid',
            'transaction_id' => $transactionId,
            'amount' => $monthlyFee,
            'due_date' => date('Y-m-d', mktime(0, 0, 0, $month, 5, $year)),
            'is_overdue' => !$isPaid && (($year < $currentYear) || ($year == $currentYear && $month < $currentMonth))
        ];
    }
}

// Calculate comprehensive stats
$totalUnpaidMonths = count($unpaidMonths);
$totalAmountToPay = $totalUnpaidMonths * $monthlyFee;
$totalPaidAmount = count($payments) * $monthlyFee;
$overdueMonths = array_filter($allMonths, function($month) {
    return $month['is_overdue'];
});
$overdueAmount = count($overdueMonths) * $monthlyFee;

// Payment completion percentage
$totalRequiredMonths = count($allMonths);
$completionPercentage = $totalRequiredMonths > 0 ? (count($payments) / $totalRequiredMonths) * 100 : 100;

// Page configuration
$pageConfig = [
    'title' => 'Payment Dashboard',
    'currentPage' => 'payments',
    'additionalCSS' => ['css/payments.css'],
    'additionalMeta' => [
        'description' => 'Obuildings Payment Dashboard',
        'keywords' => 'payments, billing, dashboard'
    ],
    'additionalJS' => ['js/payments.js']
];

// Prepare data for renderer
$pageData = [
    'firstName' => $_SESSION['fName'],
    'lastName' => $_SESSION['lName'],
    'stats' => [
        'paidMonths' => count($payments),
        'unpaidMonths' => $totalUnpaidMonths,
        'overdueMonths' => count($overdueMonths),
        'completionPercentage' => $completionPercentage,
        'totalPaidAmount' => $totalPaidAmount,
        'totalAmountToPay' => $totalAmountToPay,
        'overdueAmount' => $overdueAmount,
        'totalMonths' => $totalRequiredMonths
    ],
    'paymentStatus' => [
        'unpaidMonths' => $unpaidMonths,
        'nextPaymentMonth' => $nextPaymentMonth,
        'totalAmountToPay' => $totalAmountToPay,
        'overdueCount' => count($overdueMonths),
        'nextDueDate' => date('F Y', mktime(0, 0, 0, $currentMonth + 1, 1, $currentYear))
    ],
    'allMonths' => $allMonths
];

// Render page header
PageRenderer::renderPageHeader($pageConfig['title'], $pageConfig['currentPage'], $pageConfig['additionalCSS'], $pageConfig['additionalMeta']);

// Render page content
PaymentsRenderer::renderPageContent($pageData);

// Render page footer
PageRenderer::renderPageFooter($pageConfig['additionalJS']);
?>
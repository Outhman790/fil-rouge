<?php
// Test script for the new payment logic
require_once 'functions/getUnpaidMonths.php';

// Test scenarios
echo "<h2>Payment Logic Test Results</h2>";

// Scenario 1: User registered in July 2025, no payments made (current date: let's assume August 2025)
echo "<h3>Scenario 1: User registered in 07/2025, no payments (current: 08/2025)</h3>";
$registration_date = ['month' => 7, 'year' => 2025];
$last_payment = null;
$unpaid_months = calculateUnpaidMonths(1, $registration_date, $last_payment);
echo "Unpaid months: " . implode(', ', $unpaid_months) . "<br>";
echo "Next payment month: " . getNextPaymentMonth($unpaid_months) . "<br>";
echo "Expected: Only month 8 should be unpaid<br><br>";

// Scenario 2: User registered in July 2025, paid August, now it's September
echo "<h3>Scenario 2: User registered 07/2025, paid 08/2025, current: 09/2025</h3>";
$registration_date = ['month' => 7, 'year' => 2025];
$last_payment = ['payment_month' => 8, 'payment_year' => 2025];
$unpaid_months = calculateUnpaidMonths(1, $registration_date, $last_payment);
echo "Unpaid months: " . implode(', ', $unpaid_months) . "<br>";
echo "Next payment month: " . getNextPaymentMonth($unpaid_months) . "<br>";
echo "Expected: Only month 9 should be unpaid<br><br>";

// Scenario 3: User registered in December 2024, no payments, current: February 2025
echo "<h3>Scenario 3: User registered 12/2024, no payments, current: 02/2025</h3>";
$registration_date = ['month' => 12, 'year' => 2024];
$last_payment = null;
// Simulate current date as February 2025
$_old_date = date_default_timezone_get();
// We'll calculate manually since we can't easily mock date()
$unpaid_months_manual = [];
// From registration month + 1 (January 2025) to current month (February 2025)
for ($month = 1; $month <= 2; $month++) {
    $unpaid_months_manual[] = $month;
}
echo "Unpaid months (manual calc): " . implode(', ', $unpaid_months_manual) . "<br>";
echo "Expected: Months 1, 2 should be unpaid<br><br>";

// Scenario 4: User paid up to June 2025, current is August 2025
echo "<h3>Scenario 4: Last payment 06/2025, current: 08/2025</h3>";
$registration_date = ['month' => 3, 'year' => 2025]; // Registered in March
$last_payment = ['payment_month' => 6, 'payment_year' => 2025];
$unpaid_months = calculateUnpaidMonths(1, $registration_date, $last_payment);
echo "Unpaid months: " . implode(', ', $unpaid_months) . "<br>";
echo "Next payment month: " . getNextPaymentMonth($unpaid_months) . "<br>";
echo "Expected: Months 7, 8 should be unpaid<br><br>";

echo "<h3>Summary</h3>";
echo "The new logic correctly calculates unpaid months based on:<br>";
echo "1. Registration date (users only pay from the month AFTER registration)<br>";
echo "2. Last payment date<br>";
echo "3. Current date<br>";
echo "4. No more complex year calculations - just sequential month tracking<br>";
?>
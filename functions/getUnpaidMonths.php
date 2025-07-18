<?php
session_start();
require_once './classes/payments.class.php';

/**
 * Calculate unpaid months for a resident based on their registration date and payment history
 * 
 * @param int $resident_id The resident's ID
 * @param array $registration_date Array with 'month' and 'year' keys from extractMonthYear()
 * @param array|null $last_payment Array with 'payment_month' and 'payment_year' keys, or null if no payments
 * @return array Array of month numbers that are unpaid
 */
function calculateUnpaidMonths($resident_id, $registration_date, $last_payment = null)
{
    $current_month = (int)date('n');
    $current_year = (int)date('Y');
    $registration_month = (int)$registration_date['month'];
    $registration_year = (int)$registration_date['year'];
    
    $unpaid_months = [];
    
    // If no payments made yet, calculate from registration month to current month
    if ($last_payment === null) {
        // User registered in current year
        if ($registration_year === $current_year) {
            // Only include months from next month after registration to current month
            $start_month = $registration_month + 1;
            if ($start_month <= $current_month) {
                for ($month = $start_month; $month <= $current_month; $month++) {
                    $unpaid_months[] = $month;
                }
            }
        } else {
            // User registered in previous year(s)
            // Add remaining months from registration year
            for ($month = $registration_month + 1; $month <= 12; $month++) {
                $unpaid_months[] = $month;
            }
            
            // Add full years between registration and current year
            for ($year = $registration_year + 1; $year < $current_year; $year++) {
                for ($month = 1; $month <= 12; $month++) {
                    $unpaid_months[] = $month;
                }
            }
            
            // Add months from current year
            for ($month = 1; $month <= $current_month; $month++) {
                $unpaid_months[] = $month;
            }
        }
        
        return $unpaid_months;
    }
    
    $last_paid_month = (int)$last_payment['payment_month'];
    $last_paid_year = (int)$last_payment['payment_year'];
    
    // If last payment was in current year and current month, all is paid
    if ($last_paid_year === $current_year && $last_paid_month === $current_month) {
        return [];
    }
    
    // Calculate unpaid months from last payment to current month
    if ($last_paid_year === $current_year) {
        // Same year - just add months between last payment and current month
        for ($month = $last_paid_month + 1; $month <= $current_month; $month++) {
            $unpaid_months[] = $month;
        }
    } else {
        // Different years
        // Add remaining months from last paid year
        for ($month = $last_paid_month + 1; $month <= 12; $month++) {
            $unpaid_months[] = $month;
        }
        
        // Add full years between last paid year and current year
        for ($year = $last_paid_year + 1; $year < $current_year; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                $unpaid_months[] = $month;
            }
        }
        
        // Add months from current year
        for ($month = 1; $month <= $current_month; $month++) {
            $unpaid_months[] = $month;
        }
    }
    
    return $unpaid_months;
}

/**
 * Get the next month that needs to be paid
 * 
 * @param array $unpaid_months Array of unpaid month numbers
 * @return int|null The next month to pay, or null if all paid
 */
function getNextPaymentMonth($unpaid_months)
{
    if (empty($unpaid_months)) {
        return null;
    }
    
    return $unpaid_months[0];
}

// Legacy functions for backward compatibility
function getUnpaidYears($current_year, $joinedIn, $last_paid_year)
{
    // This function is deprecated, use calculateUnpaidMonths instead
    return [$current_year];
}

function getUnpaidMonths($current_month, $last_paid_month)
{
    // This function is deprecated, use calculateUnpaidMonths instead
    $unpaidMonths = [];
    if ($current_month > $last_paid_month) {
        for ($i = $last_paid_month + 1; $i <= $current_month; $i++) {
            $unpaidMonths[] = $i;
        }
    }
    return $unpaidMonths;
}

function getUnpaidMonthsOfAllUnpaidYears($last_paid_month, $unpaid_years, $isNew)
{
    // This function is deprecated, use calculateUnpaidMonths instead
    if (!isset($_SESSION['resident_id'])) {
        return [];
    }
    
    // Get registration date
    require_once './classes/admin.class.php';
    require_once './functions/extractMonthAndYear.php';
    
    $adminObj = new Admin();
    $resident = $adminObj->selectResident($_SESSION['resident_id']);
    $registration_date = extractMonthYear($resident['joinedIn']);
    
    // Get last payment
    $last_payment = null;
    if (!$isNew) {
        require_once './classes/user.class.php';
        $userObj = new User();
        $last_payment = $userObj->getLatestPayment($_SESSION['resident_id']);
    }
    
    return calculateUnpaidMonths($_SESSION['resident_id'], $registration_date, $last_payment);
}

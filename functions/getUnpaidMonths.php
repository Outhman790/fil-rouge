<?php
session_start();
require_once './classes/payments.class.php';
function getUnpaidYears($current_year, $joinedIn, $last_paid_year)
{
    $paymentObj = new Payments();
    // Check if last paid year is fully paid
    $checkYearPaiment = $paymentObj->checkYearPaiment($last_paid_year, $joinedIn, $_SESSION['resident_id']);
    // 
    if ($checkYearPaiment['total_months']  == 13 - $joinedIn) {
        $yearIsFullPaid = true;
    } else {
        $yearIsFullPaid = false;
    }

    $checkCurrentYearPaiment = $paymentObj->checkCurrentYearPaiment($_SESSION['resident_id']);
    if ($checkCurrentYearPaiment) {
        // If the current year is fully paid until the current month
        $unpaidYears = [];
    }
    // year X fully paid
    if ($yearIsFullPaid) {

        $result = $current_year - $last_paid_year;
        $unpaidYears = [];
        if ($result > 0) {
            for ($i = $last_paid_year + 1; $i <= $current_year; $i++) {
                $unpaidYears[] = $i;
            }
        }
    } else {


        $result = $current_year - $last_paid_year;

        if ($result > 0) {
            // Check this after 
            for ($i = $last_paid_year; $i <= $current_year; $i++) {
                $unpaidYears[] = $i;
            }
        } else {
            $unpaidYears[] = $current_year;
        }
    }

    return $unpaidYears;
}

function getUnpaidMonths($current_month, $last_paid_month)
{
    $result = $current_month - $last_paid_month;
    $unpaidMonths = [];

    if ($result > 0) {
        for ($i = $last_paid_month + 1; $i <= $current_month; $i++) {
            $unpaidMonths[] = $i;
        }
    }

    return $unpaidMonths;
}

function getUnpaidMonthsOfAllUnpaidYears($last_paid_month, $unpaid_years, $isNew)
{
    $unpaid_months = [];
    $last_year_unpaid_months = ($last_paid_month === 12) ? range(1, 12) : range($last_paid_month + 1, 12);
    $current_year = intval(date('Y'));
    $current_month = intval(date('n'));

    // All is paid
    if ($last_paid_month === $current_month && $unpaid_years[0] === $current_year) {
        return $unpaid_months;
    }

    // If the unpaid years is just one year
    if (count($unpaid_years) === 1) {
        $all_months = ($last_paid_month === 12) ? range(1, $current_month) : range($last_paid_month + 1, $current_month);
        $unpaid_months = array_merge($unpaid_months, $all_months);
        return $unpaid_months;
    }

    // For multiple unpaid years
    foreach ($unpaid_years as $key => $year) {
        // For the first year in the unpaid years
        if ($key == 0 && count($unpaid_years) == 2) {
            if ($isNew) {
                $all_months = ($last_paid_month == 12) ? range($last_paid_month, 12) : range($last_paid_month, 12);
                $unpaid_months = array_merge($unpaid_months, $all_months);
            } else {
                $all_months = ($last_paid_month == 12) ? range(1, 12) : range($last_paid_month + 1, 12);
                $unpaid_months = array_merge($unpaid_months, $all_months);
            }
        }
        // When the year is equal to the current year
        elseif ($year === $current_year) {
            if (count($unpaid_years) == 2 && $last_paid_month == 12) {
                if ($isNew) {
                    $all_months = range(1, $current_month);
                    $unpaid_months = array_merge($unpaid_months, $all_months);
                } else {
                    $all_months = range(1, $current_month);
                    $unpaid_months = array_merge($unpaid_months, $all_months);
                    return $unpaid_months;
                }
            } else {
                $all_months = range(1, $current_month);
                $unpaid_months = array_merge($unpaid_months, $all_months);
            }
        }
        // If the year isn't the current year or not the first year (rest of unpaid years)
        else {
            // Taking all the months
            if ($key == 0) {
                $all_months = range($last_paid_month + 1, 12);
                $unpaid_months = array_merge($unpaid_months, $all_months);
            } else {
                $all_months = range(1, 12);
                $unpaid_months = array_merge($unpaid_months, $all_months);
            }
        }
    }
    return $unpaid_months;
}

<?php
function extractMonthYear($joinedIn)
{
    // Convert the joinedIn date string to a DateTime object
    $date = new DateTime($joinedIn);

    // Extract the month and year
    $month = $date->format('n');
    $year = $date->format('Y');

    // Return the extracted month and year as an array
    return ['month' => $month, 'year' => $year];
}

<?php
require_once 'db.class.php';
class Payments extends DB
{

    public function addPayment($paymentMonth, $paymentYear, $transactionId, $resident_id)
    {
        $sql = "INSERT INTO payments (payment_month, payment_year, transaction_id, resident_id) 
        VALUES (:paymentMonth, :paymentYear, :transactionId, :resident_id)";

        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':paymentMonth', $paymentMonth, PDO::PARAM_INT);
        $stmt->bindParam(':paymentYear', $paymentYear, PDO::PARAM_INT);
        $stmt->bindParam(':transactionId', $transactionId, PDO::PARAM_STR);
        $stmt->bindParam(':resident_id', $resident_id, PDO::PARAM_STR);


        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function hasPaidCurrentMonth($residentId)
    {
        // Get the current month and year
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Prepare the SQL statement
        $sql = "SELECT COUNT(*) FROM payments WHERE payment_month = :month AND payment_year = :year AND resident_id = :residentId";
        $stmt = $this->connect()->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':month', $currentMonth);
        $stmt->bindParam(':year', $currentYear);
        $stmt->bindParam(':residentId', $residentId);

        // Execute the statement
        $stmt->execute();

        // Fetch the count
        $count = $stmt->fetchColumn();

        // Check if any payment exists for the current month and year
        return $count > 0;
    }
    // This function for checking if a year is fully paid or not
    public function checkYearPaiment($selected_year, $selected_month, $resident_id)
    {
        $query = "SELECT COUNT(*) AS total_months
        FROM payments
        WHERE resident_id = :resident_id
        AND (payment_year > :selected_year OR (payment_year = :selected_year AND payment_month >= :selected_month))";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':selected_year', $selected_year);
        $stmt->bindParam(':selected_month', $selected_month);
        $stmt->bindParam(':resident_id', $resident_id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
    public function checkCurrentYearPaiment($resident_id)
    {
        $query = "SELECT IF(COUNT(DISTINCT payment_month) = 12, 1, 0) AS payment_status
        FROM payments
        WHERE payment_year = YEAR(CURRENT_DATE)
        AND resident_id = :resident_id";

        $stmt = $this->connect()->prepare($query);

        $stmt->bindParam(':resident_id', $resident_id);

        $stmt->execute();

        $result = $stmt->fetchColumn();

        return $result;
    }
    public function countPaymentsResident($resident_id)
    {
        $query = "SELECT COUNT(*) AS num_payments FROM payments WHERE resident_id = :resident_id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':resident_id', $resident_id);
        $stmt->execute();
        $paymentsCount = $stmt->fetchColumn();
        return $paymentsCount;
    }
}

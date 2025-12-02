<?php
require_once('db.class.php');

class Admin extends DB
{
    public function getResidentAmount()
    {
        $connect = $this->connect();

        // Prepare the query to fetch data from the table
        $query = "SELECT amount FROM purchases";

        // Prepare the statement
        $stmt = $connect->prepare($query);

        // Execute the query
        $stmt->execute();

        // Fetch all the rows as an associative array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Encode the data as JSON
        $jsonData = json_encode($data);

        // Close the database connection
        $this->closeConnection();

        return $jsonData;
    }
    public function getSumOfAmount()
    {
        try {
            $query = "SELECT SUM(amount) AS total_sum FROM purchases";

            $statement = $this->connect()->prepare($query);

            $statement->execute();

            $amountSum = $statement->fetch(PDO::FETCH_ASSOC);

            return $amountSum;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function countResidents()
    {
        try {
            $query = "SELECT COUNT(*) AS residents_count FROM residents WHERE status NOT IN ('admin', 'Previous resident')";

            $statement = $this->connect()->prepare($query);

            $statement->execute();

            $residentsSum = $statement->fetch(PDO::FETCH_ASSOC);

            return $residentsSum;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function countresidentNotPaidCurrentMonth()
    {
        try {
            $query = "SELECT COUNT(*) AS resident_count
        FROM residents
        LEFT JOIN (
            SELECT resident_id
            FROM payments
            WHERE payment_month = MONTH(CURRENT_DATE)
            ORDER BY payment_id DESC
            LIMIT 1
        ) AS latest_payment ON residents.resident_id = latest_payment.resident_id
        WHERE latest_payment.resident_id IS NULL";

            $statement = $this->connect()->prepare($query);

            $statement->execute();

            $countresidentNotPaidCurrentMonth = $statement->fetch(PDO::FETCH_ASSOC);

            return $countresidentNotPaidCurrentMonth;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
    public function selectResident($resident_id)
    {
        $query = "SELECT * FROM residents WHERE resident_id = :resident_id;";
        $statement = $this->connect()->prepare($query);
        $statement->bindParam(':resident_id', $resident_id);
        $statement->execute();
        $resident =  $statement->fetch(PDO::FETCH_ASSOC);
        return $resident;
    }
    public function getTotalPaimentsOfMonthAndYear($month, $year)
    {
        $query = "SELECT COUNT(*) AS payment_count
        FROM payments
        WHERE payment_month = :month
        AND payment_year = :year";
        $statement = $this->connect()->prepare($query);
        $statement->bindParam(':month', $month);
        $statement->bindParam(':year', $year);
        $statement->execute();
        $total =  $statement->fetch(PDO::FETCH_ASSOC);
        return $total['payment_count'] * 300;
    }

    /**
     * Optimized method to get all monthly payment totals in a single query
     * Replaces multiple getTotalPaimentsOfMonthAndYear calls
     */
    public function getAllMonthlyPaymentTotals($year, $maxMonth = 12)
    {
        $query = "SELECT 
                    payment_month, 
                    COUNT(*) * 300 AS total_amount
                  FROM payments 
                  WHERE payment_year = :year AND payment_month <= :max_month 
                  GROUP BY payment_month
                  ORDER BY payment_month";
        
        $statement = $this->connect()->prepare($query);
        $statement->bindParam(':year', $year, PDO::PARAM_INT);
        $statement->bindParam(':max_month', $maxMonth, PDO::PARAM_INT);
        $statement->execute();
        
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        // Create array with all months (1-12) initialized to 0
        $monthlyTotals = [];
        for ($i = 1; $i <= $maxMonth; $i++) {
            $monthlyTotals[$i] = 0;
        }
        
        // Fill in actual values
        foreach ($results as $row) {
            $monthlyTotals[$row['payment_month']] = (int)$row['total_amount'];
        }
        
        return $monthlyTotals;
    }

    function addAnnouncement($title, $description, $image)
    {
        try {
            // Prepare the SQL statement
            $sql = "INSERT INTO announcements (title, description, image) VALUES (:title, :description, :image)";

            // Create a prepared statement
            $stmt = $this->connect()->prepare($sql);

            // Bind the parameters
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':image', $image);

            // Execute the statement
            if ($stmt->execute()) {
                return true; // Announcement added successfully
            } else {
                return false; // Failed to add announcement
            }
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function getAllAnnouncements()
    {
        try {

            $query = "SELECT * FROM announcements ORDER BY created_at DESC";
            $stmt = $this->connect()->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function lastPaiment($resident_id)
    {
        try {
            $query = "SELECT * FROM payments WHERE resident_id = :resident_id ORDER BY payment_year DESC, payment_month DESC LIMIT 1";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(':resident_id', $resident_id);
            $stmt->execute();
            $lastPaiment = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lastPaiment;
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getAllExpenses()
    {
        try {
            $query = "SELECT * FROM purchases ORDER BY purchase_year DESC, purchase_month DESC";
            $statement = $this->connect()->prepare($query);
            $statement->execute();
            $expenses = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $expenses;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function deleteExpense($purchase_id)
    {
        try {
            $query = "DELETE FROM purchases WHERE purchase_id = :purchase_id";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(':purchase_id', $purchase_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteAnnouncement($announcement_id)
    {
        try {
            $query = "DELETE FROM announcements WHERE announcement_id = :announcement_id";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(':announcement_id', $announcement_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}

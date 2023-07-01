<?php

class ExpenseManager extends DB
{
    public function addExpense($name, $description, $invoice, $amount, $spending_date)
    {
        try {
            $purchase_month = intval(date('n', strtotime($spending_date)));
            $purchase_year = intval(date('Y', strtotime($spending_date)));


            $query = "INSERT INTO purchases (purchase_month, purchase_year, description, invoice, amount, name) 
                VALUES (:purchase_month, :purchase_year, :description, :invoice, :amount, :name)";

            // Prepare the statement
            $statement = $this->connect()->prepare($query);

            // Bind parameters
            $statement->bindParam(':purchase_month', $purchase_month);
            $statement->bindParam(':purchase_year', $purchase_year);
            $statement->bindParam(':description', $description);
            $statement->bindParam(':invoice', $invoice);
            $statement->bindParam(':amount', $amount);
            $statement->bindParam(':name', $name);


            // Execute the statement
            $success = $statement->execute();
            return $success;
        } catch (PDOException $e) {
            die('Query failed: ' . $e->getMessage());
        }
    }
}

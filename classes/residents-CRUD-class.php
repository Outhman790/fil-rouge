<?php

require_once 'db.class.php';

class Resident extends DB
{
    public function addResident($firstName, $lastName, $email, $password, $username)
    {
        $conn = $this->connect();

        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO residents (fName, lName, email, password, username) VALUES (?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $firstName);
            $stmt->bindParam(2, $lastName);
            $stmt->bindParam(3, $email);
            $stmt->bindParam(4, $hashedPassword);
            $stmt->bindParam(5, $username);

            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        } finally {
            $this->closeConnection();
        }
    }


    public function deleteResident($resident_id)
    {
        try {
            $conn = $this->connect();

            $stmt = $conn->prepare("UPDATE residents SET status = 'Previous resident' WHERE resident_id = :resident_id;
            ");
            $stmt->bindParam('resident_id', $resident_id);
            $stmt->execute();


            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        } finally {
            $this->closeConnection();
        }
    }


    public function updateResident($residentId, $fName, $lName, $email, $password, $username)
    {
        try {
            $conn = $this->connect();

            $stmt = $conn->prepare("UPDATE residents SET fName = ?, lName = ?, email = ?, password = ?, username = ? WHERE resident_id = ?");
            $stmt->execute([$fName, $lName, $email, $password, $username, $residentId]);

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        } finally {
            $this->closeConnection();
        }
    }
}

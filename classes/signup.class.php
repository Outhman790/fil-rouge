<?php

class Signup extends DB
{
    public function createUser($username, $password, $email)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $conn = $this->connect();
            $query = "INSERT INTO residents (username, password, email) VALUES (:username, :password, :email)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            // Handle the error
            // You can customize the error handling based on your requirements
            die("Signup failed: " . $e->getMessage());
        }
    }


    public function isUsernameTaken($username)
    {
        try {
            $conn = $this->connect();
            $query = "SELECT * FROM residents WHERE username = :username";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $result = $stmt->fetch();

            return $result !== false;
        } catch (PDOException $e) {
            // Handle the error
            // You can customize the error handling based on your requirements
            die("Error checking username: " . $e->getMessage());
        }
    }

    public function emailTaken($email)
    {
        try {
            $conn = $this->connect();
            $query = "SELECT * FROM residents WHERE email = :email";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetch();

            return $result !== false;
        } catch (PDOException $e) {
            die("Error checking email: " . $e->getMessage());
        }
    }
}

<?php
require_once 'db.class.php';
class User extends DB
{
    public function getAllPurchases($offset, $limit)
    {
        $query = "SELECT * FROM `purchases` ORDER BY `purchases`.`purchase_year` DESC, `purchases`.`purchase_month` DESC LIMIT :offset, :limit";
        $statement = $this->connect()->prepare($query);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        if ($statement) {
            $purchases = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $purchases;
        }

        return null;
    }


    public function getUserPayments($resident_id)
    {

        try {
            // Query to retrieve all payments
            $query = "SELECT *
            FROM `payments`
            WHERE resident_id = :resident_id
            ORDER BY `payments`.`payment_year` DESC, `payments`.`payment_month` DESC;
            ";

            // Prepare and execute the query
            $statement = $this->connect()->prepare($query);
            $statement->bindParam(':resident_id', $resident_id);
            $statement->execute();

            // Fetch all payments as an associative array
            $payments = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $payments;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function getLatestPayment($resident_id)
    {
        try {
            // Query to retrieve last payment
            $query = "SELECT *
            FROM payments
            WHERE resident_id = :resident_id
            ORDER BY payment_year DESC, payment_month DESC
            LIMIT 1";
            // Prepare and execute the query
            $statement =  $this->connect()->prepare($query);
            $statement->bindParam(':resident_id', $resident_id);
            $statement->execute();
            // Fetch latest payment as an associative array
            $latestPayment = $statement->fetch(PDO::FETCH_ASSOC);

            return $latestPayment;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function getTotalPurchases()
    {
        $query = "SELECT COUNT(*) AS total FROM `purchases`";
        $statement = $this->connect()->query($query);
        $statement->execute();

        if ($statement) {
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        }

        return 0;
    }
    public function showComments($announcementId)
    {
        try {
            $query = "SELECT comments.*, residents.*
            FROM comments
            JOIN residents ON comments.resident_id = residents.resident_id
            WHERE comments.announcement_id = :announcement_id;
            ";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(':announcement_id', $announcementId);
            $stmt->execute();
            $allComments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $allComments;
        } catch (PDOException $e) {
            echo "error occured" . $e->getMessage();
        }
    }
    public function addComment($announcementId, $residentId, $commentText)
    {
        $currentTimestamp = date('Y-m-d H:i:s');
        echo 'Current Timestamp: ' . $currentTimestamp . '<br>';

        $query = "INSERT INTO comments (announcement_id, resident_id, comment_text, created_at)
                  VALUES (:announcementId, :residentId, :commentText, :createdAt)";

        $pdo = $this->connect(); // Get the PDO object

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':announcementId', $announcementId, PDO::PARAM_INT);
        $stmt->bindValue(':residentId', $residentId, PDO::PARAM_INT);
        $stmt->bindValue(':commentText', $commentText, PDO::PARAM_STR);
        $stmt->bindValue(':createdAt', $currentTimestamp, PDO::PARAM_STR);

        try {
            $stmt->execute();
            $lastInsertedId = $pdo->lastInsertId(); // Use lastInsertId() on the same PDO object
            echo $lastInsertedId . "<br>";

            $selectQuery = "SELECT * FROM comments WHERE comment_id = :commentId";
            $selectStmt = $pdo->prepare($selectQuery);
            $selectStmt->bindValue(':commentId', $lastInsertedId, PDO::PARAM_INT);
            $selectStmt->execute();
            $row = $selectStmt->fetch(PDO::FETCH_ASSOC);
            echo $row['created_at'];
            print_r($row);
            return $row;
        } catch (PDOException $e) {
            // Handle any database errors here
            echo 'Database Error: ' . $e->getMessage();
            return false;
        }
    }
    public function addLike($announcementId, $residentId)
    {
        try {
            $query = "INSERT INTO likes (announcement_id, resident_id) VALUES (:announcementId, :residentId)";
            $pdo = $this->connect();
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':announcementId', $announcementId);
            $stmt->bindParam(':residentId', $residentId);
            $addLike = $stmt->execute();
            return $addLike;
        } catch (PDOException $e) {
            // Handle any database errors here
            echo 'Database Error: ' . $e->getMessage();
            return false;
        }
    }


    public function countLikes($announcementId)
    {
        try {
            $query = "SELECT COUNT(*) AS like_count FROM likes WHERE announcement_id = :announcement_id";
            $pdo = $this->connect();
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':announcement_id', $announcementId);
            $stmt->execute();
            $countlikes = $stmt->fetch(PDO::FETCH_ASSOC);
            return $countlikes;
        } catch (PDOException $e) {
            // Handle any database errors here
            echo 'Database Error: ' . $e->getMessage();
            echo 'Error Code: ' . $stmt->errorCode();
            return false;
        }
    }
    public function checkLike($announcementId, $residentId)
    {
        try {
            $query = "SELECT COUNT(*) FROM likes WHERE announcement_id = :announcement_id AND resident_id = :resident_id";
            $pdo = $this->connect();
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':announcement_id', $announcementId);
            $stmt->bindParam(':resident_id', $residentId);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            return $count > 0;
        } catch (PDOException $e) {
            // Handle any database errors here
            echo 'Database Error: ' . $e->getMessage();
            return false;
        }
    }
}

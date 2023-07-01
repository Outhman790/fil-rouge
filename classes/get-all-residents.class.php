<?php
require_once 'db.class.php';
class Residents extends DB
{
    public function getAllResidents()
    {
        $conn = $this->connect();

        $query = "SELECT * FROM residents WHERE status NOT IN ('admin', 'Previous resident')";

        $stmt = $conn->prepare($query);
        $stmt->execute();

        $residents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->closeConnection();

        return $residents;
    }
}

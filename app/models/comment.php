<?php
require_once __DIR__ . "/../../config/database.php";

class Comment
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }


    public function getByTour($maTour)
    {
        $sql = "SELECT c.*, tv.Username 
            FROM comment c 
            JOIN thanhvien tv ON c.MaTVien = tv.MaTVien
            WHERE c.MaTour = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maTour);
        $stmt->execute();
        return $stmt->get_result();
    }


    public function getAll()
    {
        $sql = "SELECT * FROM comment ";
        return $this->conn->query($sql);
    }

    public function add($maCom, $maTVien, $maTour, $noiDungCom, $vote)
    {
        $sql = "INSERT INTO comment (MaCom, MaTVien, MaTour, NoiDungCom, Vote)
            VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siisi", $maCom, $maTVien, $maTour, $noiDungCom, $vote);
        return $stmt->execute();
    }


    public function delete($maCom)
    {
        $sql = "DELETE FROM comment WHERE MaCom = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maCom);
        return $stmt->execute();
    }
    public function searchByTour($maTour)
    {
        $sql = "SELECT c.*, tv.Username 
            FROM comment c 
            JOIN thanhvien tv ON c.MaTVien = tv.MaTVien
            WHERE c.MaTour LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $like = "%" . $maTour . "%";
        $stmt->bind_param("s", $like);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function approve($maCom) {
    $sql = "UPDATE comment SET TrangThai = 1 WHERE MaCom = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $maCom);
    $stmt->execute();
}

}



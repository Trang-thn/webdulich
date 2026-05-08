<?php
require_once __DIR__ . "/../../config/database.php";

class Comment {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    // Cho user: chỉ lấy bình luận đã duyệt
    public function getByTour($maTour) {
        $sql = "SELECT c.*, tv.Username
                FROM comment c
                JOIN thanhvien tv ON c.MaTVien = tv.MaTVien
                WHERE c.MaTour = ? AND c.TrangThai = 1
                ORDER BY c.MaCom DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maTour);
        $stmt->execute();
        $result = $stmt->get_result();

        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        return $comments;
    }

    // Cho admin: lấy tất cả bình luận theo mã tour (kể cả chưa duyệt)
    public function getAllByTour($maTour) {
        $sql = "SELECT c.*, tv.Username
                FROM comment c
                JOIN thanhvien tv ON c.MaTVien = tv.MaTVien
                WHERE c.MaTour = ?
                ORDER BY c.MaCom DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maTour);
        $stmt->execute();
        $result = $stmt->get_result();

        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        return $comments;
    }

    public function getAll() {
        $sql = "SELECT c.*, tv.Username 
                FROM comment c 
                JOIN thanhvien tv ON c.MaTVien = tv.MaTVien";
        return $this->conn->query($sql);
    }

    public function add($maCom, $maTVien, $maTour, $noiDungCom, $vote) {
        $sql = "INSERT INTO comment (MaCom, MaTVien, MaTour, NoiDungCom, Vote, TrangThai)
                VALUES (?, ?, ?, ?, ?, 0)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siisi", $maCom, $maTVien, $maTour, $noiDungCom, $vote);
        return $stmt->execute();
    }

    public function delete($maCom) {
        $sql = "DELETE FROM comment WHERE MaCom = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maCom);
        return $stmt->execute();
    }

    public function approve($maCom) {
        $sql = "UPDATE comment SET TrangThai = 1 WHERE MaCom = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $maCom);
        return $stmt->execute();
    }
}

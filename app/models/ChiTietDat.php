<?php
require_once __DIR__ . "/../../config/database.php";

class ChiTietDat
{
    private $conn;
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection(); 
    }

    public function addChiTiet($maDat, $maTour, $ngayDi, $soLuongKhach, $capKS, $khac)
    {
        $sql = "INSERT INTO chitietdat (MaDat, MaTour, NgayDi, SoLuongKhach, CapKS, Khac)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisiss", $maDat, $maTour, $ngayDi, $soLuongKhach, $capKS, $khac);
        return $stmt->execute();
    }

    public function updateChiTiet($maDat, $soLuongKhach, $ngayDi, $capKS, $khac)
    {
        $sql = "UPDATE chitietdat 
                SET SoLuongKhach=?, NgayDi=?, CapKS=?, Khac=?
                WHERE MaDat=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssi", $soLuongKhach, $ngayDi, $capKS, $khac, $maDat);
        return $stmt->execute();
    }

    public function deleteByMaDat($maDat)
    {
        $sql = "DELETE FROM chitietdat WHERE MaDat = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maDat);
        return $stmt->execute();
    }

    public function getChiTietByMaDat($maDat)
    {
        $sql = "SELECT * FROM chitietdat WHERE MaDat = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maDat);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }
}

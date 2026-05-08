<?php
require_once __DIR__ . "/../../config/database.php";

class DatTour
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    public function createDatTour($maTVien)
    {
        $sql = "INSERT INTO dattour (MaTVien, NgayDat) VALUES (?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maTVien);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    public function deleteDatTour($maDat)
    {
        $sql = "DELETE FROM dattour WHERE MaDat = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maDat);
        return $stmt->execute();
    }

    public function getAllBookings($keyword = null)
    {
        $sql = "SELECT d.MaDat, d.NgayDat, tv.HoTen, tv.EmailTVien,
                       ct.NgayDi, ct.SoLuongKhach, ct.CapKS, ct.Khac,
                       t.TenTour, t.GiaTour
                FROM dattour d
                JOIN thanhvien tv ON d.MaTVien = tv.MaTVien
                LEFT JOIN chitietdat ct ON d.MaDat = ct.MaDat
                LEFT JOIN tour t ON ct.MaTour = t.MaTour";

        if ($keyword) {
            $sql .= " WHERE d.MaDat LIKE ? OR tv.HoTen LIKE ? OR t.TenTour LIKE ?";
        }

        $sql .= " ORDER BY d.NgayDat DESC";
        $stmt = $this->conn->prepare($sql);

        if ($keyword) {
            $kw = "%" . $keyword . "%";
            $stmt->bind_param("sss", $kw, $kw, $kw);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        return $bookings;
    }

    public function getBookingById($maDat)
    {
        $sql = "SELECT d.MaDat, d.NgayDat, d.MaTVien, tv.HoTen, tv.EmailTVien, tv.SoDT, tv.DiaChi,
                       ct.NgayDi, ct.SoLuongKhach, ct.CapKS, ct.Khac,
                       t.MaTour, t.TenTour, t.GiaTour, t.DiemKhoiHanh, t.NoiDungTour
                FROM dattour d
                JOIN thanhvien tv ON d.MaTVien = tv.MaTVien
                LEFT JOIN chitietdat ct ON d.MaDat = ct.MaDat
                LEFT JOIN tour t ON ct.MaTour = t.MaTour
                WHERE d.MaDat = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maDat);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getBookingsByUser($maTVien)
    {
        $sql = "SELECT d.MaDat, d.NgayDat, c.NgayDi, c.SoLuongKhach, c.CapKS, c.Khac,
                   t.MaTour, t.TenTour, t.GiaTour
            FROM dattour d
            JOIN chitietdat c ON d.MaDat = c.MaDat
            JOIN tour t ON c.MaTour = t.MaTour
            WHERE d.MaTVien = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $maTVien);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        return $bookings;
    }
    public function findByUserTourDate($maTVien, $tourId, $ngayDi)
    {
        $sql = "SELECT dt.MaDat
            FROM dattour dt
            JOIN chitietdat ct ON dt.MaDat = ct.MaDat
            WHERE dt.MaTVien = ? 
              AND ct.MaTour = ? 
              AND ct.NgayDi = ?
            LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $maTVien, $tourId, $ngayDi);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); 
    }
}

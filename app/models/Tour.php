<?php
require_once __DIR__ . "/../../config/database.php";


class Tour
{
    public static function getAll()
    {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM tour ORDER BY NgayThem DESC";
        return $conn->query($sql);
    }

    public static function getById($id)
    {
        $conn = Database::getConnection();

        $id = intval($id);

        $sql = "SELECT * FROM TOUR WHERE MaTour = $id";
        return $conn->query($sql);
    }
    public static function getOtherTours($id)
    {

        $conn = Database::getConnection();
        $id = (int)$id;

        $sql = "SELECT * FROM TOUR 
                WHERE MaTour != $id 
                ORDER BY RAND() 
                LIMIT 6";

        return $conn->query($sql);
    }

    public static function insert($data)
    {
        $conn = Database::getConnection();
        $sql = "INSERT INTO TOUR 
        (MaLoai, TenTour, Gia, HinhAnh, MoTa) 
        VALUES (
            '{$data['MaLoai']}',
            '{$data['TenTour']}',
            '{$data['Gia']}',
            '{$data['HinhAnh']}',
            '{$data['MoTa']}'
        )";
        return $conn->query($sql);
    }

    public static function delete($id)
    {
        $conn = Database::getConnection();
        return $conn->query("DELETE FROM TOUR WHERE MaTour=$id");
    }
    //binh
    public static function getTourById($id)
{
    $conn = Database::getConnection();
    $id = intval($id);
    $sql = "SELECT * FROM TOUR WHERE MaTour = $id LIMIT 1";
    $result = $conn->query($sql);
    return $result ? $result->fetch_assoc() : null;
}
//linh
    public static function getLimit($limit = 8) {
        $db = Database::getConnection();
        $sql = "SELECT * FROM TOUR ORDER BY MaTour ASC LIMIT ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }
}



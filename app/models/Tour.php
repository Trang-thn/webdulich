<?php
require_once __DIR__ . "/../../config/database.php";


class Tour
{
    public static function getAll() {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM tour";
        $result = $conn->query($sql);

        $tours = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $tours[] = $row;
            }
        }
        return $tours; // ✅ Trả về mảng thay vì mysqli_result
    }


    public static function getById($id)
{
    $conn = Database::getConnection();
    $stmt = $conn->prepare("SELECT * FROM tour WHERE MaTour = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // ✅ trả về mảng
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
    public static function getLimit($limit) {
        $conn = Database::getConnection();
        $sql = "SELECT * FROM tour LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $tours = [];
        while ($row = $result->fetch_assoc()) {
            $tours[] = $row;
        }
        return $tours; // ✅ Trả về mảng
    }
}




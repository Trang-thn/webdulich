<?php
require_once __DIR__ . "/../../config/database.php";

class Tour
{
    public static function getAll()
    {
        $conn = Database::getConnection();
        $result = $conn->query("SELECT * FROM tour");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
//     public static function search($keyword)
//     {
//         $conn = Database::getConnection();
//         $stmt = $conn->prepare("SELECT * FROM tour WHERE TenTour LIKE ?");
//         $kw = "%$keyword%";
//         $stmt->bind_param("s", $kw);
//         $stmt->execute();
//         return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
//     }
//     public static function getById($id)
//     {
// =======
   public static function search($keyword) {
    $conn = Database::getConnection();
    // ép về lower-case và dùng collation để bỏ dấu
    $stmt = $conn->prepare("SELECT * FROM tour WHERE LOWER(TenTour) COLLATE utf8_general_ci LIKE LOWER(?) COLLATE utf8_general_ci");
    $kw = "%$keyword%";
    $stmt->bind_param("s", $kw);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


    public static function getById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM tour WHERE MaTour = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // trả về 1 dòng dữ liệu
    }


    public static function insert($data, $AnhTourString)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO tour (TenTour, GiaTour, TGTour, DiemKhoiHanh, NgayKhoiHanh, NoiDungTour, AnhTour)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sdsssss",
            $data['TenTour'],
            $data['GiaTour'],
            $data['TGTour'],
            $data['DiemKhoiHanh'],
            $data['NgayKhoiHanh'],
            $data['NoiDungTour'],
            $AnhTourString
        );
        return $stmt->execute();
    }

    public static function update($data, $AnhTourString)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE tour SET TenTour=?, GiaTour=?, TGTour=?, DiemKhoiHanh=?, NgayKhoiHanh=?, NoiDungTour=?, AnhTour=? WHERE MaTour=?");
        $stmt->bind_param(
            "sdsssssi",
            $data['TenTour'],
            $data['GiaTour'],
            $data['TGTour'],
            $data['DiemKhoiHanh'],
            $data['NgayKhoiHanh'],
            $data['NoiDungTour'],
            $AnhTourString,
            $data['MaTour']
        );
        return $stmt->execute();
    }

    public static function delete($id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM tour WHERE MaTour=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
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
    public static function getLimit($limit)
    {
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

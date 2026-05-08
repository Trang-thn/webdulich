<?php
require_once __DIR__ . "/../models/Tour.php";


class HomeService {
    // Lấy 8 tour nổi bật
    public static function getLimit($limit = 8) {
    $conn = Database::getConnection();
    $limit = (int)$limit; // ép kiểu an toàn
    $sql = "SELECT * FROM tour ORDER BY MaTour ASC LIMIT $limit";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}




    // Tìm kiếm tour theo tên
   public static function searchByName($keyword) {
    $conn = Database::getConnection();
    $result = $conn->query("SELECT * FROM tour");
    $tours = $result->fetch_all(MYSQLI_ASSOC);

    $filtered = [];

    // Chuẩn hóa keyword: viết thường, bỏ dấu
    $keyword = self::stripVN($keyword);

    foreach ($tours as $tour) {
        // Chuẩn hóa tên tour
        $tourName = self::stripVN($tour['TenTour']);

        // Tìm chứa keyword
        if (strpos($tourName, $keyword) !== false) {
            $filtered[] = $tour;
            continue;
        }

        // Tìm gần đúng bằng similar_text
        similar_text($keyword, $tourName, $percent);
        if ($percent > 50) { // tăng ngưỡng để chính xác hơn
            $filtered[] = $tour;
        }
    }

    return $filtered;
}

// Hàm bỏ dấu tiếng Việt
private static function stripVN($str) {
    $str = strtolower($str);
    $str = preg_replace("/[àáạảãâầấậẩẫăằắặẳẵ]/u", "a", $str);
    $str = preg_replace("/[èéẹẻẽêềếệểễ]/u", "e", $str);
    $str = preg_replace("/[ìíịỉĩ]/u", "i", $str);
    $str = preg_replace("/[òóọỏõôồốộổỗơờớợởỡ]/u", "o", $str);
    $str = preg_replace("/[ùúụủũưừứựửữ]/u", "u", $str);
    $str = preg_replace("/[ỳýỵỷỹ]/u", "y", $str);
    $str = preg_replace("/[đ]/u", "d", $str);
    return $str;
}

}

<?php
require_once __DIR__ . "/../models/Tour.php";
require_once __DIR__ . "/../../config/database.php";

class HomeApiController {
    // ✅ Trang chủ: lấy 8 tour
    public function index() {
        $tours = Tour::getLimit(8);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'success', 'data' => $tours], JSON_UNESCAPED_UNICODE);
    }

    // ✅ Tìm kiếm tour
    public function search() {
        $keyword = $_GET['keyword'] ?? '';
        $conn = Database::getConnection();

        if (!empty($keyword)) {
            $sql = "SELECT * FROM tour WHERE TenTour LIKE ?";
            $stmt = $conn->prepare($sql);
            $kw = "%$keyword%";
            $stmt->bind_param("s", $kw);
            $stmt->execute();
            $result = $stmt->get_result();
            $tours = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $tours = Tour::getLimit(8);
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'success', 'data' => $tours], JSON_UNESCAPED_UNICODE);
    }
}

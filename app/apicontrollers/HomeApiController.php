<?php
require_once __DIR__ . "/../services/HomeService.php";

class HomeApiController {
    public function index() {
        $tours = HomeService::getLimit(8);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'success', 'data' => $tours], JSON_UNESCAPED_UNICODE);
    }

    public function search() {
        $keyword = $_GET['keyword'] ?? '';
        $tours = !empty($keyword)
            ? HomeService::searchByName($keyword)
            : HomeService::getLimit(8);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'success', 'data' => $tours], JSON_UNESCAPED_UNICODE);
    }
}

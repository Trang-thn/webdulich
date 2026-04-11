<?php
require_once __DIR__ . "/../models/Tour.php";
class HomeController {
    public function index() {
        $tours = Tour::getLimit(8);
        include __DIR__ . "/../views/home/home.php";
    }
     public function search() {
    $keyword = $_GET['keyword'] ?? '';
    $conn = Database::getConnection();

    if (!empty($keyword)) {
        $sql = "SELECT * FROM tour WHERE TenTour LIKE '%" . $conn->real_escape_string($keyword) . "%'";
        $result = $conn->query($sql);
        $tours = $result;
    } else {
        $tours = Tour::getLimit(8);
    }

    include __DIR__ . "/../views/home/home.php";
}

}
<?php
require_once __DIR__ . "/../models/Tour.php";
require_once __DIR__ . "/../models/Comment.php";

class TourApiController
{
    // ✅ Lấy danh sách tour
    public function list()
    {
        $tours = Tour::getAll();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'success', 'data' => $tours], JSON_UNESCAPED_UNICODE);
    }

    // ✅ Chi tiết tour + comment
    public function detail()
{
    if (!isset($_GET['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu mã tour'], JSON_UNESCAPED_UNICODE);
        return;
    }

    $id = intval($_GET['id']);
    $tour = Tour::getById($id);

    if (!$tour) {
        echo json_encode(['status' => 'error', 'message' => 'Tour không tồn tại'], JSON_UNESCAPED_UNICODE);
        return;
    }

    $commentModel = new Comment();
    $comments = $commentModel->getByTour($tour['MaTour']); // đã là mảng

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status' => 'success',
        'data' => [
            'tour' => $tour,
            'comments' => $comments
        ]
    ], JSON_UNESCAPED_UNICODE);
}



    // ✅ Quản lý tour (dành cho admin)
    public function manage()
    {
        $tours = Tour::getAll();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'success', 'data' => $tours], JSON_UNESCAPED_UNICODE);
    }
}

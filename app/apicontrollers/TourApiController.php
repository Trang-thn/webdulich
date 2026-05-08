<?php
require_once __DIR__ . "/../services/TourService.php";

class TourApiController {
    private $tourService;

    public function __construct() {
        $this->tourService = new TourService();
    }

    public function list() {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status'=>'success','data'=>$this->tourService->getAllTours()], JSON_UNESCAPED_UNICODE);
    }

    public function search() {
        header('Content-Type: application/json; charset=utf-8');
        $keyword = $_GET['keyword'] ?? '';
        echo json_encode(['status'=>'success','data'=>$this->tourService->searchTours($keyword)], JSON_UNESCAPED_UNICODE);
    }

    public function detail() {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Thiếu tham số id'
            ]);
            return;
        }

        $tour = Tour::getById(intval($id));

        if ($tour) {
            echo json_encode([
                'status' => 'success',
                'data' => $tour
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Không tìm thấy tour'
            ]);
        }
    }


   public function add() {
    try {
        $ok = $this->tourService->createTour($_POST, $_FILES);
        echo json_encode(['status'=>$ok?'success':'error','message'=>$ok?'Thêm tour thành công!':'Không thể thêm tour']);
    } catch (Exception $e) {
        echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
    }
}


    public function edit() {
        header('Content-Type: application/json; charset=utf-8');
        $ok = $this->tourService->updateTour($_POST, $_FILES);
        echo json_encode(['status'=>$ok?'success':'error','message'=>$ok?'Cập nhật tour thành công!':'Không thể cập nhật tour'], JSON_UNESCAPED_UNICODE);
    }

    public function delete() {
        header('Content-Type: application/json; charset=utf-8');
        $id = $_POST['id'] ?? null;
        $ok = $id ? $this->tourService->deleteTour($id) : false;
        echo json_encode(['status'=>$ok?'success':'error','message'=>$ok?'Xóa tour thành công!':'Không thể xóa tour'], JSON_UNESCAPED_UNICODE);
    }
}

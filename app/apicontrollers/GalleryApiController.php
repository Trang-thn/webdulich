<?php
require_once __DIR__ . "/../services/GalleryService.php";

class GalleryApiController {
    private $service;

    public function __construct() {
        $this->service = new GalleryService();
    }

    // ✅ Lấy tất cả ảnh
    public function list() {
        $images = $this->service->getAllImages();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status'=>'success','data'=>$images], JSON_UNESCAPED_UNICODE);
    }

    // ✅ Lấy ảnh theo tour
    public function listByTour() {
        $maTour = $_GET['id'] ?? ($_GET['maTour'] ?? null);
        if (!$maTour) {
            echo json_encode(['status'=>'error','message'=>'Thiếu mã tour'], JSON_UNESCAPED_UNICODE);
            return;
        }
        $images = $this->service->getImagesByTour($maTour);
        echo json_encode(['status'=>'success','data'=>$images], JSON_UNESCAPED_UNICODE);
    }

    // ✅ Thêm ảnh mới
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $maTour = $_POST['maTour'] ?? null;
                $moTa   = $_POST['MoTa'] ?? '';
                $file   = $_FILES['LinkAnh'] ?? null;

                if (!$maTour || !$file) {
                    echo json_encode(['status'=>'error','message'=>'Thiếu dữ liệu'], JSON_UNESCAPED_UNICODE);
                    return;
                }

                $this->service->addImage($maTour, $file, $moTa);
                echo json_encode(['status'=>'success','message'=>'Thêm ảnh thành công!'], JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                echo json_encode(['status'=>'error','message'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
            }
        }
    }

    // ✅ Xóa ảnh
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                echo json_encode(['status'=>'error','message'=>'Thiếu ID ảnh'], JSON_UNESCAPED_UNICODE);
                return;
            }
            $ok = $this->service->deleteImage($id);
            if ($ok) {
                echo json_encode(['status'=>'success','message'=>'Xóa ảnh thành công!'], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['status'=>'error','message'=>'Không thể xóa ảnh'], JSON_UNESCAPED_UNICODE);
            }
        }
    }
}

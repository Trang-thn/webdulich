<?php
require_once __DIR__ . "/../services/CommentService.php";

class CommentApiController {
    private $service;

    public function __construct() {
        $this->service = new CommentService();
    }

    public function listAll() {
        $comments = $this->service->getAllComments();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status'=>'success','data'=>$comments], JSON_UNESCAPED_UNICODE);
    }

    public function listByTour() {
        $maTour = $_GET['id'] ?? ($_GET['maTour'] ?? null);
        if (!$maTour) {
            echo json_encode(['status'=>'error','message'=>'Thiếu mã tour'], JSON_UNESCAPED_UNICODE);
            return;
        }
        $comments = $this->service->getCommentsByTour($maTour);
        echo json_encode(['status'=>'success','data'=>$comments], JSON_UNESCAPED_UNICODE);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $maTVien = $_SESSION['user']['MaTVien'] ?? null;
                $maTour  = $_POST['maTour'] ?? null;
                $noiDungCom = $_POST['noiDungCom'] ?? '';
                $vote = (int)($_POST['vote'] ?? 5);

                $this->service->addComment($maTVien, $maTour, $noiDungCom, $vote);

                echo json_encode(['status'=>'success','message'=>'Bình luận đã gửi, cần admin phê duyệt trước khi hiển thị.'], JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                echo json_encode(['status'=>'error','message'=>$e->getMessage()], JSON_UNESCAPED_UNICODE);
            }
        }
    }

    public function deleteAdmin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maCom = $_POST['maCom'] ?? null;
            if (!$maCom) {
                echo json_encode(['status'=>'error','message'=>'Thiếu mã comment'], JSON_UNESCAPED_UNICODE);
                return;
            }
            $this->service->deleteComment($maCom);
            echo json_encode(['status'=>'success','message'=>'Xóa bình luận thành công!'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function approveAdmin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maCom = $_POST['maCom'] ?? null;
            if (!$maCom) {
                echo json_encode(['status'=>'error','message'=>'Thiếu mã comment'], JSON_UNESCAPED_UNICODE);
                return;
            }
            $this->service->approveComment($maCom);
            echo json_encode(['status'=>'success','message'=>'Bình luận đã được duyệt!'], JSON_UNESCAPED_UNICODE);
        }
    }
}

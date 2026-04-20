<?php
require_once __DIR__ . "/../models/Comment.php";

class CommentApiController
{
    private $commentModel;

    public function __construct()
    {
        $this->commentModel = new Comment();
    }

    // ✅ Lấy tất cả comment
    public function listAll()
    {
        $result = $this->commentModel->getAll();
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'success', 'data' => $comments], JSON_UNESCAPED_UNICODE);
    }

    // ✅ Lấy comment theo tour
   public function listByTour()
{
    $maTour = $_GET['id'] ?? ($_GET['maTour'] ?? null);
    if (!$maTour) {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu mã tour'], JSON_UNESCAPED_UNICODE);
        return;
    }

    $comments = $this->commentModel->getByTour($maTour);

    echo json_encode(['status' => 'success', 'data' => $comments], JSON_UNESCAPED_UNICODE);
}




    // ✅ Thêm comment mới
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maCom = uniqid('MC');
            $maTVien = $_SESSION['user']['MaTVien'] ?? null;
            if (!$maTVien) {
                echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập để bình luận!'], JSON_UNESCAPED_UNICODE);
                return;
            }

            $maTour  = $_POST['maTour'] ?? null;
            $noiDungCom = $_POST['noiDungCom'] ?? '';
            $vote = (int)($_POST['vote'] ?? 5);

            if ($vote < 1 || $vote > 5) $vote = 5;

            $this->commentModel->add($maCom, $maTVien, $maTour, $noiDungCom, $vote);

            echo json_encode(['status' => 'success', 'message' => 'Bình luận đã gửi, cần admin phê duyệt trước khi hiển thị.'], JSON_UNESCAPED_UNICODE);
        }
    }

    // ✅ Xóa comment (admin)
    public function deleteAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maCom = $_POST['maCom'] ?? null;
            if (!$maCom) {
                echo json_encode(['status' => 'error', 'message' => 'Thiếu mã comment'], JSON_UNESCAPED_UNICODE);
                return;
            }
            $this->commentModel->delete($maCom);
            echo json_encode(['status' => 'success', 'message' => 'Xóa bình luận thành công!'], JSON_UNESCAPED_UNICODE);
        }
    }

    // ✅ Duyệt comment (admin)
    public function approveAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maCom = $_POST['maCom'] ?? null;
            if (!$maCom) {
                echo json_encode(['status' => 'error', 'message' => 'Thiếu mã comment'], JSON_UNESCAPED_UNICODE);
                return;
            }
            $this->commentModel->approve($maCom);
            echo json_encode(['status' => 'success', 'message' => 'Bình luận đã được duyệt!'], JSON_UNESCAPED_UNICODE);
        }
    }
}

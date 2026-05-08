<?php
require_once __DIR__ . "/../services/CommentService.php";

class CommentApiController
{
    private $commentService;

    public function __construct()
    {
        $this->commentService = new CommentService();
    }

    // GET /api/comments
    // GET /api/comments
    public function listAll()
    {
        $comments = $this->commentService->getAllComments();
        $data = [];
        while ($row = $comments->fetch_assoc()) {
            $data[] = $row;
        }
        $this->jsonResponse('success', $data);
    }

    // GET /api/comments/tour?id=...
    public function listByTour()
    {
        $maTour = $_GET['id'] ?? null;
        if (!$maTour) {
            $this->jsonResponse('error', null, 'Thiếu mã tour');
            return;
        }
        $comments = $this->commentService->getCommentsByTour($maTour);
        $this->jsonResponse('success', $comments);
    }

    // POST /api/comments
    public function add()
    {
        $maTVien = $_SESSION['user']['MaTVien'] ?? null;
        if (!$maTVien) {
            $this->jsonResponse('error', null, 'Bạn cần đăng nhập để bình luận!');
            return;
        }

        $maTour = $_POST['maTour'] ?? null;
        $noiDungCom = $_POST['noiDungCom'] ?? '';
        $vote = (int)($_POST['vote'] ?? 5);
        if ($vote < 1 || $vote > 5) $vote = 5;

        $this->commentService->addComment($maTVien, $maTour, $noiDungCom, $vote);
        $this->jsonResponse('success', null, 'Bình luận đã gửi, cần admin phê duyệt.');
    }

    // DELETE /api/comments/{id}
    public function deleteAdmin()
    {
        $maCom = $_POST['maCom'] ?? null;
        if (!$maCom) {
            $this->jsonResponse('error', null, 'Thiếu mã comment');
            return;
        }
        $this->commentService->deleteComment($maCom);
        $this->jsonResponse('success', null, 'Xóa bình luận thành công!');
    }

    // PUT /api/comments/approve/{id}
    public function approveAdmin()
    {
        $maCom = $_POST['maCom'] ?? null;
        if (!$maCom) {
            $this->jsonResponse('error', null, 'Thiếu mã comment');
            return;
        }
        $this->commentService->approveComment($maCom);
        $this->jsonResponse('success', null, 'Bình luận đã được duyệt!');
    }

    private function jsonResponse($status, $data = null, $message = null)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => $status,
            'data' => $data,
            'message' => $message
        ], JSON_UNESCAPED_UNICODE);
    }
}

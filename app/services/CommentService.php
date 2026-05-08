<?php
require_once __DIR__ . "/../models/Comment.php";

class CommentService {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new Comment();
    }

    public function getAllComments() {
        return $this->commentModel->getAll();
    }

    public function getCommentsByTour($maTour) {
        return $this->commentModel->getByTour($maTour); // cho user
    }

    public function getAllCommentsByTour($maTour) {
        return $this->commentModel->getAllByTour($maTour); // cho admin
    }

    public function addComment($maTVien, $maTour, $noiDungCom, $vote) {
        if (strlen(trim($noiDungCom)) < 5) {
            return ['status' => 'error', 'message' => 'Nội dung bình luận quá ngắn'];
        }
        if ($vote < 1 || $vote > 5) {
            $vote = 5;
        }

        $maCom = uniqid('MC');
        $result = $this->commentModel->add($maCom, $maTVien, $maTour, $noiDungCom, $vote);

        if ($result) {
            return ['status' => 'success', 'message' => 'Bình luận đã gửi, cần admin phê duyệt.'];
        }
        return ['status' => 'error', 'message' => 'Thêm bình luận thất bại'];
    }

    public function deleteComment($maCom) {
        return $this->commentModel->delete($maCom);
    }

    public function approveComment($maCom) {
        return $this->commentModel->approve($maCom);
    }
}

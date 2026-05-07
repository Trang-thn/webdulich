<?php
require_once __DIR__ . "/../models/Comment.php";

class CommentService {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new Comment();
    }

    // Lấy tất cả comment
    public function getAllComments() {
        $result = $this->commentModel->getAll();
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        return $comments;
    }

    // Lấy comment theo tour
    public function getCommentsByTour($maTour) {
        return $this->commentModel->getByTour($maTour);
    }

    // Thêm comment mới
    public function addComment($maTVien, $maTour, $noiDungCom, $vote) {
        $maCom = uniqid('MC');
        if (!$maTVien) {
            throw new Exception("Bạn cần đăng nhập để bình luận!");
        }
        if ($vote < 1 || $vote > 5) $vote = 5;
        return $this->commentModel->add($maCom, $maTVien, $maTour, $noiDungCom, $vote);
    }

    // Xóa comment
    public function deleteComment($maCom) {
        return $this->commentModel->delete($maCom);
    }

    // Duyệt comment
    public function approveComment($maCom) {
        return $this->commentModel->approve($maCom);
    }
}

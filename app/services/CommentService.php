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
        return $this->commentModel->getByTour($maTour);
    }

    public function addComment($maTVien, $maTour, $noiDungCom, $vote) {
        $maCom = uniqid('MC'); // tạo mã comment
        return $this->commentModel->add($maCom, $maTVien, $maTour, $noiDungCom, $vote);
    }

    public function deleteComment($maCom) {
        return $this->commentModel->delete($maCom);
    }

    public function approveComment($maCom) {
        return $this->commentModel->approve($maCom);
    }

    public function searchCommentsByTour($maTour) {
        return $this->commentModel->searchByTour($maTour);
    }
}
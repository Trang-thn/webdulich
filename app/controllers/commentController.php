<?php
require_once __DIR__ . "/../models/comment.php";

class CommentController
{
    private $commentModel;

    public function __construct()
    {
        $this->commentModel = new Comment();
    }

    public function form()
    {
        $result = $this->commentModel->getAll();
        include "./View/php/comment_form.php";
    }

    public function admin()
    {
        $result = $this->commentModel->getAll();
        $viewFile = __DIR__ . "/../views/admin/manage_comment.php";
        include __DIR__ . "/../views/admin/dashboard.php";
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maCom = uniqid('MC');
            $maTVien = $_SESSION['user']['MaTVien'] ?? null;
            if (!$maTVien) {
                echo "<script>alert('Bạn cần đăng nhập để bình luận!'); window.location.href='/webdulich/user/login';</script>";
                return;
            }
            $maTour  = $_POST['maTour'];
            $noiDungCom = $_POST['noiDungCom'];
            $vote = (int)$_POST['vote'];

            if ($vote < 1 || $vote > 5) $vote = 5;

            $this->commentModel->add($maCom, $maTVien, $maTour, $noiDungCom, $vote);

            $_SESSION['comment_message'] = "Bình luận đã gửi, cần admin phê duyệt trước khi hiển thị.";
            header("Location: /webdulich/detail?id=$maTour");
            exit;
        }
    }



    public function deleteAdmin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $maCom = $_POST['maCom'];
            $this->commentModel->delete($maCom);
            $result = $this->commentModel->getAll();
            $viewFile = __DIR__ . "/../views/admin/manage_comment.php";
            include __DIR__ . "/../views/admin/dashboard.php";
        }
    }
    public function list($maTour)
    {
        $result = $this->commentModel->getByTour($maTour);
        include "./View/php/comment_list.php";
    }

    public function manage()
    {
        $result = $this->commentModel->getAll();
        include "./View/php/manage_comment.php";
    }
    public function searchByTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['MaTour'])) {
            $maTour = $_GET['MaTour'];
            $result = $this->commentModel->getByTour($maTour);

            $viewFile = __DIR__ . "/../views/admin/manage_comment.php";
            include __DIR__ . "/../views/admin/dashboard.php";
        }
    }
    public function approveAdmin()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $maCom = $_POST['maCom'];
        $this->commentModel->approve($maCom);

        $result = $this->commentModel->getAll();
        $viewFile = __DIR__ . "/../views/admin/manage_comment.php";
        include __DIR__ . "/../views/admin/dashboard.php";
    }
}

}

<?php
require_once __DIR__ . "/../models/Tour.php";
require_once __DIR__ . "/../models/Comment.php";


class TourController
{
    public function index()
    {
        $tours = Tour::getAll();
        include __DIR__ . "/../views/tour/list.php";
    }

    public function list()
    {
        $tours = Tour::getAll();
        include __DIR__ . "/../views/tour/list.php";
    }

    public function detail()
{
    if (!isset($_GET['id'])) {
        echo "Thiếu mã tour";
        return;
    }

    $id = intval($_GET['id']);
    $tour = Tour::getById($id);

    if (!$tour) {
        echo "Tour không tồn tại";
        return;
    }

    $commentModel = new Comment();
    $comments = $commentModel->getByTour($tour['MaTour']); // giờ đã là mảng

    include __DIR__ . "/../views/tour/detail.php";
}

    public function manage()
    {
        $tours = Tour::getAll();
        $viewFile = __DIR__ . "/../views/admin/manage_tour.php";
        include __DIR__ . "/../views/admin/dashboard.php";
    }

}

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

        $result = Tour::getById($id);

        if ($result->num_rows == 0) {
            echo "Tour không tồn tại";
            return;
        }

        $tour = $result->fetch_assoc();
        $commentModel = new Comment();
        $resultComments = $commentModel->getByTour($tour['MaTour']);
        $comments = [];
        while ($row = $resultComments->fetch_assoc()) {
            $comments[] = $row;
        }


        include __DIR__ . "/../views/tour/detail.php";
    }
    public function manage()
    {
        $tours = Tour::getAll();
        $viewFile = __DIR__ . "/../views/admin/manage_tour.php";
        include __DIR__ . "/../views/admin/dashboard.php";
    }

}

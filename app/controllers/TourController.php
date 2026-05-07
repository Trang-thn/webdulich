<?php
require_once __DIR__ . "/../models/Tour.php";
require_once __DIR__ . "/../models/Comment.php";

class TourController
{
    private $model;

    public function __construct() {
        $this->model = new Tour(); // gọi trực tiếp model
    }

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
 // Thêm tour
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->model->insert($_POST, $_FILES); // gọi hàm add trong model
                $_SESSION['toastr'] = ['type' => 'success', 'msg' => 'Thêm tour thành công!'];
                header("Location: /webdulich/tour/manage");
                exit();
            } catch (Exception $e) {
                $error = "Không thể thêm tour: " . $e->getMessage();
                $viewFile = __DIR__ . "/../views/tour/add_tour.php";
                include __DIR__ . "/../views/admin/dashboard.php";
                return;
            }
        }
        $viewFile = __DIR__ . "/../views/tour/add_tour.php";

    if (file_exists($viewFile)) {
        include __DIR__ . "/../views/admin/dashboard.php";
    } else {
        echo "<div style='padding:20px;color:red;font-weight:bold'>
                ❌ Không tìm thấy file view: {$viewFile}
              </div>";
        error_log("Dashboard include lỗi: không tìm thấy file view {$viewFile}");
    }
    }

    // Sửa tour
    public function edit() {
        $viewFile = __DIR__ . "/../views/tour/edit_tour.php";

    if (file_exists($viewFile)) {
        include __DIR__ . "/../views/admin/dashboard.php";
    } else {
        echo "<div style='padding:20px;color:red;font-weight:bold'>
                ❌ Không tìm thấy file view: {$viewFile}
              </div>";
        error_log("Dashboard include lỗi: không tìm thấy file view {$viewFile}");
    }
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
        if (file_exists($viewFile)) {
        include __DIR__ . "/../views/admin/dashboard.php";
    } else {
        echo "<div style='padding:20px;color:red;font-weight:bold'>
                ❌ Không tìm thấy file view: {$viewFile}
              </div>";
        error_log("Dashboard include lỗi: không tìm thấy file view {$viewFile}");
    }
    }

}

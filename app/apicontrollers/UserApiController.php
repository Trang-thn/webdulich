<?php
require_once __DIR__ . "/../models/User.php";

class UserApiController
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }

    // ✅ Lấy danh sách user (có tìm kiếm)
    public function list()
    {
        $keyword = $_GET['keyword'] ?? null;
        $users = $this->model->getAll($keyword);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'success', 'data' => $users], JSON_UNESCAPED_UNICODE);
    }

    // ✅ Thêm user mới
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['Username'] ?? '';
            if ($this->model->existsUsername($username)) {
                echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập đã tồn tại!'], JSON_UNESCAPED_UNICODE);
                return;
            }

            $this->model->add($_POST);
            echo json_encode(['status' => 'success', 'message' => 'Thêm thành viên thành công!'], JSON_UNESCAPED_UNICODE);
        }
    }

    // ✅ Lấy thông tin user theo ID
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $user = $this->model->getById($id);
            echo json_encode(['status' => 'success', 'data' => $user], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu ID'], JSON_UNESCAPED_UNICODE);
        }
    }

    // ✅ Cập nhật user
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->update($_POST);
            echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành viên thành công!'], JSON_UNESCAPED_UNICODE);
        }
    }

    // ✅ Xóa user
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $result = $this->model->delete($_POST['id']);
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Xóa thành viên thành công!'], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không thể xóa thành viên vì đang có đơn đặt tour!'], JSON_UNESCAPED_UNICODE);
            }
        }
    }

    // ✅ Đăng ký user
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';
            $hoten   = $_POST['hoten'] ?? '';
            $email   = $_POST['email'] ?? '';
            $diachi  = $_POST['diachi'] ?? '';
            $socmt   = $_POST['socmt'] ?? '';
            $sodt    = $_POST['sodt'] ?? '';

            if ($this->model->existsUsername($username)) {
                echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập đã tồn tại!'], JSON_UNESCAPED_UNICODE);
                return;
            }

            if ($password !== $confirm) {
                echo json_encode(['status' => 'error', 'message' => 'Mật khẩu nhập lại không khớp'], JSON_UNESCAPED_UNICODE);
                return;
            }

            $this->model->register([
                'Username' => $username,
                'PassWord' => password_hash($password, PASSWORD_DEFAULT),
                'HoTen'    => $hoten,
                'EmailTVien' => $email,
                'DiaChi'   => $diachi,
                'SoCMT'    => $socmt,
                'SoDT'     => $sodt
            ]);


            echo json_encode(['status' => 'success', 'message' => 'Đăng ký thành công!'], JSON_UNESCAPED_UNICODE);
        }
    }
    public function checkUsername()
    {
        $username = $_GET['Username'] ?? '';
        $exists = $this->model->existsUsername($username);
        echo json_encode(['exists' => $exists], JSON_UNESCAPED_UNICODE);
    }
}

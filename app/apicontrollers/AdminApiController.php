<?php
require_once __DIR__ . "/../models/Tour.php";
require_once __DIR__ . "/../models/Gallery.php";
require_once __DIR__ . "/../models/Admin.php";
require_once __DIR__ . "/../models/User.php";

class AdminApiController
{
    private $adminModel;
    private $userModel;

    public function __construct()
    {
        $this->adminModel = new Admin();
        $this->userModel = new User();
    }

    // ✅ API đăng nhập
    public function login()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $admin = $this->adminModel->getByUsername($username);
        if ($admin && $admin['PassAdmin'] === $password) {
            $_SESSION['admin'] = $admin;
            echo json_encode([
                'status' => 'success',
                'role'   => 'admin',
                'redirect' => '/webdulich/dashboard'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $user = $this->userModel->getByUsername($username);
        if ($user && password_verify($password, $user['PassWord'])) {
            $_SESSION['user'] = $user;
            echo json_encode([
                'status' => 'success',
                'role'   => 'user',
                'redirect' => '/webdulich/'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        echo json_encode(['status' => 'error', 'message' => 'Sai tài khoản hoặc mật khẩu'], JSON_UNESCAPED_UNICODE);
    }
}


    // ✅ API đăng ký
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

            if ($this->userModel->existsUsername($username)) {
                echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập đã tồn tại!'], JSON_UNESCAPED_UNICODE);
                return;
            }

            if ($password !== $confirm) {
                echo json_encode(['status' => 'error', 'message' => 'Mật khẩu nhập lại không khớp'], JSON_UNESCAPED_UNICODE);
                return;
            }

            $this->userModel->register([
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'hoten'    => $hoten,
                'email'    => $email,
                'diachi'   => $diachi,
                'socmt'    => $socmt,
                'sodt'     => $sodt
            ]);

            echo json_encode(['status' => 'success', 'message' => 'Đăng ký thành công!'], JSON_UNESCAPED_UNICODE);
        }
    }

    // ✅ API thống kê dashboard
    public function dashboard()
    {
        $stats = [
            'tour'    => $this->adminModel->countTable('TOUR'),
            'user'    => $this->adminModel->countTable('THANHVIEN'),
            'booking' => $this->adminModel->countTable('DATTOUR'),
            'comment' => $this->adminModel->countTable('COMMENT')
        ];

        echo json_encode(['status' => 'success', 'data' => $stats], JSON_UNESCAPED_UNICODE);
    }

    // ✅ API thông tin người dùng
    // public function profile()
    // {
    //     $userId = $_GET['id'] ?? null;
    //     if ($userId) {
    //         $user = $this->userModel->getById($userId);
    //         echo json_encode(['status' => 'success', 'data' => $user], JSON_UNESCAPED_UNICODE);
    //     } else {
    //         echo json_encode(['status' => 'error', 'message' => 'Thiếu ID người dùng'], JSON_UNESCAPED_UNICODE);
    //     }
    // }

}

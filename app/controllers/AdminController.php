<?php
require_once __DIR__ . "/../models/Tour.php";
require_once __DIR__ . "/../models/Gallery.php";
require_once __DIR__ . "/../models/Admin.php";
require_once __DIR__ . "/../models/User.php";

class AdminController
{
    private $adminModel;
    private $userModel;

    public function __construct()
    {
        $this->adminModel = new Admin();
        $this->userModel = new User();
    }

    public function loginForm()
    {
        include __DIR__ . "/../views/user/login.php";
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $admin = $this->adminModel->getByUsername($username);
            if ($admin && $admin['PassAdmin'] === $password) {
                $_SESSION['admin'] = $admin;
                $viewFile = __DIR__ . "/../views/admin/home.php";
                include __DIR__ . "/../views/admin/dashboard.php";
                exit();
            }

            $user = $this->userModel->getByUsername($username);
            if ($user && password_verify($password, $user['PassWord'])) {
                $_SESSION['user'] = $user;
                $tours = Tour::getLimit(8);
                include __DIR__ . "/../views/home/home.php";
                exit();
            }


            $error = "Sai tài khoản hoặc mật khẩu";
            include __DIR__ . "/../views/user/login.php";
        }
    }


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
                $error = "Tên đăng nhập đã tồn tại!";
                include __DIR__ . "/../views/user/register.php";
                return;
            }

            if ($password !== $confirm) {
                $error = "Mật khẩu nhập lại không khớp";
                include __DIR__ . "/../views/user/register.php";
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

            $_SESSION['message'] = "Đăng ký thành công, vui lòng đăng nhập!";
            header("Location: /webdulich/user/login");
            exit();
        } else {
            include __DIR__ . "/../views/user/register.php";
        }
    }


    public function logout()
    {
        unset($_SESSION['admin']);
        unset($_SESSION['user']);
        session_destroy();
        header("Location: /webdulich");
        exit();
    }

    public function dashboard()
    {
        if (!isset($_SESSION['admin'])) {
            header("Location: /webdulich/admin/login");
            exit();
        }

        $stats = [
            'tour'    => $this->adminModel->countTable('TOUR'),
            'user'    => $this->adminModel->countTable('THANHVIEN'),
            'booking' => $this->adminModel->countTable('DATTOUR'),
            'comment' => $this->adminModel->countTable('COMMENT')
        ];

        $viewFile = __DIR__ . "/../views/admin/home.php";
        include __DIR__ . "/../views/admin/dashboard.php";
    }


    public function profile()
    {
        if (isset($_SESSION['user'])) {
            $user = $this->userModel->getById($_SESSION['user']['MaTVien']);
            include "app/views/user/profile.php";
        } else {
            header("Location: /webdulich/admin/login");
            exit();
        }
    }

    public function home() {
    $viewFile = __DIR__ . '/../views/admin/home.php';
    include __DIR__ . "/../views/admin/dashboard.php";
}

    public function admin()
    {
        include __DIR__ . "/../views/admin/dashboard.php";
    }
}

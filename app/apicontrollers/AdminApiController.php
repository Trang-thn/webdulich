<?php
require_once __DIR__ . "/../services/UserService.php";

class AdminApiController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    public function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->userService->login($username, $password);

        if ($result) {
            if ($result['role'] === 'admin') {
                $_SESSION['admin'] = $result['data']; // lưu riêng cho admin
                return $this->jsonResponse(200, [
                    'status' => 'success',
                    'message' => 'Đăng nhập thành công',
                    'role'   => 'admin',
                    'redirect' => '/webdulich/dashboard'
                ]);
            } else {
                $_SESSION['user'] = $result['data']; // lưu riêng cho user
                return $this->jsonResponse(200, [
                    'status' => 'success',
                    'message' => 'Đăng nhập thành công',
                    'role'   => 'user',
                    'redirect' => '/webdulich/'
                ]);
            }
        }

        return $this->jsonResponse(401, [
            'status' => 'error',
            'message' => 'Sai tài khoản hoặc mật khẩu'
        ]);
    }
}


    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->userService->register([
                    'Username' => $_POST['username'] ?? '',
                    'PassWord' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
                    'HoTen'    => $_POST['hoten'] ?? '',
                    'EmailTVien' => $_POST['email'] ?? '',
                    'DiaChi'   => $_POST['diachi'] ?? '',
                    'SoCMT'    => $_POST['socmt'] ?? '',
                    'SoDT'     => $_POST['sodt'] ?? ''
                ]);
                return $this->jsonResponse(201, [
                    'status' => 'success',
                    'message' => 'Đăng ký thành công!'
                ]);
            } catch (Exception $e) {
                return $this->jsonResponse(400, [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }
    }

    public function dashboard() {
        $stats = $this->userService->dashboardStats();
        return $this->jsonResponse(200, [
            'status' => 'success',
            'message' => 'Thống kê thành công',
            'data' => $stats
        ]);
    }

    // ✅ API thông tin người dùng
    public function profile()
{
    $userId = $_GET['id'] ?? null;
    if ($userId) {
        $user = $this->userService->getUserById($userId); // sửa ở đây
        echo json_encode(['status' => 'success', 'data' => $user], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Thiếu ID người dùng'], JSON_UNESCAPED_UNICODE);
    }
}

    private function jsonResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}


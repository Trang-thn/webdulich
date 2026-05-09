<?php
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/Admin.php";

class UserService {
    private $userModel;
    private $adminModel;

    public function __construct() {
        $this->userModel = new User();
        $this->adminModel = new Admin();
    }

    public function getAllUsers($keyword = null) {
        return $this->userModel->getAll($keyword);
    }

    public function getUserById($id) {
        return $this->userModel->getById($id);
    }

    public function createUser($data) {
        // Ví dụ: validate dữ liệu trước khi thêm
        if ($this->userModel->existsUsername($data['Username'])) {
            throw new Exception("Tên đăng nhập đã tồn tại");
        }
        return $this->userModel->add($data);
    }

    public function updateUser($data) {
        return $this->userModel->update($data);
    }

    public function deleteUser($id) {
        return $this->userModel->delete($id);
    }

    public function login($username, $password) {
    // Kiểm tra admin (plain text)
    $admin = $this->adminModel->getByUsername($username);
    if ($admin && $admin['PassAdmin'] === $password) {
        return ['role' => 'admin', 'data' => $admin];
    }

    // Kiểm tra user (có mã hóa)
    $user = $this->userModel->getByUsername($username);
    if ($user && password_verify($password, $user['PassWord'])) {
        return ['role' => 'user', 'data' => $user];
    }

    return null;
}



    public function register($data) {
        if ($this->userModel->existsUsername($data['Username'])) {
            throw new Exception("Tên đăng nhập đã tồn tại!");
        }
        return $this->userModel->register($data);
    }
public function updateProfile($data) {
    return $this->userModel->updateProfile($data['MaTVien'], $data);
}


    public function dashboardStats() {
        return [
            'tour'    => $this->adminModel->countTable('TOUR'),
            'user'    => $this->adminModel->countTable('THANHVIEN'),
            'booking' => $this->adminModel->countTable('DATTOUR'),
            'comment' => $this->adminModel->countTable('COMMENT')
        ];
    }
}

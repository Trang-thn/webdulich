<?php
require_once __DIR__ . "/../services/UserService.php";

class UserApiController {
    private $service;

    public function __construct() {
        $this->service = new UserService();
    }

    public function list() {
        $keyword = $_GET['keyword'] ?? null;
        $users = $this->service->getAllUsers($keyword);
        $this->jsonResponse(200, ['status'=>'success','data'=>$users]);
    }

    public function add() {
        try {
            $this->service->createUser($_POST);
            $this->jsonResponse(201, ['status'=>'success','message'=>'Thêm thành viên thành công!']);
        } catch (Exception $e) {
            $this->jsonResponse(400, ['status'=>'error','message'=>$e->getMessage()]);
        }
    }

    public function detail() {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        return $this->jsonResponse(400, ['status'=>'error','message'=>'Thiếu ID']);
    }
    $user = $this->service->getUserById($id);
    if ($user) {
        $this->jsonResponse(200, ['status'=>'success','data'=>$user]);
    } else {
        $this->jsonResponse(404, ['status'=>'error','message'=>'Không tìm thấy người dùng']);
    }
}


    public function update() {
    try {
        $this->service->updateUser($_POST);
        $this->jsonResponse(200, ['status'=>'success','message'=>'Cập nhật thành viên thành công!']);
    } catch (Exception $e) {
        $this->jsonResponse(400, ['status'=>'error','message'=>$e->getMessage()]);
    }
}

    public function delete() {
        $id = $_POST['id'] ?? null;
        if (!$id) return $this->jsonResponse(400, ['status'=>'error','message'=>'Thiếu ID']);
        $result = $this->service->deleteUser($id);
        if ($result) {
            $this->jsonResponse(200, ['status'=>'success','message'=>'Xóa thành viên thành công!']);
        } else {
            $this->jsonResponse(409, ['status'=>'error','message'=>'Không thể xóa thành viên vì đang có đơn đặt tour!']);
        }
    }

    public function updateProfile() {
        $maTVien = $_POST['MaTVien'] ?? null;
        if (!$maTVien) {
            return $this->jsonResponse(400, [
                'status' => 'error',
                'message' => 'Thiếu mã thành viên'
            ]);
        }

        $data = [
            'MaTVien'    => $maTVien,
            'HoTen'      => $_POST['HoTen'] ?? '',
            'EmailTVien' => $_POST['EmailTVien'] ?? '',
            'DiaChi'     => $_POST['DiaChi'] ?? '',
            'SoCMT'      => $_POST['SoCMT'] ?? '',
            'SoDT'       => $_POST['SoDT'] ?? ''
        ];

        $ok = $this->service->updateProfile($data);
        if ($ok) {
            return $this->jsonResponse(200, [
                'status' => 'success',
                'message' => 'Cập nhật thành công'
                ]);
            } else {
            return $this->jsonResponse(500, [
                'status' => 'error',
                'message' => 'Cập nhật thất bại'
            ]);
        }
    }
    private function jsonResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

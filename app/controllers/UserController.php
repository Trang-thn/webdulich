<?php
require_once __DIR__ . "/../models/User.php";

class UserController
{
    private $model;
    private $db;

    public function __construct()
    {
        $this->model = new User();
    }
    public function manage()
    {
        $keyword = $_GET['keyword'] ?? null;
        $users = $this->model->getAll($keyword);
        $viewFile = __DIR__ . "/../views/admin/manage_user.php";
        include __DIR__ . "/../views/admin/dashboard.php";
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            if ($this->model->existsUsername($username)) {
                $error = "Tên đăng nhập đã tồn tại!";
                include __DIR__ . "/../views/user/register.php";
                return;
            }

            $this->model->add($_POST);
            $_SESSION['toastr'] = ['type' => 'success', 'msg' => 'Thêm thành viên thành công!'];
            header("Location: /webdulich/user/manage");
            exit();
        }
        $viewFile = __DIR__ . "/../views/admin/add_user.php";
        include __DIR__ . "/../views/admin/dashboard.php";
    }

    public function editForm()
    {
        $id = $_GET['id'] ?? null;
        $user = $this->model->getById($id);
        $viewFile = __DIR__ . "/../views/admin/edit_user.php";
        include __DIR__ . "/../views/admin/dashboard.php";
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->update($_POST);
            $_SESSION['toastr'] = ['type' => 'success', 'msg' => 'Cập nhật thành viên thành công!'];
            header("Location: /webdulich/user/manage");
            exit();
        }
    }

    public function editProfile(): void
    {
        $id = $_SESSION['user']['MaTVien'] ?? null;
        $user = $this->model->getById($id);
        $viewFile = __DIR__ . "/../views/admin/edit_user.php";
        include $viewFile;
    }

    public function updateProfile(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user']['MaTVien'] ?? null;
            if ($id == $_POST['MaTVien']) {
                $this->model->update($_POST);
                $_SESSION['toastr'] = ['type' => 'success', 'msg' => 'Cập nhật hồ sơ thành công!'];
                header('Location: /webdulich/profile');
                exit();
            } else {
                echo "Không hợp lệ.";
            }
        }
    }


    public function delete()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $result = $this->model->delete($_POST['id']);
        if ($result) {
            $_SESSION['toastr'] = ['type' => 'success', 'msg' => 'Xóa thành viên thành công!'];
        } else {
            $_SESSION['toastr'] = ['type' => 'error', 'msg' => 'Không thể xóa thành viên vì đang có đơn đặt tour!'];
        }
        header("Location: /webdulich/user/manage");
        exit();
    }
}

    public function registerForm()
    {
        include __DIR__ . "/../views/user/register.php";
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

            if ($this->model->existsUsername($username)) {
                $error = "Tên đăng nhập đã tồn tại!";
                include __DIR__ . "/../views/user/register.php";
                return;
            }

            if ($password !== $confirm) {
                $error = "Mật khẩu nhập lại không khớp";
                include __DIR__ . "/../views/user/register.php";
                return;
            }


            $this->model->register([
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
    public function checkUsername()
    {
        $username = $_GET['username'] ?? '';
        $exists = $this->model->existsUsername($username);
        echo json_encode(['exists' => $exists]);
        exit;
    }

    public function export()
    {
        $keyword = $_GET['keyword'] ?? null;
        $users = $this->model->getAll($keyword);

        require_once __DIR__ . "/../Classes/PHPExcel.php";
        require_once __DIR__ . "/../Classes/PHPExcel/IOFactory.php";

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Users');

        $headers = ['Mã TV', 'Tên đăng nhập', 'Họ tên', 'Email', 'Địa chỉ', 'Số CMT', 'Số ĐT'];
        $col = 0;
        foreach ($headers as $h) {
            $sheet->setCellValueByColumnAndRow($col, 1, $h);
            $col++;
        }

        $row = 2;
        foreach ($users as $u) {
            $sheet->setCellValueByColumnAndRow(0, $row, $u['MaTVien']);
            $sheet->setCellValueByColumnAndRow(1, $row, $u['Username']);
            $sheet->setCellValueByColumnAndRow(2, $row, $u['HoTen']);
            $sheet->setCellValueByColumnAndRow(3, $row, $u['EmailTVien']);
            $sheet->setCellValueByColumnAndRow(4, $row, $u['DiaChi']);
            $sheet->setCellValueByColumnAndRow(5, $row, $u['SoCMT']);
            $sheet->setCellValueByColumnAndRow(6, $row, $u['SoDT']);
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="users.xlsx"');
        header('Cache-Control: max-age=0');
        ob_clean();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function import()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
            require_once __DIR__ . "/../Classes/PHPExcel.php";
            require_once __DIR__ . "/../Classes/PHPExcel/IOFactory.php";

            $file = $_FILES['excel_file']['tmp_name'];
            $objPHPExcel = PHPExcel_IOFactory::load($file);
            $sheet = $objPHPExcel->getActiveSheet();
            $highestRow = $sheet->getHighestRow();

            $errors = [];
            $successCount = 0;

            for ($row = 2; $row <= $highestRow; $row++) {
                $username = trim($sheet->getCellByColumnAndRow(1, $row)->getValue());
                $hoten    = trim($sheet->getCellByColumnAndRow(2, $row)->getValue());
                $email    = trim($sheet->getCellByColumnAndRow(3, $row)->getValue());
                $diachi   = trim($sheet->getCellByColumnAndRow(4, $row)->getValue());
                $socmt    = trim($sheet->getCellByColumnAndRow(5, $row)->getValue());
                $sodt     = trim($sheet->getCellByColumnAndRow(6, $row)->getValue());

                if (!$username) {
                    $errors[] = "Dòng $row: Thiếu tên đăng nhập .";
                    continue;
                }

                if ($this->model->existsUsername($username)) {
                    $errors[] = "Dòng $row: Tên đăng nhập '$username' đã tồn tại.";
                    continue;
                }

                $data = [
                    'username' => $username,
                    'hoten'    => $hoten,
                    'email'    => $email,
                    'diachi'   => $diachi,
                    'socmt'    => $socmt,
                    'sodt'     => $sodt,
                    'password' => password_hash("123456", PASSWORD_DEFAULT)
                ];

                $this->model->add($data);
                $successCount++;
            }

            $_SESSION['toastr'] = [
                'type' => 'info',
                'msg' => "Đã nhập thành công $successCount dòng. Có " . count($errors) . " lỗi."
            ];
            $_SESSION['import_errors'] = $errors;

            header("Location: /webdulich/user/manage");
            exit();
        } else {
            $viewFile = __DIR__ . "/../views/admin/import_user.php";
            include __DIR__ . "/../views/admin/dashboard.php";
        }
    }
}

<?php
require_once __DIR__ . "/../models/Tour.php";
require_once __DIR__ . "/../../config/database.php";

class TourService {
    public function createTour($data, $files) {
    $conn = Database::getConnection();

    $AnhTourList = [];
    $target_dir = __DIR__ . "/../../public/images/images/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!empty($files['AnhTour']['name'][0])) {
        foreach ($files['AnhTour']['name'] as $key => $name) {
            $fileName = time() . "_" . basename($name);
            $target_file = $target_dir . $fileName;
            if (move_uploaded_file($files['AnhTour']['tmp_name'][$key], $target_file)) {
                $AnhTourList[] = $fileName;
            }
        }
    }
    if (!empty($data['NgayKhoiHanh'])) {
    $ngayNhap = strtotime($data['NgayKhoiHanh']);
    $homNay = strtotime(date("m-d-Y"));

    if ($ngayNhap < $homNay) {
        throw new Exception("Ngày khởi hành không hợp lệ.");
    } else {
        $NgayKhoiHanh = date("m-d-Y H:i:s", $ngayNhap);
    }
} else {
    $NgayKhoiHanh = date("m-d-Y H:i:s");
} 
    $MaLoai = isset($data['MaLoai']) ? intval($data['MaLoai']) : 1;

    $sql = "INSERT INTO tour ( TenTour, GiaTour, TGTour, DiemKhoiHanh, NgayKhoiHanh, NoiDungTour, AnhTour)
            VALUES (?, ?, ?, ?, ?,  ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsssss",
        $data['TenTour'],
        $data['GiaTour'],
        $data['TGTour'],
        $data['DiemKhoiHanh'],
        $NgayKhoiHanh,
        $data['NoiDungTour'],
        $AnhTourString
    );

    if (!$stmt->execute()) {
        error_log("Lỗi thêm tour: " . $stmt->error);
        throw new Exception("Không thể thêm tour: " . $stmt->error);
    }

    return true;
}

    public function updateTour($data, $files) {
    $conn = Database::getConnection();

    $NgayKhoiHanh = !empty($data['NgayKhoiHanh'])
        ? date("Y-m-d H:i:s", strtotime($data['NgayKhoiHanh']))
        : null;

    // Xử lý ảnh upload mới
    $AnhTourList = [];
    if (!empty($files['AnhTour']['name'][0])) {
        $target_dir = __DIR__ . "/../../public/images/images/";
        foreach ($files['AnhTour']['name'] as $key => $name) {
            $fileName = time() . "_" . basename($name);
            $target_file = $target_dir . $fileName;
            if (move_uploaded_file($files['AnhTour']['tmp_name'][$key], $target_file)) {
                $AnhTourList[] = $fileName;
            }
        }
    }

    // Nếu không upload ảnh mới → lấy ảnh cũ từ DB
    $MaTour = intval($data['MaTour']);
    if (!empty($AnhTourList)) {
        $AnhTourString = implode(",", $AnhTourList);
    } else {
        $stmtOld = $conn->prepare("SELECT AnhTour FROM tour WHERE MaTour=?");
        $stmtOld->bind_param("i", $MaTour);
        $stmtOld->execute();
        $resultOld = $stmtOld->get_result();
        $rowOld = $resultOld->fetch_assoc();
        $AnhTourString = $rowOld['AnhTour'] ?? '';
    }

    // Câu lệnh UPDATE
    $sql = "UPDATE tour
            SET TenTour=?, GiaTour=?, TGTour=?, DiemKhoiHanh=?, NgayKhoiHanh=?, NoiDungTour=?, AnhTour=? 
            WHERE MaTour=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsssssi",
        $data['TenTour'],
        $data['GiaTour'],
        $data['TGTour'],
        $data['DiemKhoiHanh'],
        $NgayKhoiHanh,
        $data['NoiDungTour'],
        $AnhTourString,
        $MaTour
    );

    if (!$stmt->execute()) {
        throw new Exception("Không thể cập nhật tour: " . $stmt->error);
    }

    return true;
}

public function searchTours($keyword) {
    // Gọi xuống model Tour để thực hiện truy vấn
    return Tour::search($keyword);
}



    public function deleteTour($id) {
        return Tour::delete($id);
    }

    public function getAllTours() {
        return Tour::getAll();
    }

    public function getTourById($id) {
        return Tour::getById($id);
    }
}

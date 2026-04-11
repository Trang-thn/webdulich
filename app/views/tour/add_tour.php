<?php
require_once __DIR__ . "/../../../config/database.php";
$conn = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $TenTour       = $_POST['TenTour'];
    $GiaTour       = $_POST['GiaTour'];
    $TGTour        = $_POST['TGTour'];
    $DiemKhoiHanh  = $_POST['DiemKhoiHanh'];
    $NgayKhoiHanh = $_POST['NgayKhoiHanh'] ?? null;

// Chuyển về định dạng MySQL DATETIME nếu cần
if (!empty($NgayKhoiHanh)) {
    $NgayKhoiHanh = date("Y-m-d H:i:s", strtotime($NgayKhoiHanh));
}

    $NoiDungTour   = $_POST['NoiDungTour'] ?? '';

    // Xử lý nhiều ảnh upload
    $AnhTourList = [];
    if (!empty($_FILES['AnhTour']['name'][0])) {
        $target_dir = __DIR__ . "/../../../public/images/images/";

        foreach ($_FILES['AnhTour']['name'] as $key => $name) {
            $fileName = time() . "_" . basename($name);
            $target_file = $target_dir . $fileName;

            if (move_uploaded_file($_FILES['AnhTour']['tmp_name'][$key], $target_file)) {
                $AnhTourList[] = $fileName;
            }
        }
    }

    // Lưu danh sách ảnh vào DB (chuỗi phân cách bằng dấu phẩy)
    $AnhTourString = implode(",", $AnhTourList);

    // INSERT dữ liệu
    $sql = "INSERT INTO tour (TenTour, GiaTour, TGTour, DiemKhoiHanh, NgayKhoiHanh, NoiDungTour, AnhTour)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsssss", $TenTour, $GiaTour, $TGTour, $DiemKhoiHanh, $NgayKhoiHanh, $NoiDungTour, $AnhTourString);

    if ($stmt->execute()) {
    header("Location: /webdulich/tour/manage?status=success");
    exit;
} else {
    header("Location: /webdulich/tour/manage?status=error");
    exit;
}

    
}
?>

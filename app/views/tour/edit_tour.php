<?php
require_once __DIR__ . "/../../../config/database.php";
$conn = Database::getConnection();

$required = ['MaTour','TenTour','GiaTour','TGTour','DiemKhoiHanh','NgayKhoiHanh'];
foreach ($required as $f) {
    if (!isset($_POST[$f])) {
        header("Location: /webdulich/tour/manage?status=error");
        exit;
    }
}

$MaTour       = intval($_POST['MaTour']);
$TenTour      = mysqli_real_escape_string($conn, $_POST['TenTour']);
$GiaTour      = intval($_POST['GiaTour']);
$TGTour       = mysqli_real_escape_string($conn, $_POST['TGTour']);
$DiemKhoiHanh = mysqli_real_escape_string($conn, $_POST['DiemKhoiHanh']);
$NgayKhoiHanh = mysqli_real_escape_string($conn, $_POST['NgayKhoiHanh']);
$NoiDungTour  = mysqli_real_escape_string($conn, $_POST['NoiDungTour'] ?? "");

if (strpos($NgayKhoiHanh, 'T') !== false) {
    $NgayKhoiHanh = str_replace('T', ' ', $NgayKhoiHanh) . ':00';
}

$oldImages = '';
$res = mysqli_query($conn, "SELECT AnhTour FROM tour WHERE MaTour = {$MaTour} LIMIT 1");
if ($res && mysqli_num_rows($res) === 1) {
    $row = mysqli_fetch_assoc($res);
    $oldImages = $row['AnhTour'];
}
$oldImagesArray = !empty($oldImages) ? explode(",", $oldImages) : [];

$keepImages = isset($_POST['keepImages']) ? array_map('trim', $_POST['keepImages']) : [];

$newImages = [];
$maxFiles  = 10;
$maxSize   = 5 * 1024 * 1024;
$allowedExt = ['jpg','jpeg','png','gif','webp'];

if (!empty($_FILES['AnhTour']['name'][0])) {
    $target_dir = __DIR__ . "/../../../public/images/images/";
    if (!is_dir($target_dir)) {
        @mkdir($target_dir, 0755, true);
    }

    $count = count($_FILES['AnhTour']['name']);
    if ($count > $maxFiles) {
        $count = $maxFiles;
    }

    for ($key = 0; $key < $count; $key++) {
        $name = $_FILES['AnhTour']['name'][$key] ?? '';
        if (empty($name)) continue;
        if ($_FILES['AnhTour']['error'][$key] !== UPLOAD_ERR_OK) continue;
        if ($_FILES['AnhTour']['size'][$key] > $maxSize) continue;

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) continue;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $_FILES['AnhTour']['tmp_name'][$key]);
        finfo_close($finfo);
        if (strpos($mime, 'image/') !== 0) continue;

        $safeName   = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', basename($name));
        $fileName   = time() . "_" . bin2hex(random_bytes(4)) . "_" . $safeName;
        $targetFile = $target_dir . $fileName;

        if (move_uploaded_file($_FILES['AnhTour']['tmp_name'][$key], $targetFile)) {
            $newImages[] = $fileName;
        }
    }
}

if (isset($_POST['replaceAll']) && $_POST['replaceAll'] == "1") {
    $AnhTourString = !empty($newImages) ? implode(",", $newImages) : "";
} else {
    $keepImages = array_values(array_intersect($keepImages, $oldImagesArray));
    $AnhTourString = implode(",", array_merge($keepImages, $newImages));
}

$sql = "UPDATE tour SET 
            TenTour      = ?, 
            GiaTour      = ?, 
            TGTour       = ?, 
            DiemKhoiHanh = ?,
            NgayKhoiHanh = ?, 
            NoiDungTour  = ?, 
            AnhTour      = ?
        WHERE MaTour = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdsssssi", $TenTour, $GiaTour, $TGTour, $DiemKhoiHanh, $NgayKhoiHanh, $NoiDungTour, $AnhTourString, $MaTour);

if ($stmt->execute()) {
    header("Location: /webdulich/tour/manage?status=success");
    exit;
} else {
    header("Location: /webdulich/tour/manage?status=error");
    exit;
}

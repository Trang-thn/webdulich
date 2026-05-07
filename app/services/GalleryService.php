<?php
require_once __DIR__ . "/../models/Gallery.php";

class GalleryService {
    private $galleryModel;

    public function __construct() {
        $this->galleryModel = new Gallery();
    }

    // Lấy tất cả ảnh
    public function getAllImages() {
        $result = $this->galleryModel->getAll();
        $images = [];
        while ($row = $result->fetch_assoc()) {
            $images[] = $row;
        }
        return $images;
    }

    // Lấy ảnh theo tour
    public function getImagesByTour($maTour) {
        $result = $this->galleryModel->getByTour($maTour);
        $images = [];
        while ($row = $result->fetch_assoc()) {
            $images[] = $row;
        }
        return $images;
    }

    // Thêm ảnh mới
    public function addImage($maTour, $file, $moTa) {
        // xử lý upload file
        $target_dir = __DIR__ . "/../../public/images/images/";
        $fileName = time() . "_" . basename($file['name']);
        $target_file = $target_dir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return $this->galleryModel->insert($maTour, $fileName, $moTa);
        } else {
            throw new Exception("Không thể upload ảnh");
        }
    }

    // Xóa ảnh
    public function deleteImage($id) {
        return $this->galleryModel->delete($id);
    }
}

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../models/Tour.php";
require_once __DIR__ . "/../models/DatTour.php";
require_once __DIR__ . "/../models/ChiTietDat.php";

class BookingService
{
    private $datTourModel;
    private $chiTietModel;
    private $tourModel;

    public function __construct()
    {
        $this->datTourModel = new DatTour();
        $this->chiTietModel = new ChiTietDat();
        $this->tourModel = new Tour();

        // Thiết lập timezone mặc định
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function getTourById($id)
    {
        return $this->tourModel->getTourById($id);
    }

    // Tạo bản ghi dattour (chỉ dùng nội bộ)
    private function createDatTour($maTVien)
    {
        if (empty($maTVien) || !is_numeric($maTVien)) {
            throw new Exception("Mã thành viên không hợp lệ");
        }
        return $this->datTourModel->createDatTour($maTVien);
    }

    public function deleteDatTour($maDat)
    {
        if (empty($maDat) || !is_numeric($maDat)) {
            throw new Exception("Mã đặt tour không hợp lệ");
        }
        return $this->datTourModel->deleteDatTour($maDat);
    }

    // Lấy danh sách booking (admin)
    public function getAllBookings($keyword = null)
    {
        return $this->datTourModel->getAllBookings($keyword);
    }

    // Lấy chi tiết booking theo ID
    public function getBookingById($maDat)
    {
        $booking = $this->datTourModel->getBookingById($maDat);
        $chiTiet = $this->chiTietModel->getChiTietByMaDat($maDat);
        if ($booking && $chiTiet) {
            $booking['ChiTiet'] = $chiTiet;
        }
        return $booking;
    }

    // Lấy lịch sử booking của user
    public function getBookingsByUser($maTVien)
    {
        return $this->datTourModel->getBookingsByUser($maTVien);
    }

    // Tạo booking mới
    public function createBooking($data, $maTVien)
    {
        // Validate dữ liệu
        if (empty($data['tour_id']) || empty($data['soLuongKhach']) || empty($data['ngayDi'])) {
            throw new Exception("Thiếu dữ liệu bắt buộc");
        }
        if (!is_numeric($data['soLuongKhach']) || $data['soLuongKhach'] <= 0) {
            throw new Exception("Số lượng khách không hợp lệ");
        }

        if (strtotime($data['ngayDi']) <= strtotime(date("Y-m-d"))) {
            throw new Exception("Ngày đi không hợp lệ, phải lớn hơn ngày hôm nay");
        }

        // Kiểm tra tour có tồn tại không
        $tour = $this->tourModel->getTourById($data['tourId']);
        if (!$tour) {
            throw new Exception("Tour không tồn tại");
        }

        // Tạo bản ghi trong dattour
        $maDat = $this->createDatTour($maTVien);

        // Thêm chi tiết đặt tour
        $success = $this->chiTietModel->addChiTiet(
            $maDat,
            $data['tourId'],
            $data['ngayDi'],
            $data['soLuongKhach'],
            $data['capKS'] ?? null,
            $data['khac'] ?? null
        );

        if (!$success) {
            // rollback nếu thêm chi tiết thất bại
            $this->datTourModel->deleteDatTour($maDat);
            throw new Exception("Không thể thêm chi tiết đặt tour");
        }

        return $maDat;
    }

    // Cập nhật booking
    public function updateBooking($maDat, $data)
    {
        if (empty($maDat) || empty($data['ngayDi'])) {
            throw new Exception("Thiếu dữ liệu");
        }
        if (strtotime($data['ngayDi']) <= strtotime(date("Y-m-d"))) {
            throw new Exception("Ngày đi không hợp lệ, phải lớn hơn ngày hôm nay");
        }

        if (!empty($data['soLuongKhach']) && (!is_numeric($data['soLuongKhach']) || $data['soLuongKhach'] <= 0)) {
            throw new Exception("Số lượng khách không hợp lệ");
        }

        return $this->chiTietModel->updateChiTiet(
            $maDat,
            $data['soLuongKhach'] ?? 1,
            $data['ngayDi'],
            $data['capKS'] ?? null,
            $data['khac'] ?? null
        );
    }

    // Hủy booking
    public function cancelBooking($maDat)
    {
        if (empty($maDat)) {
            throw new Exception("Thiếu mã đặt");
        }
        $this->chiTietModel->deleteByMaDat($maDat);
        return $this->datTourModel->deleteDatTour($maDat);
    }
}

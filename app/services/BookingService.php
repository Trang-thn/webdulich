<?php
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
        $this->tourModel    = new Tour();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    private function validateBooking($data, $requireTour = false)
    {
        if (empty($data['ngayDi']) || strtotime($data['ngayDi']) <= strtotime(date("Y-m-d"))) {
            throw new Exception("Ngày đi không hợp lệ, ngày đi phải lớn hơn ngày hôm nay");
        }
        if (!empty($data['soLuongKhach']) && (!is_numeric($data['soLuongKhach']) || $data['soLuongKhach'] <= 0)) {
            throw new Exception("Số lượng khách không hợp lệ");
        }
        if ($requireTour && empty($data['tour_id'])) {
            throw new Exception("Thiếu mã tour");
        }
    }

    public function createBooking($data, $maTVien, $isAdmin = false)
    {
        $this->validateBooking($data, true);

        if (!$this->tourModel->getTourById($data['tour_id'])) {
            throw new Exception("Tour không tồn tại");
        }
        $existing = $this->datTourModel->findByUserTourDate($maTVien, $data['tour_id'], $data['ngayDi']);
        if ($existing && !$isAdmin) {
            throw new Exception("Bạn đã đặt tour này cho ngày đi đó rồi!");
        }
        $maDat = $this->datTourModel->createDatTour($maTVien);
        if (!$this->chiTietModel->addChiTiet(
            $maDat,
            $data['tour_id'],
            $data['ngayDi'],
            $data['soLuongKhach'],
            $data['capKS'] ?? null,
            $data['khac'] ?? null
        )) {
            $this->datTourModel->deleteDatTour($maDat);
            throw new Exception("Không thể thêm chi tiết đặt tour");
        }
        return $maDat;
    }

    public function updateBooking($maDat, $data, $isAdmin = false)
    {
        if (empty($maDat)) throw new Exception("Thiếu mã đặt");
        $this->validateBooking($data);
        $booking = $this->getBookingById($maDat);
        if (!$booking) throw new Exception("Không tìm thấy booking");
        $ngayDi = new DateTime($booking['NgayDi']);
        $today  = new DateTime();
        if (!$isAdmin && $ngayDi <= $today) {
            throw new Exception("Tour đã diễn ra, không thể thay đổi!");
        }
        $tourId = $booking['MaTour'];
        $existing = $this->datTourModel->findByUserTourDate($booking['MaTVien'], $tourId, $data['ngayDi']);
        if ($existing && $existing['MaDat'] != $maDat && !$isAdmin) {
            throw new Exception("Bạn đã đặt tour này cho ngày đi đó rồi!");
        }
        return $this->chiTietModel->updateChiTiet(
            $maDat,
            $data['soLuongKhach'] ?? 1,
            $data['ngayDi'],
            $data['capKS'] ?? null,
            $data['khac'] ?? null
        );
    }

    public function cancelBooking($maDat, $isAdmin = false)
    {
        if (empty($maDat)) throw new Exception("Thiếu mã đặt");
        $booking = $this->getBookingById($maDat);
        if (!$booking) throw new Exception("Không tìm thấy booking");
        $ngayDi = new DateTime($booking['NgayDi']);
        $today  = new DateTime();
        $diff   = $today->diff($ngayDi)->format('%r%a');
        $diff = (int)$today->diff($ngayDi)->format('%r%a');

        if (!$isAdmin && $diff < 3) {

            throw new Exception(
                "Tour còn dưới hoặc =3 ngày, bạn không thể hủy!"
            );
        }

        $this->chiTietModel->deleteByMaDat($maDat);
        return $this->datTourModel->deleteDatTour($maDat);
    }


    public function getAllBookings($keyword = null)
    {
        return $this->datTourModel->getAllBookings($keyword);
    }

    public function getBookingById($maDat)
    {
        return $this->datTourModel->getBookingById($maDat);
    }

    public function getBookingsByUser($maTVien)
    {
        return $this->datTourModel->getBookingsByUser($maTVien);
    }
}

<?php
require_once __DIR__ . "/../models/DatTour.php";
require_once __DIR__ . "/../models/ChiTietDat.php";
require_once __DIR__ . "/../models/Tour.php";

class BookingApiController
{
    // ✅ Tạo booking mới
    public function createBooking()
    {
        $tourId = $_POST['tour_id'] ?? null;
        $soLuongKhach = $_POST['soLuongKhach'] ?? null;
        $ngayDi = $_POST['ngayDi'] ?? null;

        if (!$tourId || !$soLuongKhach || !$ngayDi) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu'], JSON_UNESCAPED_UNICODE);
            return;
        }

        if (strtotime($ngayDi) < strtotime(date("Y-m-d"))) {
            echo json_encode(['status' => 'error', 'message' => 'Ngày đi không hợp lệ'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $capKS = $_POST['capKS'] ?? '';
        $khac = $_POST['khac'] ?? '';
        $maTVien = $_SESSION['user']['MaTVien'] ?? null;

        if (!$maTVien) {
            echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập để đặt tour!'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $datTourModel = new DatTour();
        $maDat = $datTourModel->createDatTour($maTVien);

        $chiTietModel = new ChiTietDat();
        $chiTietModel->addChiTiet($maDat, $tourId, $ngayDi, $soLuongKhach, $capKS, $khac);

        echo json_encode(['status' => 'success', 'message' => 'Đặt tour thành công!', 'maDat' => $maDat], JSON_UNESCAPED_UNICODE);
    }

    // ✅ Cập nhật booking
    public function updateBooking()
    {
        $maDat = $_POST['maDat'] ?? null;
        $soLuongKhach = $_POST['soLuongKhach'] ?? null;
        $ngayDi = $_POST['ngayDi'] ?? null;
        $capKS = $_POST['capKS'] ?? '';
        $khac = $_POST['khac'] ?? '';

        if (!$maDat || !$ngayDi) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu'], JSON_UNESCAPED_UNICODE);
            return;
        }

        if (strtotime($ngayDi) < strtotime(date("Y-m-d"))) {
            echo json_encode(['status' => 'error', 'message' => 'Ngày đi không hợp lệ'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $chiTietModel = new ChiTietDat();
        $chiTietModel->updateChiTiet($maDat, $soLuongKhach, $ngayDi, $capKS, $khac);

        echo json_encode(['status' => 'success', 'message' => "Đơn đặt #$maDat đã được cập nhật thành công!"], JSON_UNESCAPED_UNICODE);
    }

    // ✅ Hủy booking
    public function cancelBooking()
    {
        $maDat = $_POST['maDat'] ?? null;
        if (!$maDat) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu mã đặt'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $chiTietModel = new ChiTietDat();
        $chiTietModel->deleteByMaDat($maDat);

        $datTourModel = new DatTour();
        $datTourModel->deleteDatTour($maDat);

        echo json_encode(['status' => 'success', 'message' => "Đơn đặt #$maDat đã được hủy thành công!"], JSON_UNESCAPED_UNICODE);
    }

    // ✅ Lịch sử booking của user
    public function userHistory()
    {
        $maTVien = $_SESSION['user']['MaTVien'] ?? null;
        if (!$maTVien) {
            echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $datTourModel = new DatTour();
        $bookings = $datTourModel->getBookingsByUser($maTVien);

        echo json_encode(['status' => 'success', 'data' => $bookings], JSON_UNESCAPED_UNICODE);
    }

    // ✅ Quản lý booking (admin)
    public function manage()
    {
        $datTourModel = new DatTour();
        $keyword = $_GET['keyword'] ?? null;
        $bookings = $datTourModel->getAllBookings($keyword);

        echo json_encode(['status' => 'success', 'data' => $bookings], JSON_UNESCAPED_UNICODE);
    }

    // ✅ Chi tiết booking
    public function detail()
    {
        $maDat = $_GET['maDat'] ?? null;
        if (!$maDat) {
            echo json_encode(['status' => 'error', 'message' => 'Thiếu mã đặt'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $datTourModel = new DatTour();
        $booking = $datTourModel->getBookingById($maDat);

        echo json_encode(['status' => 'success', 'data' => $booking], JSON_UNESCAPED_UNICODE);
    }
}

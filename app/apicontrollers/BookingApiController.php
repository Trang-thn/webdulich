<?php
require_once __DIR__ . "/../services/BookingService.php";

class BookingApiController
{
    private $service;

    public function __construct()
    {
        $this->service = new BookingService();
    }

    public function create()
    {
        try {
            $maTVien = $_SESSION['user']['MaTVien'] ?? null;
            if (!$maTVien) {
                return $this->jsonResponse(401, ['status' => 'error', 'message' => 'Bạn cần đăng nhập để đặt tour!']);
            }
            $data = json_decode(file_get_contents("php://input"), true);
            $maDat = $this->service->createBooking($data, $maTVien);
            return $this->jsonResponse(201, ['status' => 'success', 'message' => 'Đặt tour thành công!', 'maDat' => $maDat]);
        } catch (Exception $e) {
            return $this->jsonResponse(400, ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $maDat = $data['maDat'] ?? null;
            if (!$maDat) return $this->jsonResponse(400, ['status' => 'error', 'message' => 'Thiếu mã đặt']);

            $booking = $this->service->getBookingById($maDat);
            if (!$booking) return $this->jsonResponse(404, ['status' => 'error', 'message' => 'Không tìm thấy booking']);

            $ngayDi = strtotime($booking['NgayDi']);
            $today  = strtotime(date("Y-m-d"));

            if ($ngayDi <= $today) {
                return $this->jsonResponse(403, [
                    'status' => 'error',
                    'message' => 'Tour đã diễn ra, không thể thay đổi!'
                ]);
            }


            $this->service->updateBooking($maDat, $data);
            return $this->jsonResponse(200, ['status' => 'success', 'message' => "Đơn đặt #$maDat đã được cập nhật thành công!"]);
        } catch (Exception $e) {
            return $this->jsonResponse(400, ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function cancel()
    {
        try {

            $data = json_decode(file_get_contents("php://input"), true);

            $maDat = $data['maDat'] ?? null;

            if (!$maDat) {

                return $this->jsonResponse(400, [
                    'status'  => 'error',
                    'message' => 'Thiếu mã đặt'
                ]);
            }
            $isAdmin = isset($_SESSION['admin']);
            $this->service->cancelBooking($maDat, $isAdmin);

            return $this->jsonResponse(200, [
                'status'  => 'success',
                'message' => "Đơn đặt #$maDat đã được hủy thành công!"
            ]);
        } catch (Exception $e) {

            return $this->jsonResponse(400, [
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function userHistory()
    {
        try {
            $maTVien = $_SESSION['user']['MaTVien'] ?? null;
            if (!$maTVien) return $this->jsonResponse(401, ['status' => 'error', 'message' => 'Bạn cần đăng nhập']);
            $bookings = $this->service->getBookingsByUser($maTVien);
            return $this->jsonResponse(200, ['status' => 'success', 'data' => $bookings]);
        } catch (Exception $e) {
            return $this->jsonResponse(400, ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function manage()
    {
        try {
            $keyword = $_GET['keyword'] ?? null;
            $bookings = $this->service->getAllBookings($keyword);
            return $this->jsonResponse(200, ['status' => 'success', 'data' => $bookings]);
        } catch (Exception $e) {
            return $this->jsonResponse(400, ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function detail($maDat = null)
    {
        try {
            if (!$maDat) $maDat = $_GET['maDat'] ?? null;
            if (!$maDat) return $this->jsonResponse(400, ['status' => 'error', 'message' => 'Thiếu mã đặt']);
            $booking = $this->service->getBookingById($maDat);
            if ($booking) {
                return $this->jsonResponse(200, ['status' => 'success', 'data' => $booking]);
            } else {
                return $this->jsonResponse(404, ['status' => 'error', 'message' => 'Không tìm thấy booking']);
            }
        } catch (Exception $e) {
            return $this->jsonResponse(400, ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    private function jsonResponse($statusCode, $data)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

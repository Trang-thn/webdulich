<?php
require_once __DIR__ . "/../services/BookingService.php";

class BookingApiController
{
    private $service;

    public function __construct()
    {
        $this->service = new BookingService();
    }

    // ✅ Tạo booking mới
    public function create()
    {
        try {
            $maTVien = $_SESSION['user']['MaTVien'] ?? null;
            if (!$maTVien) {
                return $this->jsonResponse(401, ['status' => 'error', 'message' => 'Bạn cần đăng nhập để đặt tour!']);
            }

            $data = [
                'tourId'       => $_POST['tour_id'] ?? null,
                'soLuongKhach' => $_POST['soLuongKhach'] ?? null,
                'ngayDi'       => $_POST['ngayDi'] ?? null,
                'capKS'        => $_POST['capKS'] ?? null,
                'khac'         => $_POST['khac'] ?? null
            ];
            if (empty($data['ngayDi'])) {
                throw new Exception("Ngày đi không hợp lệ, vui lòng chọn ngày đi!");
            }

            if (strtotime($data['ngayDi']) <= strtotime(date("Y-m-d"))) {
                throw new Exception("Ngày đi không hợp lệ, phải lớn hơn ngày hôm nay");
            }


            $maDat = $this->service->createBooking($data, $maTVien);

            return $this->jsonResponse(201, [
                'status' => 'success',
                'message' => 'Đặt tour thành công!',
                'maDat' => $maDat
            ]);
        } catch (Exception $e) {
            return $this->jsonResponse(400, ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // ✅ Cập nhật booking
    public function update()
    {
        try {
            $maDat = $_POST['maDat'] ?? null;
            if (!$maDat) {
                return $this->jsonResponse(400, ['status' => 'error', 'message' => 'Thiếu mã đặt']);
            }

            $data = [
                'soLuongKhach' => $_POST['soLuongKhach'] ?? null,
                'ngayDi'       => $_POST['ngayDi'] ?? null,
                'capKS'        => $_POST['capKS'] ?? null,
                'khac'         => $_POST['khac'] ?? null
            ];
            if (empty($data['ngayDi'])) {
                throw new Exception("Ngày đi không hợp lệ, vui lòng chọn ngày đi!");
            }

            if (strtotime($data['ngayDi']) <= strtotime(date("Y-m-d"))) {
                throw new Exception("Ngày đi không hợp lệ, phải lớn hơn ngày hôm nay");
            }


            $this->service->updateBooking($maDat, $data);
            return $this->jsonResponse(200, ['status' => 'success', 'message' => "Đơn đặt #$maDat đã được cập nhật thành công!"]);
        } catch (Exception $e) {
            return $this->jsonResponse(400, ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // ✅ Hủy booking
    public function cancel()
    {
        try {
            $maDat = $_POST['maDat'] ?? null;
            if (!$maDat) {
                return $this->jsonResponse(400, ['status' => 'error', 'message' => 'Thiếu mã đặt']);
            }
            $this->service->cancelBooking($maDat);
            return $this->jsonResponse(200, ['status' => 'success', 'message' => "Đơn đặt #$maDat đã được hủy thành công!"]);
        } catch (Exception $e) {
            return $this->jsonResponse(400, ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // ✅ Lịch sử booking của user
    public function userHistory()
    {
        try {
            $maTVien = $_SESSION['user']['MaTVien'] ?? null;
            if (!$maTVien) {
                return $this->jsonResponse(401, ['status' => 'error', 'message' => 'Bạn cần đăng nhập']);
            }
            $bookings = $this->service->getBookingsByUser($maTVien);
            return $this->jsonResponse(200, ['status' => 'success', 'data' => $bookings]);
        } catch (Exception $e) {
            return $this->jsonResponse(400, ['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // ✅ Quản lý booking (admin)
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

    // ✅ Chi tiết booking
    public function detail()
    {
        try {
            $maDat = $_GET['maDat'] ?? null;
            if (!$maDat) {
                return $this->jsonResponse(400, ['status' => 'error', 'message' => 'Thiếu mã đặt']);
            }
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

    // ✅ Helper trả JSON
    private function jsonResponse($statusCode, $data)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

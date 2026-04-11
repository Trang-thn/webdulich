<?php
require_once __DIR__ . "/../models/DatTour.php";
require_once __DIR__ . "/../models/ChiTietDat.php";
require_once __DIR__ . "/../models/Tour.php";

class BookingController
{
    public function form()
    {
        $tourId = $_GET['tour_id'] ?? null;
        if (!$tourId) {
            echo "Thiếu tour_id";
            return;
        }
        $tour = Tour::getTourById($tourId);
        if (!$tour) {
            echo "Tour không tồn tại";
            return;
        }

        include __DIR__ . "/../views/booking/form.php";
    }


    public function createBooking()
    {
        $tourId = $_POST['tour_id'];
        $soLuongKhach = $_POST['soLuongKhach'];
        $ngayDi = $_POST['ngayDi'];
        if (strtotime($ngayDi) < strtotime(date("Y-m-d"))) {
            $_SESSION['toastr'] = ['type' => 'warning', 'msg' => "⚠️ Ngày đi không hợp lệ, phải lớn hơn hoặc bằng ngày hiện tại!"];
            header("Location: /webdulich/booking/form?tour_id=$tourId");
            exit;
        }

        $capKS = $_POST['capKS'];
        $khac = $_POST['khac'];

        $maTVien = $_SESSION['user']['MaTVien'] ?? null;
        if (!$maTVien) {
            echo "<script>alert('Bạn cần đăng nhập để đặt tour!'); window.location.href='/webdulich/user/login';</script>";
            return;
        }


        $datTourModel = new DatTour();
        $maDat = $datTourModel->createDatTour($maTVien);

        $chiTietModel = new ChiTietDat();
        $chiTietModel->addChiTiet($maDat, $tourId, $ngayDi, $soLuongKhach, $capKS, $khac);

        $tourModel = new Tour();
        $tour = $tourModel->getTourById($tourId);

        include __DIR__ . "/../views/booking/success.php";
    }

    public function updateBooking()
    {
        $maDat = $_POST['maDat'];
        $soLuongKhach = $_POST['soLuongKhach'];
        $ngayDi = $_POST['ngayDi'];
        $capKS = $_POST['capKS'];
        $khac = $_POST['khac'];
        $source = $_POST['source'] ?? 'edit';
        $tourId = $_POST['tour_id'] ?? null;
        if (strtotime($ngayDi) < strtotime(date("Y-m-d"))) {
            $_SESSION['toastr'] = ['type' => 'warning', 'msg' => "⚠️ Ngày đi không hợp lệ, phải lớn hơn hoặc bằng ngày hiện tại!"];
            header("Location: /webdulich/booking/edit?maDat=$maDat");
            exit;
        }

        $chiTietModel = new ChiTietDat();
        $chiTietModel->updateChiTiet($maDat, $soLuongKhach, $ngayDi, $capKS, $khac);

        $_SESSION['toastr'] = ['type' => 'success', 'msg' => "✅ Đơn đặt #$maDat đã được cập nhật thành công!"];

        if ($source === 'success' && $tourId) {
            header("Location: /webdulich/booking/success?maDat=$maDat&tour_id=$tourId");
        } else {
            header("Location: /webdulich/booking/manage");
        }
        exit;
    }

    public function success()
    {
        $maDat = $_GET['maDat'] ?? null;
        $tourId = $_GET['tour_id'] ?? null;
        $tourModel = new Tour();
        $tour = $tourModel->getTourById($tourId);

        $chiTietModel = new ChiTietDat();
        $booking = $chiTietModel->getChiTietByMaDat($maDat);
        $soLuongKhach = $booking['SoLuongKhach'];
        $ngayDi = $booking['NgayDi'];
        $capKS = $booking['CapKS'];
        $khac = $booking['Khac'];

        include __DIR__ . "/../views/booking/success.php";
    }
    public function successEdit()
    {
        $maDat = $_GET['maDat'] ?? null;
        $tourId = $_GET['tour_id'] ?? null;

        if (!$maDat || !$tourId) {
            echo "<div class='alert alert-danger'>Thiếu tham số maDat hoặc tour_id!</div>";
            return;
        }

        $tourModel = new Tour();
        $tour = $tourModel->getTourById($tourId);

        $chiTietModel = new ChiTietDat();
        $booking = $chiTietModel->getChiTietByMaDat($maDat);

        if (!$tour || !$booking) {
            echo "<div class='alert alert-danger'>Không tìm thấy dữ liệu booking hoặc tour!</div>";
            return;
        }

        $soLuongKhach = $booking['SoLuongKhach'];
        $ngayDi       = $booking['NgayDi'];
        $capKS        = $booking['CapKS'];
        $khac         = $booking['Khac'];

        include __DIR__ . "/../views/booking/success.php";
    }




    public function cancelBooking()
    {
        $maDat = $_POST['maDat'];
        $tourId = $_POST['tour_id'] ?? null;
        $source = $_POST['source'] ?? 'manage';

        $chiTietModel = new ChiTietDat();
        $chiTietModel->deleteByMaDat($maDat);

        $datTourModel = new DatTour();
        $datTourModel->deleteDatTour($maDat);

        $_SESSION['toastr'] = ['type' => 'success', 'msg' => "✅ Đơn đặt #$maDat đã được hủy thành công!"];

        if ($source === 'success') {
            header("Location: /webdulich/booking/form?tour_id=$tourId");
        } else {
            header("Location: /webdulich/booking/manage");
        }
    }
    public function cancelAndGoHome()
    {
        $maDat = $_POST['maDat'] ?? null;

        if (!$maDat) {
            echo "<div class='alert alert-danger'>Không tìm thấy đơn đặt!</div>";
            return;
        }
        $chiTietModel = new ChiTietDat();
        $chiTietModel->deleteByMaDat($maDat);
        $datTourModel = new DatTour();
        $datTourModel->deleteDatTour($maDat);
        // Thêm thông báo cho khách hàng
        $_SESSION['toastr'] = ['type' => 'success', 'msg' => "✅ Đơn đặt #$maDat đã được hủy thành công!"];
        header("Location: /webdulich/booking/userHistory");
        exit;
    }

    public function history()
    {
        $datTourModel = new DatTour();
        $bookings = $datTourModel->getAllBookings();
        $viewFile = __DIR__ . "/../views/booking/history.php";
        include __DIR__ . "/../views/admin/dashboard.php";
    }
    public function userHistory()
    {
        $maTVien = $_SESSION['user']['MaTVien'] ?? null;
        if (!$maTVien) {
            header("Location: /webdulich/user/login");
            exit;
        }

        $datTourModel = new DatTour();
        $bookings = $datTourModel->getBookingsByUser($maTVien);

        include __DIR__ . "/../views/booking/user_history.php";
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['maDat'])) {
            $maDat = intval($_POST['maDat']);

            $datTourModel = new DatTour();
            $datTourModel->deleteDatTour($maDat);

            $_SESSION['toastr'] = ['type' => 'success', 'msg' => "✅ Đơn đặt #$maDat đã được xóa!"];

            header("Location: /webdulich/booking/history");
            exit;
        }
    }


    public function manage()
    {
        $datTourModel = new DatTour();
        $keyword = $_GET['keyword'] ?? null;
        $bookings = $datTourModel->getAllBookings($keyword);
        if ($keyword && empty($bookings)) {
            $_SESSION['toastr'] = [
                'type' => 'warning',
                'msg'  => "⚠️ Không tìm thấy đơn đặt tour nào với từ khóa '$keyword'"
            ];
        }
        $viewFile = __DIR__ . "/../views/booking/manage_booking.php";
        include __DIR__ . "/../views/admin/dashboard.php";
    }


    public function export()
    {

        $datTourModel = new DatTour();
        $keyword = $_GET['keyword'] ?? null;
        $bookings = $datTourModel->getAllBookings($keyword);

        require_once __DIR__ . "/../Classes/PHPExcel.php";
        require_once __DIR__ . "/../Classes/PHPExcel/IOFactory.php";

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->setTitle('Bookings');
        $headers = ['Mã đặt','Họ tên','Email','Tên tour','Giá tour','Ngày đặt','Ngày đi','Số lượng khách','Cấp KS','Yêu cầu khác'];
        $col = 0;
        foreach ($headers as $h) {
            $sheet->setCellValueByColumnAndRow($col, 1, $h);
            $col++;
        }

        $row = 2;
        foreach ($bookings as $b) {
            $sheet->setCellValueByColumnAndRow(0, $row, $b['MaDat']);
            $sheet->setCellValueByColumnAndRow(1, $row, $b['HoTen']);
            $sheet->setCellValueByColumnAndRow(2, $row, $b['EmailTVien']);
            $sheet->setCellValueByColumnAndRow(3, $row, $b['TenTour']);
            $sheet->setCellValueByColumnAndRow(4, $row, $b['GiaTour']);
            $sheet->setCellValueByColumnAndRow(5, $row, $b['NgayDat']);
            $sheet->setCellValueByColumnAndRow(6, $row, $b['NgayDi']);
            $sheet->setCellValueByColumnAndRow(7, $row, $b['SoLuongKhach']);
            $sheet->setCellValueByColumnAndRow(8, $row, $b['CapKS']);
            $sheet->setCellValueByColumnAndRow(9, $row, $b['Khac']);
            $row++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="bookings.xlsx"');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function detail()
    {
        $maDat = $_GET['maDat'] ?? null;
        if (!$maDat) {
            echo "<div class='alert alert-danger'>Không tìm thấy đơn đặt!</div>";
            return;
        }

        $datTourModel = new DatTour();
        $booking = $datTourModel->getBookingById($maDat);

        $viewFile = __DIR__ . "/../views/booking/detail.php";
        include __DIR__ . "/../views/admin/dashboard.php";
    }
    public function editForm()
    {
        $maDat = $_GET['maDat'] ?? null;
        if (!$maDat) {
            echo "<div class='alert alert-danger'>Không tìm thấy đơn đặt!</div>";
            return;
        }

        $datTourModel = new DatTour();
        $booking = $datTourModel->getBookingById($maDat);

        $viewFile = __DIR__ . "/../views/booking/edit.php";
        include __DIR__ . "/../views/admin/dashboard.php";
    }
}

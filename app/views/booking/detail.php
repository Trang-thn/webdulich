<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn đặt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/webdulich/public/css/detail_booking.css">
</head>

<body>

    <div class="container booking-detail">
        <h3 id="booking-title">Chi tiết đơn đặt</h3>
        <ul class="list-group" id="booking-info"></ul>
        <div class="mt-4 text-center">
            <a href="/webdulich/booking/manage" class="btn btn-secondary">
                ⬅️ Quay lại quản lý đơn đặt
            </a>
        </div>
    </div>
    <script>
        const params = new URLSearchParams(window.location.search);
        const maDat = params.get('maDat');

        async function apiRequest(url) {
            const res = await fetch(url);
            return res.json();
        }

        function renderBookingDetail(b) {
            return `
            <li class="list-group-item"><strong>Khách hàng:</strong> ${b.HoTen}</li>
            <li class="list-group-item"><strong>Email:</strong> ${b.EmailTVien}</li>
            <li class="list-group-item"><strong>Tour:</strong> ${b.TenTour}</li>
            <li class="list-group-item"><strong>Giá tour:</strong>
                <span class="price">${Number(b.GiaTour).toLocaleString('vi-VN')} đ</span>
            </li>
            <li class="list-group-item"><strong>Ngày đặt:</strong> ${b.NgayDat}</li>
            <li class="list-group-item"><strong>Ngày đi:</strong> ${b.NgayDi}</li>
            <li class="list-group-item"><strong>Số lượng khách:</strong> ${b.SoLuongKhach}</li>
            <li class="list-group-item"><strong>Cấp khách sạn:</strong> ${b.CapKS}</li>
            <li class="list-group-item"><strong>Yêu cầu khác:</strong> ${b.Khac ?? ''}</li>
        `;
        }

        function showError(message) {
            document.getElementById('booking-title').textContent = message;
        }

        async function loadBooking(maDat) {
            try {
                const data = await apiRequest('/webdulich/api/bookings/detail?maDat=' + maDat);
                if (data.status === 'success') {
                    const b = data.data;
                    document.getElementById('booking-title').innerHTML =
                        'Chi tiết đơn đặt <span>#' + b.MaDat + '</span>';
                    document.getElementById('booking-info').innerHTML = renderBookingDetail(b);
                } else {
                    showError(data.message);
                }
            } catch (err) {
                showError('Lỗi khi tải dữ liệu: ' + err);
            }
        }

        if (maDat) loadBooking(maDat);
    </script>
</body>

</html>
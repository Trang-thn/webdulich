<?php include __DIR__ . "/../home/home_menu.php"; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lịch sử đặt tour</title>
    <link rel="stylesheet" href="/webdulich/public/css/user_history.css">
</head>

<body class="auth-background">
    <div class="history-container">
        <h2>Lịch sử đặt tour của bạn</h2>
        <div class="history-scroll">
            <table class="history-table" id="history-table" style="display:none;">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Tên tour</th>
                        <th>Ngày đặt</th>
                        <th>Ngày đi</th>
                        <th>Số lượng khách</th>
                        <th>Giá tour</th>
                        <th>Khách sạn</th>
                        <th>Yêu cầu khác</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="history-body"></tbody>
            </table>
        </div>
        <p id="no-booking" style="text-align:center; color:#7f8c8d; display:none;">Bạn chưa có đơn đặt tour nào.</p>
    </div>
</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        fetch('/webdulich/api/bookings/user')
            .then(res => res.json())
            .then(resp => {
                if (resp.status === 'success' && resp.data.length > 0) {
                    $('#history-table').show();
                    const tbody = $('#history-body');
                    resp.data.forEach(b => {
                        tbody.append(`
                        <tr>
                            <td>${b.MaDat}</td>
                            <td>${b.TenTour}</td>
                            <td>${b.NgayDat.split(' ')[0]}</td>
                            <td>${b.NgayDi.split(' ')[0]}</td>
                            <td>${b.SoLuongKhach}</td>
                            <td>${Number(b.GiaTour).toLocaleString()} VND</td>
                            <td>${b.CapKS}</td>
                            <td>${b.Khac || ''}</td>
                            <td>
                                <a href="/webdulich/booking/success?maDat=${b.MaDat}&tour_id=${b.MaTour}" class="btn update" style="background:#f39c12;">✏️ Sửa</a>
                                <button class="btn cancel" onclick="cancelBooking(${b.MaDat})">❌ Hủy</button>
                            </td>
                        </tr>
                    `);
                    });
                } else {
                    $('#no-booking').show();
                }
            });
    });

    function cancelBooking(maDat) {
        if (!confirm('Bạn chắc chắn muốn hủy đơn #' + maDat + '?')) return;
        fetch('/webdulich/api/bookings/cancel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    maDat
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    toastr.success(data.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error(data.message);
                }
            })
            .catch(err => toastr.error('Lỗi kết nối API: ' + err));
    }
</script>
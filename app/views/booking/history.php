<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lịch sử đặt tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/webdulich/public/css/history.css">
</head>

<body>
    <div class="history-container">
        <h2>Lịch sử đặt tour</h2>
        <table class="table table-bordered table-striped align-middle" id="history-table" style="display:none;">
            <thead>
                <tr>
                    <th>Mã đặt</th>
                    <th>Tên khách</th>
                    <th>Email</th>
                    <th>Tên tour</th>
                    <th>Ngày đặt</th>
                    <th>Ngày đi</th>
                    <th>Số lượng khách</th>
                    <th>Cấp KS</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody id="booking-body"></tbody>
        </table>
        <p id="no-booking" style="text-align:center; color:#7f8c8d; display:none;">Không có đơn đặt tour nào.</p>
    </div>

    <script>
        async function loadBookings() {
            const res = await fetch('/webdulich/api/bookings/admin');
            const json = await res.json();
            const tbody = document.getElementById('booking-body');
            tbody.innerHTML = '';

            if (json.status === 'success' && json.data.length > 0) {
                document.getElementById('history-table').style.display = 'table';
                json.data.forEach(b => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                <td>${b.MaDat}</td>
                <td>${b.HoTen}</td>
                <td>${b.EmailTVien}</td>
                <td>${b.TenTour}</td>
                <td>${b.NgayDat ? new Date(b.NgayDat).toLocaleDateString() : '-'}</td>
                <td>${b.NgayDi ? new Date(b.NgayDi).toLocaleDateString() : '-'}</td>
                <td>${b.SoLuongKhach ?? '-'}</td>
                <td>${b.CapKS ?? '-'}</td>
                <td>${b.NgayDi ? '<span class="badge bg-success">Đã đặt</span>' : '<span class="badge bg-danger">Đã hủy</span>'}</td>
            `;
                    tbody.appendChild(tr);
                });
            } else {
                document.getElementById('no-booking').style.display = 'block';
            }
        }

        loadBookings();
    </script>
</body>

</html>
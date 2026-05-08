<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn đặt tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/webdulich/public/css/manage_booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body>
    <h2>Quản lý đơn đặt tour</h2>
    <div class="search-bar">
        <input type="text" id="keyword" class="form-control" placeholder="Tìm theo Mã đặt, Tên khách, Tên tour">
        <button onclick="loadBookings()" class="btn btn-primary">🔍 Tìm kiếm</button>
        <button id="exportExcel" class="btn btn-success">📊 Xuất Excel</button>
        <button onclick="resetSearch()" class="btn btn-secondary">🔄 Làm mới</button>
    </div>
    <div class="manage-container">
        <table class="table table-bordered table-striped align-middle" id="booking-table" style="display:none;">
            <thead>
                <tr>
                    <th>Mã đặt</th>
                    <th>Tên khách</th>
                    <th>Tour</th>
                    <th>Giá tour</th>
                    <th>Ngày đi</th>
                    <th>Số lượng khách</th>
                    <th>Yêu cầu khác</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="booking-body"></tbody>
        </table>
        <p id="no-booking" style="text-align:center; color:#7f8c8d; display:none;">Không có dữ liệu</p>
    </div>

    <script>
        function toggleTable(show) {
            document.getElementById('booking-table').style.display = show ? 'table' : 'none';
            document.getElementById('no-booking').style.display = show ? 'none' : 'block';
        }

        function renderBookingRow(b) {
            return `
            <tr>
                <td>${b.MaDat}</td>
                <td>${b.HoTen}</td>
                <td>${b.TenTour}</td>
                <td>${Number(b.GiaTour).toLocaleString()} đ</td>
                <td>${b.NgayDi ? new Date(b.NgayDi).toLocaleDateString() : '-'}</td>
                <td>${b.SoLuongKhach}</td>
                <td>${b.Khac ?? ''}</td>
                <td>
                    <a href="/webdulich/booking/edit?maDat=${b.MaDat}" class="btn btn-warning btn-sm">Sửa</a>
                    <button class="btn btn-danger btn-sm" onclick="cancelBooking(${b.MaDat})">Hủy</button>
                    <a href="/webdulich/booking/detail?maDat=${b.MaDat}" class="btn btn-info btn-sm">Chi tiết</a>
                </td>
            </tr>`;
        }

        function showToast(type, message) {
            toastr[type](message);
        }

        async function loadBookings() {
            const keyword = document.getElementById('keyword').value;
            const res = await fetch('/webdulich/api/bookings/admin?keyword=' + encodeURIComponent(keyword));
            const json = await res.json();
            const tbody = document.getElementById('booking-body');
            tbody.innerHTML = '';

            if (json.status === 'success' && json.data.length > 0) {
                toggleTable(true);
                tbody.innerHTML = json.data.map(renderBookingRow).join('');
            } else {
                toggleTable(false);
            }
        }

        async function cancelBooking(maDat) {
            if (!confirm("Bạn có chắc chắn muốn hủy tour này không?")) return;

            const res = await fetch('/webdulich/api/bookings/cancel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    maDat
                })
            });

            const json = await res.json();
            showToast(json.status, json.message);

            if (json.status === 'success') setTimeout(loadBookings, 1000);
        }

        function resetSearch() {
            document.getElementById('keyword').value = '';
            loadBookings();
        }

        loadBookings();
    </script>

</body>

</html>
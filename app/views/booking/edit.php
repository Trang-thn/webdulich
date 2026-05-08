<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa đơn đặt tour</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="/webdulich/public/css/edit.css">
</head>

<body>

    <div class="container edit-booking">
        <h2>✏️ Sửa đơn đặt tour <span id="booking-id"></span></h2>
        <form id="edit-form">
            <input type="hidden" name="maDat">
            <div class="mb-3">
                <label class="form-label">Số lượng khách</label>
                <input type="number" name="soLuongKhach" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày đi</label>
                <input type="date" name="ngayDi" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Cấp khách sạn</label>
                <select name="capKS" class="form-select">
                    <option value="3*">3 sao</option>
                    <option value="4*">4 sao</option>
                    <option value="5*">5 sao</option>
                </select>


            </div>

            <div class="mb-3">
                <label class="form-label">Yêu cầu khác</label>
                <textarea name="khac" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
            <a href="/webdulich/booking/manage" class="btn btn-secondary">⬅ Quay lại</a>
        </form>
        <div id="msg"></div>
    </div>

    <script>
        const params = new URLSearchParams(window.location.search);
        const maDat = params.get('maDat');

        async function apiRequest(url, options = {}) {
            const res = await fetch(url, options);
            return res.json();
        }

        function fillForm(b) {
            document.getElementById('booking-id').textContent = '#' + b.MaDat;
            document.querySelector('[name="maDat"]').value = b.MaDat;
            document.querySelector('[name="soLuongKhach"]').value = b.SoLuongKhach;
            document.querySelector('[name="ngayDi"]').value = b.NgayDi.substring(0, 10);
            document.querySelector('[name="capKS"]').value = b.CapKS;
            document.querySelector('[name="khac"]').value = b.Khac ?? '';
        }

        function showToast(type, message) {
            toastr[type](message);
        }

        async function loadBooking(maDat) {
            const data = await apiRequest('/webdulich/api/bookings/detail?maDat=' + maDat);
            if (data.status === 'success') {
                fillForm(data.data);
            } else {
                document.getElementById('msg').textContent = data.message;
            }
        }

        document.getElementById('edit-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            const resp = await apiRequest('/webdulich/api/bookings/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            showToast(resp.status, resp.message);
        });

        if (maDat) loadBooking(maDat);
    </script>

</body>

</html>

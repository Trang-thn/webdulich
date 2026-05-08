<?php
$maDat = $_GET['maDat'] ?? null;
if (!$maDat) {
    echo "<div class='alert alert-danger'>Thiếu mã đặt!</div>";
    return;
}
?>
<?php include __DIR__ . "/../home/home_menu.php"; ?>
<div class="background-slideshow"></div>
<div class="booking-success">
    <div class="alert luxury-alert">
        <h4>🎉 Đơn đặt tour</h4>
        <p>Mã đơn: <strong id="booking-id"></strong></p>
        <p id="tour-name"></p>
    </div>
    <div id="msg"></div>
    <form id="update-form">
        <input type="hidden" name="maDat">
        <div class="mb-3">
            <label>Số lượng khách</label>
            <input type="number" name="soLuongKhach" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label>Ngày đi</label>
            <input type="date" name="ngayDi" min="<?= date('Y-m-d') ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Cấp khách sạn</label>
            <select name="capKS" class="form-select">
                <option value="3*">3 sao</option>
                <option value="4*">4 sao</option>
                <option value="5*">5 sao (Luxury)</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Yêu cầu khác</label>
            <textarea name="khac" class="form-control" rows="3"></textarea>
        </div>
        <div class="booking-actions">
            <button type="submit" class="btn luxury-btn-warning">💾 Lưu thay đổi</button>
        </div>
    </form>
    <div class="booking-actions">
        <form id="cancel-form">
            <input type="hidden" name="maDat">
            <button type="submit" class="btn luxury-btn-danger">❌ Hủy tour</button>
        </form>
        <a href="/webdulich/" class="btn luxury-btn-secondary">⬅️ Thoát</a>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="/webdulich/public/js/api.js"></script>
<script>
    const params = new URLSearchParams(window.location.search);
    const maDat = params.get('maDat');
    async function apiRequest(url, options = {}) {
        const res = await fetch(url, options);
        return res.json();
    }

    function showToast(type, message) {
        toastr[type](message);
    }

    function fillForm(b, tour) {
        document.getElementById('booking-id').textContent = '#' + b.MaDat;
        document.getElementById('tour-name').innerHTML = `Tour: <strong>${tour.TenTour}</strong>`;
        document.querySelector('#update-form [name="maDat"]').value = b.MaDat;
        document.querySelector('#cancel-form [name="maDat"]').value = b.MaDat;
        document.querySelector('[name="soLuongKhach"]').value = b.SoLuongKhach;
        document.querySelector('[name="ngayDi"]').value = b.NgayDi.substring(0, 10);
        document.querySelector('[name="capKS"]').value = b.CapKS;
        document.querySelector('[name="khac"]').value = b.Khac ?? '';
        if (tour.AnhTour) {
            const images = tour.AnhTour.split(',').map(i => i.trim());
            const slideshow = document.querySelector('.background-slideshow');
            let i = 0;

            function changeBg() {
                slideshow.style.backgroundImage = `url('/webdulich/public/images/images/${images[i]}')`;
                i = (i + 1) % images.length;
            }
            changeBg();
            setInterval(changeBg, 5000);
        }
    }
    async function loadBooking(maDat) {
        try {
            const bookingData = await apiRequest('/webdulich/api/bookings/detail?maDat=' + maDat);
            if (bookingData.status !== 'success') {
                document.getElementById('msg').innerHTML = `<div class="alert alert-danger">${bookingData.message}</div>`;
                return;
            }
            const booking = bookingData.data;
            const tourData = await apiRequest('/webdulich/api/tours/detail?id=' + booking.MaTour);
            if (tourData.status !== 'success') {
                document.getElementById('msg').innerHTML = `<div class="alert alert-danger">Không tìm thấy tour</div>`;
                return;
            }
            fillForm(booking, tourData.data);
        } catch (err) {
            document.getElementById('msg').innerHTML = `<div class="alert alert-danger">Lỗi API: ${err.message}</div>`;
        }
    }
    document.getElementById('update-form').addEventListener('submit', async e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        try {
            const resp = await apiRequest('/webdulich/api/bookings/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            showToast(resp.status, resp.message);
        } catch (err) {
            showToast('error', 'Lỗi kết nối API: ' + err.message);
        }
    });
    document.getElementById('cancel-form').addEventListener('submit', async e => {
        e.preventDefault();
        if (!confirm("Bạn có chắc muốn hủy tour?")) return;
        const maDat = e.target.querySelector('[name=maDat]').value;
        try {
            const resp = await apiRequest('/webdulich/api/bookings/cancel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    maDat
                })
            });
            if (resp.status === 'success') {
                showToast('success', resp.message);
                setTimeout(() => {
                    location.href = '/webdulich/';
                }, 1500);
            } else {
                showToast('error', resp.message);
            }
        } catch (err) {
            showToast('error', 'Lỗi API: ' + err.message);
        }
    });
    loadBooking(maDat);
</script>
<?php include __DIR__ . "/../home/home_footer.php"; ?>
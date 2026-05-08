<?php
$maDat = $_GET['maDat'] ?? null;
$tourId = $_GET['tour_id'] ?? null;

if (!$maDat || !$tourId) {
    echo "<div class='alert alert-danger'>Thiếu tham số!</div>";
    return;
}

// Gọi API booking detail
$bookingJson = file_get_contents("http://localhost/webdulich/api/bookings/$maDat");
$bookingData = json_decode($bookingJson, true);

if (!$bookingData || $bookingData['status'] !== 'success') {
    echo "<div class='alert alert-danger'>Không tìm thấy đơn đặt!</div>";
    return;
}
$booking = $bookingData['data'];
$soLuongKhach = $booking['SoLuongKhach'];
$ngayDi = $booking['NgayDi'];
$capKS = $booking['CapKS'];
$khac = $booking['Khac'];

// Gọi API tour detail
$tourJson = file_get_contents("http://localhost/webdulich/api/tours/$tourId");
$tourData = json_decode($tourJson, true);
$tour = $tourData['data'] ?? null;

if (!$tour) {
    echo "<div class='alert alert-danger'>Không tìm thấy tour!</div>";
    return;
}

$images = explode(",", $tour['AnhTour']);
$images = array_map('trim', $images);
?>
<?php include __DIR__ . "/../home/home_menu.php"; ?>

<div class="background-slideshow" data-images='<?= json_encode($images) ?>'></div>

<div class="booking-success">
    <div class="alert luxury-alert">
        <h4>🎉 Đặt tour thành công!</h4>
        <p>Bạn đã đặt tour: <strong><?= htmlspecialchars($tour['TenTour']) ?></strong></p>
    </div>

    <!-- Form cập nhật -->
    <form id="update-form">
        <input type="hidden" name="maDat" value="<?= $maDat ?>">
        <input type="hidden" name="tourId" value="<?= $tour['MaTour'] ?>">

        <div class="mb-3">
            <label>Số lượng khách</label>
            <input type="number" name="soLuongKhach" value="<?= $soLuongKhach ?>" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label>Ngày đi</label>
            <input type="date" name="ngayDi" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime($ngayDi)) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Cấp khách sạn</label>
            <select name="capKS" class="form-select">
                <option value="3*" <?= $capKS == "3*" ? "selected" : "" ?>>3 sao</option>
                <option value="4*" <?= $capKS == "4*" ? "selected" : "" ?>>4 sao</option>
                <option value="5*" <?= $capKS == "5*" ? "selected" : "" ?>>5 sao (Luxury)</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Yêu cầu khác</label>
            <textarea name="khac" class="form-control" rows="3"><?= htmlspecialchars($khac) ?></textarea>
        </div>

        <div class="booking-actions">
            <button type="submit" class="btn luxury-btn-warning">💾 Lưu thay đổi</button>
        </div>
    </form>

    <!-- Form hủy -->
    <div class="booking-actions">
        <form id="cancel-form">
            <input type="hidden" name="maDat" value="<?= $maDat ?>">
            <button type="submit" class="btn luxury-btn-danger">❌ Hủy tour</button>
        </form>
        <a href="/webdulich/" class="btn luxury-btn-secondary">⬅️ Thoát</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
document.getElementById('update-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    fetch('/webdulich/api/bookings/' + data.maDat, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.status === 'success') {
            toastr.success(resp.message);
        } else {
            toastr.error(resp.message);
        }
    });
});

document.getElementById('cancel-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const maDat = this.querySelector('[name=maDat]').value;
    if (!confirm("Bạn có chắc chắn muốn hủy tour này không?")) return;

    fetch('/webdulich/api/bookings/' + maDat, { method: 'DELETE' })
    .then(res => res.json())
    .then(resp => {
        if (resp.status === 'success') {
            toastr.success(resp.message);
            setTimeout(() => window.location.href = '/webdulich/', 1500);
        } else {
            toastr.error(resp.message);
        }
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const slideshow = document.querySelector(".background-slideshow");
    const images = JSON.parse(slideshow.dataset.images);
    let index = 0;
    function changeBackground() {
        slideshow.style.backgroundImage = `url('/webdulich/public/images/images/${images[index]}')`;
        index = (index + 1) % images.length;
    }
    changeBackground();
    setInterval(changeBackground, 5000);
});
</script>

<?php include __DIR__ . "/../home/home_footer.php"; ?>

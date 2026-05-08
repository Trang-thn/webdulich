<?php
if (!isset($tour) || !$tour) {
    echo "<div class='alert'>Không tìm thấy thông tin tour.</div>";
    return;
}
$tourId = $_GET['tour_id'] ?? null;
$images = explode(",", $tour['AnhTour']);
$images = array_map('trim', $images);
?>
<?php include __DIR__ . "/../home/home_menu.php"; ?>

<div class="background-slideshow" data-images='<?= json_encode($images) ?>'></div>

<div class="luxury-booking">
    <h2>ĐẶT TOUR CAO CẤP</h2>
    <p class="subtitle">Trải nghiệm dịch vụ đẳng cấp</p>

    <form id="booking-form">
        <input type="hidden" name="tour_id" value="<?= htmlspecialchars($tourId) ?>">

        <div class="luxury-group">
            <label>Số lượng khách</label>
            <input type="number" name="soLuongKhach" min="1" required>
        </div>

        <div class="luxury-group">
            <label>Ngày khởi hành</label>
            <input type="date" name="ngayDi" min="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="luxury-group">
            <label>Cấp khách sạn</label>
            <select name="capKS">
                <option value="3 sao">3 sao</option>
                <option value="4 sao">4 sao</option>
                <option value="5 sao">5 sao (Luxury)</option>
            </select>
        </div>

        <div class="luxury-group">
            <label>Yêu cầu đặc biệt</label>
            <textarea name="khac" rows="4" placeholder="Ăn chay, phòng VIP, xe riêng..."></textarea>
        </div>

        <button type="submit" class="luxury-btn">Xác nhận đặt tour</button>
    </form>

    <div id="booking-msg" class="mt-3 text-center"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
document.getElementById("booking-form").addEventListener("submit", async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    const msg = document.getElementById("booking-msg");
    msg.textContent = "Đang xử lý...";
    msg.style.color = "blue";

    try {
        const res = await fetch("/webdulich/api/bookings/create", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        });
        const json = await res.json();
        if (json.status === "success") {
            toastr.success(json.message || "Đặt tour thành công!");
            msg.textContent = json.message;
            msg.style.color = "green";
            if (json.maDat) {
                setTimeout(() => window.location.href = "/webdulich/booking/success?maDat=" + json.maDat, 1500);
            }
        } else {
            toastr.error(json.message || "Đặt tour thất bại!");
            msg.textContent = json.message;
            msg.style.color = "red";
        }
    } catch (err) {
        toastr.error("Lỗi kết nối API: " + err.message);
        msg.textContent = "Lỗi kết nối API: " + err.message;
        msg.style.color = "red";
    }
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
    setInterval(changeBackground, 4000);
});
</script>

<?php include __DIR__ . "/../home/home_footer.php"; ?>

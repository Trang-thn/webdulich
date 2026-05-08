<?php
if (!isset($tour) || !$tour) {
    echo "<div class='alert'>Không tìm thấy thông tin tour.</div>";
    return;
}
$tourId = $_GET['tour_id'] ?? null;
$images = array_map('trim', explode(",", $tour['AnhTour']));
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
                <option value="3*">3 sao</option>
                <option value="4*">4 sao</option>
                <option value="5*">5 sao (Luxury)</option>
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
    async function apiRequest(url, options = {}) {
        const res = await fetch(url, options);
        return res.json();
    }

    function showToast(type, message) {
        toastr[type](message);
    }

    document.getElementById("booking-form").addEventListener("submit", async function(e) {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(this).entries());

        try {
            const json = await apiRequest("/webdulich/api/bookings/create", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            });
            if (json.status === "success") {
                showToast("success", json.message || "Đặt tour thành công!");
                if (json.maDat) {
                    setTimeout(() => {
                        window.location.href = `/webdulich/booking/success?maDat=${json.maDat}&tour_id=${data.tour_id}`;
                    }, 1500);
                }
            } else {
                showToast("error", json.message || "Đặt tour thất bại!");
            }
        } catch (err) {
            showToast("error", "Lỗi kết nối API: " + err.message);
        }
    });

    function initSlideshow(images) {
        const slideshow = document.querySelector(".background-slideshow");
        let index = 0;

        function changeBackground() {
            slideshow.style.backgroundImage = `url('/webdulich/public/images/images/${images[index]}')`;
            index = (index + 1) % images.length;
        }
        changeBackground();
        setInterval(changeBackground, 4000);
    }

    document.addEventListener("DOMContentLoaded", () => {
        const images = JSON.parse(document.querySelector(".background-slideshow").dataset.images);
        initSlideshow(images);
    });
</script>

<?php include __DIR__ . "/../home/home_footer.php"; ?>
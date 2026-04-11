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
<div class="background-slideshow"
    data-images='<?= json_encode($images) ?>'></div>


<div class="luxury-booking">
    <h2>ĐẶT TOUR CAO CẤP</h2>
    <p class="subtitle">Trải nghiệm dịch vụ đẳng cấp</p>

    <form action="/webdulich/booking/create" method="POST">
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<?php if (!empty($_SESSION['toastr'])): ?>
    <script>
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };
            toastr["<?= $_SESSION['toastr']['type'] ?>"]("<?= $_SESSION['toastr']['msg'] ?>");
        });
    </script>
    <?php unset($_SESSION['toastr']); ?>
<?php endif; ?>
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
<?php
$daysDiff = (strtotime($ngayDi) - time()) / (60 * 60 * 24);
$disabled = $daysDiff <= 7 ? 'disabled' : '';
$images = explode(",", $tour['AnhTour']);
$images = array_map('trim', $images);
?>

<?php include __DIR__ . "/../home/home_menu.php"; ?>
<div class="background-slideshow"
    data-images='<?= json_encode($images) ?>'></div>
    <div class=" booking-success">
        <div class="alert luxury-alert">
            <h4>🎉 Đặt tour thành công!</h4>
            <p>Bạn đã đặt tour: <strong><?= htmlspecialchars($tour['TenTour']) ?></strong></p>
        </div>

        <form action="/webdulich/booking/update" method="POST">
            <input type="hidden" name="maDat" value="<?= $maDat ?>">
            <input type="hidden" name="tour_id" value="<?= $tour['MaTour'] ?>">

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
                <input type="hidden" name="source" value="success">
                <input type="hidden" name="tour_id" value="<?= $tour['MaTour'] ?>">
                <button type="submit" class="btn luxury-btn-warning">💾 Lưu thay đổi</button>
            </div>
        </form>


        <div class="booking-actions">

            <form action="/webdulich/booking/cancel" method="POST" onsubmit="return confirmCancel();">
                <input type="hidden" name="maDat" value="<?= $maDat ?>">
                <input type="hidden" name="ngayDi" value="<?= $ngayDi ?>">
                <input type="hidden" name="tour_id" value="<?= $tour['MaTour'] ?>">
                <input type="hidden" name="source" value="success">
                <button type="submit" class="btn luxury-btn-danger" >❌ Hủy tour</button>
            </form>

            <a href="/webdulich/" class="btn luxury-btn-secondary">⬅️ Thoát</a>
        </div>
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

function confirmCancel() {
    return confirm("Bạn có chắc chắn muốn hủy tour này không?");
}
</script>

</script>

<?php include __DIR__ . "/../home/home_footer.php"; ?>
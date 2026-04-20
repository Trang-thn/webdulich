<?php include __DIR__ . "/../home/home_banner.php"; ?>

<div class="detail-wrapper">

    <!-- LEFT: Thông tin tour -->
    <div class="detail-left">

        <h1 class="tour-title"><?= $tour['TenTour'] ?></h1>

        <div class="tour-meta">
            <p><b>⏰ Thời gian:</b> <?= $tour['TGTour'] ?></p>
            <p><b>📍 Điểm xuất phát:</b> <?= $tour['DiemKhoiHanh'] ?></p>
            <p>🛫
                <?= !empty($tour['NgayKhoiHanh'])
                    ? date('d/m/Y', strtotime($tour['NgayKhoiHanh']))
                    : 'Chưa có ngày khởi hành' ?>
            </p>
        </div>
        <div class="tour-gallery">
            <?php
            if (!empty($tour['AnhTour'])) {
                $images = explode(",", $tour['AnhTour']);
                foreach ($images as $img) {
                    $img = trim($img);
                    if ($img !== "") {
                        echo "<img src='/webdulich/public/images/images/$img' alt='Ảnh tour' style='width:200px; margin:5px; border-radius:6px;'>";
                    }
                }
            }
            ?>
        </div>


        <div class="tour-content">
            <h2>📌 Chương trình tour</h2>
            <?= nl2br($tour['NoiDungTour']) ?>
        </div>

    </div>

    <!-- RIGHT: Hướng dẫn & hỗ trợ -->
    <div class="detail-right">

        <div class="box">
            <h3>📘 HƯỚNG DẪN ĐẶT TOUR</h3>
            <ul>
                <li>Quý khách để lại thông tin đặt tour.</li>
                <li>Nhân viên CSKH sẽ liên hệ xác nhận.</li>
                <li>Chưa cần thanh toán ngay.</li>
                <li>Hotline: <b>+8433 233 7357</b></li>
            </ul>
        </div>

        <div class="box">
            <h3>📞 HỖ TRỢ KHÁCH HÀNG</h3>
            <p>HN: <b>+8433 233 7357</b></p>
            <p>QT: <b>+19722026548</b></p>
            <p>Email: <b>info@dongphuongtours.com</b></p>

            <div class="price-big">
                <?= number_format($tour['GiaTour'], 0, ',', '.') ?>đ
            </div>

            <a href="/webdulich/booking/form?tour_id=<?= $tour['MaTour'] ?>" class="btn-book">Đặt tour ngay</a>
            <a href="/webdulich/tour" class="btn-back">⬅ Quay lại danh sách tour</a>
        </div>
        <div class="tour-comments mt-5">
            <h2>💬 Bình luận cho tour này</h2>

            <!-- Thông báo sau khi gửi bình luận -->
            <?php if (!empty($_SESSION['comment_message'])): ?>
                <div class="alert alert-info">
                    <?= $_SESSION['comment_message']; ?>
                </div>
                <?php unset($_SESSION['comment_message']); ?>
            <?php endif; ?>

            <!-- Form thêm bình luận -->
            <form method="POST" action="/webdulich/comment/add">
                <input type="hidden" name="maTour" value="<?= $tour['MaTour'] ?>">
                <label for="noiDungCom">Nội dung bình luận</label>
                <textarea name="noiDungCom" id="noiDungCom" required></textarea>

                <label for="vote">Đánh giá</label>
                <select name="vote" id="vote" required>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="1">⭐</option>
                </select>

                <button type="submit" class="btn btn-primary mt-2">Gửi bình luận</button>
            </form>

            <!-- Danh sách bình luận -->
            <div class="comment-list mt-4">
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $c): ?>
                        <?php if ($c['TrangThai'] == 1): ?> <!-- chỉ hiện bình luận đã duyệt -->
                            <div class="comment-item border-bottom py-2">
                                <b><?= htmlspecialchars($c['Username']) ?>:</b>
                                <p><?= htmlspecialchars($c['NoiDungCom']) ?></p>
                                <span>Đánh giá: <?= str_repeat("⭐", $c['Vote']) ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Chưa có bình luận nào cho tour này.</p>
                <?php endif; ?>
            </div>

        </div>

    </div>
</div>
    <?php include __DIR__ . "/../home/home_footer.php"; ?>
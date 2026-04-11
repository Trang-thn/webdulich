<?php include __DIR__ . "/../home/home_banner.php"; ?>

<h2 class="title">🌏 Danh sách Tour Du Lịch</h2>
<div class="tour-grid">
    <?php while ($tour = $tours->fetch_assoc()): ?>
        <?php
        $images = explode(",", $tour['AnhTour']);
        $firstImage = trim($images[0]);
        ?>
        <div class="tour-card">
            <img src="/webdulich/public/images/images/<?= $firstImage ?>"
                alt="<?= $tour['TenTour'] ?>">


            <div class="tour-info">
                <h3><?= $tour['TenTour'] ?></h3>

                <p>📍 <?= $tour['DiemKhoiHanh'] ?></p>
                <p>⏰ <?= $tour['TGTour'] ?></p>
                <p>🛫
                    <?= !empty($tour['NgayKhoiHanh'])
                        ? date('d/m/Y', strtotime($tour['NgayKhoiHanh']))
                        : 'Chưa có ngày khởi hành' ?>
                </p>
                <div class="price">
                    <?= number_format($tour['GiaTour']) ?>đ
                </div>
            </div>

            <a class="btn"
                href="/webdulich/detail?id=<?= $tour['MaTour'] ?>">
                Chi tiết
            </a>

            <a class="btn btn-book"
                href="/webdulich/booking/form?tour_id=<?= $tour['MaTour'] ?>">
                Đặt tour
            </a>

        </div>
    <?php endwhile; ?>
</div>
<?php include __DIR__ . "/../home/home_footer.php"; ?>
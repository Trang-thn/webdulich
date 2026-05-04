<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="/webdulich/public/css/home.css">
   <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
   <!--  phần top-->
   <div class="admin">
      <?php if (isset($_SESSION['user'])): ?>
         <div class="dropdown">
            <a href="#" class="dropdown-toggle">
               <i class='bx bxs-user'></i>
               <?= htmlspecialchars($_SESSION['user']['Username']) ?>
            </a>
            <div class="dropdown-menu">
               <?php if (isset($_SESSION['user'])): ?>
                  <a href="/webdulich/booking/userHistory">Lịch sử đặt tour</a>
               <?php endif; ?>
               <a href="/webdulich/profile">Thông tin cá nhân</a>
               <a href="/webdulich/logout">Đăng xuất</a>
            </div>
         </div>
      <?php else: ?>
         <i class='bx bxs-user' style="color:black"></i>
         <p><a href="/webdulich/user/login" style="color:black">Đăng nhập</a></p>
      <?php endif; ?>
   </div>

   <!--phần logo-->
   <div class="logo">
      <h1>DU LỊCH VIỆT NAM </h1>

   </div>
   <!--phần menu-->
   <div class="container">
      <div id="divLeft">
         <ul>
            <li><a href="/webdulich/">Trang chủ</a> </li>
            <li><a href="/webdulich/tour"> Điểm đến </a></li>
         </ul>
      </div>
      <div id="divRight">
         <form action="/webdulich/search" method="GET">
            <input type="text" name="keyword" id="txtSearch" placeholder="Search">
            <button type="submit" id="btnsearch">
               <i class='bx bx-search'></i>
            </button>
         </form>
      </div>


   </div>

   <!--phần hình ảnh banner-->
   <div class="banner" id="home">
      <img src="/webdulich/public/images/images/dalat.1.jpg" alt="sp">



      <!--linh thêm -->

   </div>
   <?php if (!empty($_GET['keyword'])): ?>
      <h2 class="title">🔍 Kết quả tìm kiếm cho: <?= htmlspecialchars($_GET['keyword']) ?></h2>
   <?php else: ?>
      <h2 class="title">🌏 Tour nổi bật</h2>
   <?php endif; ?>
   <div class="tour-grid">
      <?php foreach ($tours as $tour): ?>
   <?php
   $images = explode(",", $tour['AnhTour']);
   $firstImage = trim($images[0]);
   ?>
   <div class="tour-card">
      <img src="/webdulich/public/images/images/<?= $firstImage ?>" alt="<?= $tour['TenTour'] ?>">
      <div class="tour-info">
         <h3><?= htmlspecialchars($tour['TenTour']) ?></h3>
         <p>📍 <?= htmlspecialchars($tour['DiemKhoiHanh']) ?></p>
         <p>⏰ <?= htmlspecialchars($tour['TGTour']) ?></p>
         <p>🛫
            <?= !empty($tour['NgayKhoiHanh'])
               ? date('d/m/Y', strtotime($tour['NgayKhoiHanh']))
               : 'Chưa có ngày khởi hành' ?>
         </p>
         <div class="price"><?= number_format($tour['GiaTour']) ?>đ</div>
      </div>
      <a class="btn" href="/webdulich/detail?id=<?= $tour['MaTour'] ?>">Chi tiết</a>
      <a class="btn btn-book" href="/webdulich/booking/form?tour_id=<?= $tour['MaTour'] ?>">Đặt tour</a>
   </div>
<?php endforeach; ?>

   </div>


   <!-- vùng chứa thông tin -->
   <div class="footer-info">
      <div class="container info-row">
         <div class="info">
            <h3>TOUR</h3>
            <p>Miền Bắc</p>
            <p>Miền Trung</p>
            <p>Miền Nam</p>
         </div>
         <div class="info">
            <h3>Hỗ trợ</h3>
            <p>24/7</p>
            <p>Địa chỉ: LK 5A/04 Lô4, KDT Mỗ Lao, Hà Nội</p>
            <p>SĐT: (+84) 332337357</p>
         </div>
         <div class="info">
            <h3>Chính sách</h3>
            <p>Chính sách bảo mật</p>
            <p>Chính sách hoàn tiền</p>
         </div>
      </div>
   </div>
   <!--phần footer-->
   <div class="footer">

   </div>


</body>

</html>
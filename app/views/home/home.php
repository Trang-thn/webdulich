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
   <!-- phần top -->
   <div class="admin">
      <?php if (isset($_SESSION['user'])): ?>
         <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
               <i class='bx bxs-user'></i>
               <?= htmlspecialchars($_SESSION['user']['Username']) ?>
            </a>
            <ul class="dropdown-menu">
               <li><a class="dropdown-item" href="/webdulich/booking/userHistory">Lịch sử đặt tour</a></li>
               <li><a class="dropdown-item" href="/webdulich/profile">Thông tin cá nhân</a></li>
               <li><a class="dropdown-item" href="/webdulich/logout">Đăng xuất</a></li>
            </ul>
         </div>
      <?php else: ?>
         <i class='bx bxs-user' style="color:black"></i>
         <p><a href="/webdulich/user/login" style="color:black">Đăng nhập</a></p>
      <?php endif; ?>
   </div>

   <!-- logo -->
   <div class="logo">
      <h1>DU LỊCH VIỆT NAM </h1>
   </div>

   <!-- menu -->
   <div class="container">
      <div id="divLeft">
         <ul>
            <li><a href="/webdulich/">Trang chủ</a></li>
            <li><a href="/webdulich/tour">Điểm đến</a></li>
         </ul>
      </div>
      <div id="divRight">
         <form id="search-form">
            <input type="text" name="keyword" id="txtSearch" placeholder="Search">
            <button type="submit" id="btnsearch">
               <i class='bx bx-search'></i>
            </button>
         </form>


      </div>
   </div>

   <!-- banner -->
   <div class="banner" id="home">
      <img src="/webdulich/public/images/images/dalat.1.jpg" alt="sp">
   </div>

   <!-- tiêu đề và danh sách tour -->
   <h2 class="title" id="search-title">🌏 Tour nổi bật</h2>
   <div class="tour-grid" id="tour-grid"></div>


   <!-- footer info -->
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

   <!-- footer -->
   <div class="footer"></div>

   <!-- script gọi API -->
   <script>
      function renderTours(tours) {
         const grid = document.getElementById('tour-grid');
         grid.innerHTML = tours.length ?
            tours.map(t => {
               const img = t.AnhTour.split(",")[0].trim();
               return `
             <div class="tour-card">
               <img src="/webdulich/public/images/images/${img}" alt="${t.TenTour}">
               <div class="tour-info">
                 <h3>${t.TenTour}</h3>
                 <p>📍 ${t.DiemKhoiHanh}</p>
                 <p>⏰ ${t.TGTour}</p>
                 <p>🛫 ${t.NgayKhoiHanh ? new Date(t.NgayKhoiHanh).toLocaleDateString('vi-VN') : 'Chưa có ngày khởi hành'}</p>
                 <div class="price">${Number(t.GiaTour).toLocaleString()}đ</div>
               </div>
               <a class="btn" href="/webdulich/detail?id=${t.MaTour}">Chi tiết</a>
               <a class="btn btn-book" href="/webdulich/booking/form?tour_id=${t.MaTour}">Đặt tour</a>
             </div>`;
            }).join('') :
            "<p>❌ Không có tour nào</p>";
      }

      function loadTours(keyword = '') {
         const url = keyword ?
            '/webdulich/api/home/search?keyword=' + encodeURIComponent(keyword) :
            '/webdulich/api/home';

         fetch(url)
            .then(r => r.json())
            .then(d => {
               document.getElementById('search-title').textContent =
                  keyword ? "🔍 Kết quả tìm kiếm cho: " + keyword : "🌏 Tour nổi bật";
               renderTours(d.data);
            })
            .catch(err => console.error("Lỗi khi gọi API:", err));
      }

      // mặc định hiển thị 8 tour nổi bật
      loadTours();

      // xử lý form search
      document.getElementById('search-form').addEventListener('submit', e => {
         e.preventDefault();
         loadTours(document.getElementById('txtSearch').value);
      });
   </script>
</body>

</html>
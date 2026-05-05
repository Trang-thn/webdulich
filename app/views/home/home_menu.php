<!DOCTYPE html>
 <html lang="en">

 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/webdulich/public/css/home.css">
    <link rel="stylesheet" href="/webdulich/public/css/booking.css">
    <link rel="stylesheet" href="/webdulich/public/css/success.css">
    <link rel="stylesheet" href="/webdulich/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
      body{
         padding: 0 !important;

      }
      </style>
 </head>

 <body>
    <!--  phần top-->
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

    <!--phần logo-->
    <div class="logo">
       <h1>DU LỊCH VIỆT NAM </h1>

    </div>
    <!--phần menu-->

     <!--phần menu-->
    <div class="menu">
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
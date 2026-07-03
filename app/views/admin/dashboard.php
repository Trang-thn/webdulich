<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" >
  <link rel="stylesheet" href="/webdulich/public/css/dashboard.css">
</head>

<body>
  <div class="navbar d-flex justify-content-between align-items-center">
    <h1>📊 Trang Quản Trị - Admin</h1>
    <div class="logout">
      <a href="/webdulich/">🚪 Đăng xuất</a>
    </div>
  </div>

  <aside class="sidebar">
    <ul>
      <li><a href="/webdulich/home">Trang Chủ</a></li>
      <li><a href="/webdulich/tour/manage">Quản lý Tour</a></li>
      <li><a href="/webdulich/user/manage">Quản lý Thành Viên</a></li>
      <li><a href="/webdulich/booking/manage">Quản lý Đặt Tour</a></li>
      <li><a href="/webdulich/booking/history">Lịch sử</a></li>
      <li><a href="/webdulich/comment/admin">Quản lý Bình Luận</a></li>
    </ul>
  </aside>

   <main class="content">
    <?php include $viewFile; ?>
  </main>
</body>

</html>
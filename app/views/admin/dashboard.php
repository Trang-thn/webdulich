<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4;
      }

    html, body {
      top:0;
      height: 100%;
      margin: 0;
      overflow: hidden; /* chặn cuộn toàn trang */
      }

.navbar {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
  background: linear-gradient(135deg, #bfa25a, #8e6f3e);
  color: #fff;
  padding: 15px;
}

.navbar h1 {
  margin: 0;
  font-size: 22px;
  font-weight: 600;
}

.logout a {
  color: #fff;
  text-decoration: none;
  background: #dc3545;
  padding: 6px 12px;
  border-radius: 8px;
  font-weight: 600;
}

.sidebar {
  width: 240px;
  background: #343a40;
  color: #fff;
  position: fixed;
  top: 70px; /* nằm ngay dưới navbar */
  bottom: 0;
  box-shadow: 4px 0 12px rgba(0, 0, 0, 0.2);
}

.sidebar ul li {
  list-style: none;
}

.sidebar a {
  display: block;
  padding: 14px 20px;
  color: #fff;
  text-decoration: none;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar a:hover {
  background: #495057;
  transition: 0.3s;
}

.content {
  margin-left: 260px;
  margin-top: 70px;
  height: calc(100vh - 70px);
  overflow-y: auto; /* cuộn nội dung ở đây */
}


iframe {
  width: 100%;
  height: 100%;
  border: none;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}


  </style>
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
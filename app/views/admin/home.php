<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/webdulich/public/css/home_admin.css">
</head>
<body>
    <div class="manage-container">
        <div class="welcome">
            <h2>👋 Xin chào, <span><?= htmlspecialchars($_SESSION['admin']['UserAdmin']) ?></span>!</h2>
            <p>Chào mừng bạn đến với <strong>trang quản trị hệ thống du lịch</strong>.</p>
        </div>

        <h2>📊 Thống kê hệ thống</h2>
        <table class="table table-bordered stats-table">
            <thead>
                <tr>
                    <th>Loại dữ liệu</th>
                    <th>Số lượng</th>
                </tr>
            </thead>
            <tbody id="stats-body">
            </tbody>
        </table>
    </div>

    <script>
      fetch('/webdulich/api/admin/dashboard').then(res => res.json()).then(data => {
          if (data.status === 'success') {
            const s = data.data;
            document.getElementById('stats-body').innerHTML = `
              <tr><td>🗺️ Tour</td><td>${s.tour}</td></tr>
              <tr><td>👥 Thành viên</td><td>${s.user}</td></tr>
              <tr><td>📑 Đơn đặt tour</td><td>${s.booking}</td></tr>
              <tr><td>💬 Bình luận</td><td>${s.comment}</td></tr>
            `;
          } else {
            document.getElementById('stats-body').innerHTML =`<tr><td colspan="2" class="text-danger">${data.message}</td></tr>`;
          }
        })
        .catch(err => {
          document.getElementById('stats-body').innerHTML =`<tr><td colspan="2" class="text-danger">Lỗi tải dữ liệu: ${err}</td></tr>`;
        });
    </script>
</body>
</html>

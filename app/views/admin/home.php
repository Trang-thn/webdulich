<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; }
        .manage-container { max-width: 1000px; margin: 50px auto; background: #fff;
            padding: 30px; border-radius: 18px; box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        h2 { text-align: center; margin-bottom: 25px; color: #111; font-weight: bold; }
        .stats-table th { background: linear-gradient(135deg, #6c757d, #495057); color: #fff; }
        .stats-table td { font-weight: 500; color: #333; }
        .welcome { text-align: center; margin-bottom: 30px; }
        .welcome span { color: #bfa25a; font-weight: bold; }
    </style>
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
                <!-- JS sẽ render dữ liệu API vào đây -->
            </tbody>
        </table>
    </div>

    <script>
      // Gọi API để lấy số liệu thống kê
      fetch('/webdulich/api/admin/dashboard')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            const s = data.data;
            document.getElementById('stats-body').innerHTML = `
              <tr><td>🗺️ Tour</td><td>${s.tour}</td></tr>
              <tr><td>👥 Thành viên</td><td>${s.user}</td></tr>
              <tr><td>📑 Đơn đặt tour</td><td>${s.booking}</td></tr>
              <tr><td>💬 Bình luận</td><td>${s.comment}</td></tr>
            `;
          } else {
            document.getElementById('stats-body').innerHTML =
              `<tr><td colspan="2" class="text-danger">${data.message}</td></tr>`;
          }
        })
        .catch(err => {
          document.getElementById('stats-body').innerHTML =
            `<tr><td colspan="2" class="text-danger">Lỗi tải dữ liệu: ${err}</td></tr>`;
        });
    </script>
</body>
</html>

<?php include __DIR__ . "/../home/home_menu.php"; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang cá nhân</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Segoe UI', sans-serif; }
        .profile-card {
            max-width: 700px;
            margin: 60px auto;
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
        }
        .profile-card h2 {
            text-align: center;
            margin-bottom: 35px;
            letter-spacing: 1px;
            color: #1c1c1c;
        }
        .auth-background {
            min-height: 100vh;
            background: url('/webdulich/public/images/images/dalat_3.jpg') no-repeat center center;
            background-size: cover;
            padding: 0;
        }
        .profile-card h2 span { color: #bfa25a; }
        .profile-item { margin-bottom: 15px; font-size: 16px; }
        .profile-item strong { color: #333; min-width: 120px; display: inline-block; }
        .btn-primary {
            background: linear-gradient(135deg, #bfa25a, #8e6f3e);
            border: none; padding: 10px 26px; border-radius: 12px; font-weight: 600;
        }
        .btn-secondary { padding: 10px 26px; border-radius: 12px; font-weight: 600; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.2); transition: 0.3s; }
        @media (max-width: 576px) { .profile-card { padding: 25px; } }
        #profile-msg { text-align:center; margin-top:15px; font-weight:500; }
    </style>
</head>
<body class="auth-background">

    <div class="container profile-card">
        <h2>👤 Trang cá nhân <span id="profile-id"></span></h2>

        <div id="profile-view"></div>

        <div class="d-flex gap-3 mt-4">
            <button onclick="toggleEdit()" class="btn btn-primary">✏️ Chỉnh sửa</button>
            <a href="/webdulich/logout" class="btn btn-secondary">🚪 Đăng xuất</a>
        </div>

        <div id="edit-form" style="display:none; margin-top:30px;">
            <form id="profile-form">
                <input type="hidden" name="MaTVien" id="MaTVien">

                <div class="mb-3">
                    <label>Họ tên</label>
                    <input type="text" name="HoTen" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="EmailTVien" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Địa chỉ</label>
                    <input type="text" name="DiaChi" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Số CMT</label>
                    <input type="text" name="SoCMT" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Số ĐT</label>
                    <input type="text" name="SoDT" class="form-control">
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary">💾 Lưu</button>
                    <button type="button" onclick="toggleEdit()" class="btn btn-secondary">❌ Hủy</button>
                </div>
            </form>
            <div id="profile-msg"></div>
        </div>
    </div>

    <script>
        function toggleEdit() {
            const view = document.getElementById('profile-view');
            const form = document.getElementById('edit-form');
            view.style.display = (view.style.display === 'none') ? 'block' : 'none';
            form.style.display = (form.style.display === 'none') ? 'block' : 'none';
        }

        // Lấy dữ liệu profile từ API
        function loadProfile() {
            fetch('/webdulich/api/users/detail?id=' + <?= json_encode($_SESSION['user']['MaTVien'] ?? 0) ?>)
              .then(res => res.json())
              .then(data => {
                if (data.status === 'success') {
                  const u = data.data;
                  document.getElementById('profile-id').textContent = "#" + u.MaTVien;
                  document.getElementById('profile-view').innerHTML = `
                    <div class="profile-item"><strong>Họ tên:</strong> ${u.HoTen}</div>
                    <div class="profile-item"><strong>Email:</strong> ${u.EmailTVien}</div>
                    <div class="profile-item"><strong>Địa chỉ:</strong> ${u.DiaChi}</div>
                    <div class="profile-item"><strong>Số CMT:</strong> ${u.SoCMT}</div>
                    <div class="profile-item"><strong>Số điện thoại:</strong> ${u.SoDT}</div>
                  `;
                  // điền vào form edit
                  document.getElementById('MaTVien').value = u.MaTVien;
                  document.querySelector('[name="HoTen"]').value = u.HoTen;
                  document.querySelector('[name="EmailTVien"]').value = u.EmailTVien;
                  document.querySelector('[name="DiaChi"]').value = u.DiaChi;
                  document.querySelector('[name="SoCMT"]').value = u.SoCMT;
                  document.querySelector('[name="SoDT"]').value = u.SoDT;
                } else {
                  document.getElementById('profile-view').innerHTML = "<p style='color:red;'>Không tải được dữ liệu người dùng.</p>";
                }
              })
              .catch(() => {
                document.getElementById('profile-view').innerHTML = "<p style='color:red;'>Lỗi kết nối API.</p>";
              });
        }

        // Submit cập nhật profile qua API
        document.getElementById('profile-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = this.querySelector('button[type="submit"]');
            const msg = document.getElementById('profile-msg');

            btn.disabled = true;
            btn.textContent = "Đang lưu...";
            msg.textContent = "Đang xử lý...";
            msg.style.color = "blue";

            try {
                const res = await fetch('/webdulich/api/users/update', { method: 'POST', body: formData });
                if (!res.ok) throw new Error("HTTP " + res.status);
                const data = await res.json();

                if (data.status === 'success') {
                    msg.textContent = data.message || 'Cập nhật thành công!';
                    msg.style.color = 'green';
                    toggleEdit();
                    loadProfile();
                } else {
                    msg.textContent = data.message || 'Cập nhật thất bại!';
                    msg.style.color = 'red';
                }
            } catch (err) {
                msg.textContent = 'Lỗi kết nối API: ' + err.message;
                msg.style.color = 'red';
            } finally {
                btn.disabled = false;
                btn.textContent = "💾 Lưu";
            }
        });

        // Load profile khi mở trang
        loadProfile();
    </script>

</body>
</html>
<?php include __DIR__ . "/../home/home_footer.php"; ?>

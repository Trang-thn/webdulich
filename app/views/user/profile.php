<?php include __DIR__ . "/../home/home_menu.php"; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang cá nhân</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" >
    <link rel="stylesheet" href="/webdulich/public/css/profile.css">
</head>
<body class="auth-background">

<div class="container profile-card">
    <h2>👤 Trang cá nhân <span id="profile-id"></span></h2>

    <!-- View -->
    <div id="profile-view">
        <div class="profile-item"><strong>Họ tên:</strong> <span id="view-HoTen"></span></div>
        <div class="profile-item"><strong>Email:</strong> <span id="view-EmailTVien"></span></div>
        <div class="profile-item"><strong>Địa chỉ:</strong> <span id="view-DiaChi"></span></div>
        <div class="profile-item"><strong>Số CMT:</strong> <span id="view-SoCMT"></span></div>
        <div class="profile-item"><strong>Số điện thoại:</strong> <span id="view-SoDT"></span></div>

        <div class="d-flex gap-3 mt-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">✏️ Chỉnh sửa</button>
            <a href="/webdulich/logout" class="btn btn-secondary">🚪 Đăng xuất</a>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="profile-form">
        <div class="modal-header">
          <h5 class="modal-title">Chỉnh sửa thông tin</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="MaTVien" id="form-MaTVien">

            <div class="mb-3">
                <label>Họ tên</label>
                <input type="text" name="HoTen" class="form-control" id="form-HoTen" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="EmailTVien" class="form-control" id="form-EmailTVien" required>
            </div>
            <div class="mb-3">
                <label>Địa chỉ</label>
                <input type="text" name="DiaChi" class="form-control" id="form-DiaChi">
            </div>
            <div class="mb-3">
                <label>Số CMT</label>
                <input type="number" name="SoCMT" class="form-control" id="form-SoCMT">
            </div>
            <div class="mb-3">
                <label>Số ĐT</label>
                <input type="number" name="SoDT" class="form-control" id="form-SoDT">
            </div>
            <div id="profile-msg"></div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">💾 Lưu</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Hủy</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    async function loadProfile() {
        try {
            const res = await fetch('/webdulich/api/users/detail?id=<?= json_encode($_SESSION['user']['MaTVien'] ?? 0) ?>');
            const data = await res.json();
            if (data.status === 'success') {
                const u = data.data;
                document.getElementById('profile-id').textContent = "#" + u.MaTVien;
                document.getElementById('view-HoTen').textContent = u.HoTen;
                document.getElementById('view-EmailTVien').textContent = u.EmailTVien;
                document.getElementById('view-DiaChi').textContent = u.DiaChi;
                document.getElementById('view-SoCMT').textContent = u.SoCMT;
                document.getElementById('view-SoDT').textContent = u.SoDT;

                document.getElementById('form-MaTVien').value = u.MaTVien;
                document.getElementById('form-HoTen').value = u.HoTen;
                document.getElementById('form-EmailTVien').value = u.EmailTVien;
                document.getElementById('form-DiaChi').value = u.DiaChi;
                document.getElementById('form-SoCMT').value = u.SoCMT;
                document.getElementById('form-SoDT').value = u.SoDT;
            }
        } catch (err) {
            console.error("Lỗi load profile:", err);
        }
    }

    document.getElementById('profile-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const msg = document.getElementById('profile-msg');
        msg.textContent = "Đang xử lý...";
        msg.style.color = "blue";

        try {
            const res = await fetch('/webdulich/api/users/updateProfile', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.status === 'success') {
                msg.textContent = data.message || 'Cập nhật thành công!';
                msg.style.color = 'green';
                loadProfile();
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                    modal.hide();
                    msg.textContent = "";
                }, 1000);
            } else {
                msg.textContent = data.message || 'Cập nhật thất bại!';
                msg.style.color = 'red';
            }
        } catch (err) {
            msg.textContent = 'Lỗi kết nối API: ' + err.message;
            msg.style.color = 'red';
        }
    });
    loadProfile();
</script>

</body>
</html>

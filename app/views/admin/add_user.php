<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm thành viên</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Segoe UI', sans-serif; }
        .add-user {
            max-width: 700px;
            margin: 60px auto;
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
        }
        .add-user h2 {
            text-align: center;
            margin-bottom: 35px;
            letter-spacing: 1px;
            color: #1c1c1c;
        }
        .add-user h2 span { color: #bfa25a; }
        .form-label { font-weight: 600; color: #333; }
        .form-control {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #ddd;
            background: #fafafa;
            transition: 0.3s;
        }
        .form-control:focus {
            border-color: #bfa25a;
            box-shadow: 0 0 8px rgba(191, 162, 90, 0.4);
            background: #fff;
        }
        .btn-primary {
            background: linear-gradient(135deg, #bfa25a, #8e6f3e);
            border: none;
            padding: 10px 26px;
            border-radius: 12px;
            font-weight: 600;
        }
        .btn-secondary {
            padding: 10px 26px;
            border-radius: 12px;
            font-weight: 600;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        }
        @media (max-width: 576px) {
            .add-user { padding: 25px; }
        }
        #msg { text-align:center; margin-top:15px; font-weight:600; }
    </style>
</head>
<body>

<div class="container add-user">
    <h2>➕ Thêm thành viên mới</h2>

    <form id="add-user-form">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="Username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="HoTen" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="EmailTVien" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="DiaChi" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Số CMT</label>
            <input type="text" name="SoCMT" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Số ĐT</label>
            <input type="text" name="SoDT" class="form-control">
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary">💾 Lưu thành viên</button>
            <a href="/webdulich/user/manage" class="btn btn-secondary">⬅️ Quay lại</a>
        </div>
    </form>

    <div id="msg"></div>
</div>

<script>
document.getElementById('add-user-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('/webdulich/api/users/add', { method: 'POST', body: formData })
      .then(res => res.json())
      .then(data => {
        const msg = document.getElementById('msg');
        if (data.status === 'success') {
          msg.textContent = data.message;
          msg.style.color = 'green';
          setTimeout(() => window.location.href = '/webdulich/user/manage', 1500);
        } else {
          msg.textContent = data.message;
          msg.style.color = 'red';
        }
      })
      .catch(err => {
        const msg = document.getElementById('msg');
        msg.textContent = 'Lỗi kết nối API: ' + err;
        msg.style.color = 'red';
      });
});
</script>

</body>
</html>

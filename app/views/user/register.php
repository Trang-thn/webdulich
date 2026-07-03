<?php include __DIR__ . "/../home/home_menu.php"; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký</title>
  <link rel="stylesheet" href="/webdulich/public/css/login.css">
  <link rel="stylesheet" href="/webdulich/public/css/register.css">

</head>
<body class="auth-background">
  <div class="form-container">
    <h2>Đăng Ký</h2>

    <form id="register-form">
      <label>Tài khoản</label>
      <input type="text" id="username" name="username" required>
      <span id="username-msg" style="color:red;"></span>

      <label>Mật khẩu</label>
      <input type="password" name="password" required>

      <label>Nhập lại mật khẩu</label>
      <input type="password" name="confirm_password" required>

      <label>Họ tên</label>
      <input type="text" name="hoten">

      <label>Email</label>
      <input type="email" name="email">

      <label>Địa chỉ</label>
      <input type="text" name="diachi">

      <label>Số CMT</label>
      <input type="number" name="socmt">

      <label>Số ĐT</label>
      <input type="number" name="sodt">

      <button type="submit">Đăng ký</button>
    </form>

    <div id="register-msg"></div>

    <p style="margin-top:10px;">Bạn đã có tài khoản? <a href="/webdulich/user/login">Đăng nhập</a></p>
  </div>

  <script>
    document.getElementById('register-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    const pw = formData.get('password');
    const confirm = formData.get('confirm_password');
    if (pw !== confirm) {
      document.getElementById('register-msg').textContent = "Mật khẩu nhập lại không khớp!";
      document.getElementById('register-msg').style.color = 'red';
      return;
    }

    const msg = document.getElementById('register-msg');
    const btn = this.querySelector('button');
    btn.disabled = true;
    btn.textContent = "Đang đăng ký...";
    msg.textContent = "Đang xử lý...";
    msg.style.color = "blue";

    try {
      const res = await fetch('/webdulich/api/admin/register', { method: 'POST', body: formData });
      if (!res.ok) throw new Error("HTTP " + res.status);

      const data = await res.json();

      if (data.status === 'success') {
        msg.textContent = data.message || "Đăng ký thành công!";
        msg.style.color = 'green';
        setTimeout(() => window.location.href = '/webdulich/user/login', 1500);
      } else {
        msg.textContent = data.message || "Đăng ký thất bại!";
        msg.style.color = 'red';
      }
    } catch (err) {
      msg.textContent = 'Lỗi kết nối API: ' + err.message;
      msg.style.color = 'red';
    } finally {
      btn.disabled = false;
      btn.textContent = "Đăng ký";
    }
  });
  </script>
</body>
</html>

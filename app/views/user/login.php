<?php include __DIR__ . "/../home/home_menu.php"; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập</title>
  <link rel="stylesheet" href="/webdulich/public/css/login.css">
</head>
<body class="auth-background">
  <div class="form-container">
    <h2>Đăng Nhập</h2>

    <form id="login-form">
      <label>Tài khoản</label>
      <input type="text" name="username" required autocomplete="off">
      <label>Mật khẩu</label>
      <input type="password" name="password" required autocomplete="off">
      <button type="submit">Đăng nhập</button>
    </form>

    <p style="margin-top:10px;">Bạn chưa có tài khoản? <a href="/webdulich/user/register">Đăng kí ngay</a></p>
    <div id="login-msg" style="text-align:center; margin-top:10px;"></div>

    <script>
      document.getElementById('login-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const msg = document.getElementById('login-msg');
        const btn = this.querySelector('button');

        btn.disabled = true;
        btn.textContent = "Đang đăng nhập...";
        msg.textContent = "Đang xử lý...";
        msg.style.color = "blue";

        try {
          const res = await fetch('/webdulich/api/admin/login', {method: 'POST',body: formData});
          const data = await res.json();

          if (!res.ok) {
            msg.textContent = data.message || "Sai tài khoản hoặc mật khẩu!";
            msg.style.color = "red";
            return;
          }

          if (data.status === 'success') {
            msg.textContent = data.message || "Đăng nhập thành công!";
            msg.style.color = "green";

            if (data.redirect) {
              window.location.href = data.redirect;
            } else if (data.role === 'admin') {
              window.location.href = '/webdulich/dashboard';
            } else {
              window.location.href = '/webdulich/';
            }
          } else {
            msg.textContent = data.message || "Đăng nhập thất bại!";
            msg.style.color = "red";
          }
        } catch (err) {
          msg.textContent = "Lỗi kết nối API: " + err.message;
          msg.style.color = "red";
        } finally {
          btn.disabled = false;
          btn.textContent = "Đăng nhập";
        }
      });
    </script>
  </div>
</body>
</html>

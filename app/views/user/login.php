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

    <?php if (!empty($error)): ?>
      <div style="text-align:center; color:red;"><?= htmlspecialchars($error) ?></div>
    <?php elseif (!empty($message)): ?>
      <div style="text-align:center; color:green;"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- <form method="POST" action="/webdulich/login">
      <label>Tài khoản</label>
      <input type="text" name="username" required>
      <label>Mật khẩu</label>
      <input type="password" name="password" required>
      <button type="submit">Đăng nhập</button>
    </form> -->

    <form id="login-form">
  <label>Tài khoản</label>
  <input type="text" name="username" required>
  <label>Mật khẩu</label>
  <input type="password" name="password" required>
  <button type="submit">Đăng nhập</button>
</form>
<p style="margin-top:10px;">
      Bạn chưa có tài khoản? <a href="/webdulich/user/register">Đăng kí ngay</a>
    </p>
<div id="login-msg" style="text-align:center; margin-top:10px;"></div>

<script>
document.getElementById('login-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  fetch('/webdulich/api/admin/login', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        if (data.role === 'admin') {
          window.location.href = '/webdulich/dashboard';
        } else {
          window.location.href = '/webdulich/';
        }
      } else {
        document.getElementById('login-msg').textContent = data.message;
        document.getElementById('login-msg').style.color = 'red';
      }
    })
    .catch(err => {
      document.getElementById('login-msg').textContent = 'Lỗi kết nối API: ' + err;
      document.getElementById('login-msg').style.color = 'red';
    });
});
</script>

  </div>
</body>
</html>

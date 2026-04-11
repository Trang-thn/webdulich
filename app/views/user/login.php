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

    <form method="POST" action="/webdulich/login">
      <label>Tài khoản</label>
      <input type="text" name="username" required>
      <label>Mật khẩu</label>
      <input type="password" name="password" required>
      <button type="submit">Đăng nhập</button>
    </form>

    <p style="margin-top:10px;">
      Bạn chưa có tài khoản? <a href="/webdulich/user/register">Đăng kí ngay</a>
    </p>
  </div>
</body>
</html>

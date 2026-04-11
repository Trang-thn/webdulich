<?php include __DIR__ . "/../home/home_menu.php"; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Đăng ký</title>
  <link rel="stylesheet" href="#">
<style>
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow-y: auto;
  }

  .auth-background {
    min-height: 100vh;
    background: url('/webdulich/public/images/phong_canh.jpg') no-repeat center center;
    background-size: cover;
    padding: 40px 20px; 
  }

  .form-container {
    max-width: 500px;
    margin-top: 30px !important;
    margin: auto;
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  }


.form-container h2 {
    text-align: center;
    
    margin-bottom: 20px;
    color: #222;
}

.form-container input {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    color: #333;
}

.form-container button {
    width: 100%;
    padding: 10px;
    background-color: #ffcc00;
    border: none;
    color: black;
    font-weight: bold;
    margin-top: 10px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.form-container button:hover {
    background-color: #e6b800;
}
</style>

</head>

<body class="auth-background">
  <div class="form-container">
    <h2>Đăng Ký</h2>

    <?php if (!empty($error)): ?>
      <div style="text-align:center; color:red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/webdulich/user/register">
      <label>Tài khoản</label>
      <input type="text" value="<?= htmlspecialchars($username ?? '') ?>" name="username" required>

      <label>Mật khẩu</label>
      <input type="password" value="<?= htmlspecialchars($password?? '') ?>" name="password" required>

      <label>Nhập lại mật khẩu</label>
      <input type="password" value="<?= htmlspecialchars($confirm?? '') ?>" name="confirm_password" required>

      <label>Họ tên</label>
      <input type="text" value="<?= htmlspecialchars($hoten?? '') ?>" name="hoten">

      <label>Email</label>
      <input type="email" value="<?= htmlspecialchars($email ?? '') ?>" name="email">

      <label>Địa chỉ</label>
      <input type="text" value="<?= htmlspecialchars($diachi ?? '') ?>" name="diachi">

      <label>Số CMT</label>
      <input type="text" value="<?= htmlspecialchars($socmt?? '') ?>" name="socmt">

      <label>Số ĐT</label>
      <input type="text" value="<?= htmlspecialchars($sodt ?? '') ?>" name="sodt">

      <button type="submit">Đăng ký</button>
    </form>

    <p style="margin-top:10px;">
      Bạn đã có tài khoản? <a href="/webdulich/user/login">Đăng nhập</a>
    </p>
  </div>
<script>
document.getElementById('username').addEventListener('blur', function() {
    const val = this.value;
    fetch('/webdulich/user/check-username?username=' + encodeURIComponent(val))
      .then(res => res.json())
      .then(data => {
        if (data.exists) {
          document.getElementById('username-msg').textContent = "Tên đăng nhập đã tồn tại!";
        } else {
          document.getElementById('username-msg').textContent = "";
        }
      });
});
</script>

</body>

</html>
<?php include __DIR__ . "/../home/home_menu.php"; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký</title>
  <link rel="stylesheet" href="/webdulich/public/css/login.css">
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
    #username-msg, #register-msg {
      text-align:center;
      font-size:14px;
      margin-top:5px;
    }
  </style>
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

    <p style="margin-top:10px;">
      Bạn đã có tài khoản? <a href="/webdulich/user/login">Đăng nhập</a>
    </p>
  </div>

  <script>
    // Kiểm tra username động
    document.getElementById('username').addEventListener('blur', function() {
      const val = this.value.trim();
      if (!val) return;
      fetch('/webdulich/api/users/check?username=' + encodeURIComponent(val))
        .then(res => res.json())
        .then(data => {
          if (data.exists) {
            document.getElementById('username-msg').textContent = "Tên đăng nhập đã tồn tại!";
          } else {
            document.getElementById('username-msg').textContent = "";
          }
        })
        .catch(() => {
          document.getElementById('username-msg').textContent = "Không kiểm tra được tên đăng nhập.";
        });
    });

    // Submit form qua API
    document.getElementById('register-form').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);

      fetch('/webdulich/api/users/register', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            document.getElementById('register-msg').textContent = "Đăng ký thành công!";
            document.getElementById('register-msg').style.color = 'green';
            setTimeout(() => window.location.href = '/webdulich/user/login', 1500);
          } else {
            document.getElementById('register-msg').textContent = data.message;
            document.getElementById('register-msg').style.color = 'red';
          }
        })
        .catch(err => {
          document.getElementById('register-msg').textContent = 'Lỗi kết nối API: ' + err;
          document.getElementById('register-msg').style.color = 'red';
        });
    });
  </script>
</body>
</html>

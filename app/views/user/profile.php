<?php include __DIR__ . "/../home/home_menu.php"; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang cá nhân</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }

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

        .profile-card h2 span {
            color: #bfa25a;
        }

        .profile-item {
            margin-bottom: 15px;
            font-size: 16px;
        }

        .profile-item strong {
            color: #333;
            min-width: 120px;
            display: inline-block;
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
            .profile-card {
                padding: 25px;
            }
        }
    </style>
</head>

<body class=auth-background>

    <div class="container profile-card">
        <h2>👤 Trang cá nhân <span>#<?= $user['MaTVien'] ?></span></h2>

        <div id="profile-view">
            <div class="profile-item"><strong>Họ tên:</strong> <?= htmlspecialchars($user['HoTen']) ?></div>
            <div class="profile-item"><strong>Email:</strong> <?= htmlspecialchars($user['EmailTVien']) ?></div>
            <div class="profile-item"><strong>Địa chỉ:</strong> <?= htmlspecialchars($user['DiaChi']) ?></div>
            <div class="profile-item"><strong>Số CMT:</strong> <?= htmlspecialchars($user['SoCMT']) ?></div>
            <div class="profile-item"><strong>Số điện thoại:</strong> <?= htmlspecialchars($user['SoDT']) ?></div>

            <div class="d-flex gap-3 mt-4">
                <button onclick="toggleEdit()" class="btn btn-primary">✏️ Chỉnh sửa</button>
                <a href="/webdulich/logout" class="btn btn-secondary">🚪 Đăng xuất</a>
            </div>
        </div>

        <div id="edit-form" style="display:none; margin-top:30px;">
            <form method="POST" action="/webdulich/user/update-profile">
                <input type="hidden" name="MaTVien" value="<?= $user['MaTVien'] ?>">

                <div class="mb-3">
                    <label>Họ tên</label>
                    <input type="text" name="HoTen" class="form-control" value="<?= htmlspecialchars($user['HoTen']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="EmailTVien" class="form-control" value="<?= htmlspecialchars($user['EmailTVien']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Địa chỉ</label>
                    <input type="text" name="DiaChi" class="form-control" value="<?= htmlspecialchars($user['DiaChi']) ?>">
                </div>
                <div class="mb-3">
                    <label>Số CMT</label>
                    <input type="text" name="SoCMT" class="form-control" value="<?= htmlspecialchars($user['SoCMT']) ?>">
                </div>
                <div class="mb-3">
                    <label>Số ĐT</label>
                    <input type="text" name="SoDT" class="form-control" value="<?= htmlspecialchars($user['SoDT']) ?>">
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary">💾 Lưu</button>
                    <button type="button" onclick="toggleEdit()" class="btn btn-secondary">❌ Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleEdit() {
            const view = document.getElementById('profile-view');
            const form = document.getElementById('edit-form');
            view.style.display = (view.style.display === 'none') ? 'block' : 'none';
            form.style.display = (form.style.display === 'none') ? 'block' : 'none';
        }
    </script>


</body>

</html>
<?php include __DIR__ . "/../home/home_footer.php"; ?>
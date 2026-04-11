<?php
require_once __DIR__ . "/../../../config/database.php";
require_once __DIR__ . "/../../../app/models/Admin.php";

$admin = new Admin(new Database());
$stats = [
    'tour'    => $admin->countTable('TOUR'),
    'user'    => $admin->countTable('THANHVIEN'),
    'booking' => $admin->countTable('DATTOUR'),
    'comment' => $admin->countTable('COMMENT')
];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
        }

        .manage-container {
            max-width: 1000px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #111;
            font-weight: bold;
        }

        .stats-table th {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: #fff;
        }

        .stats-table td {
            font-weight: 500;
            color: #333;
        }

        .welcome {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome span {
            color: #bfa25a;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="manage-container">
        <div class="welcome">
            <h2>👋 Xin chào, <span><?= htmlspecialchars($_SESSION['admin']['UserAdmin']) ?></span>!</h2>
            <p>Chào mừng bạn đến với <strong>trang quản trị hệ thống du lịch</strong>.</p>
        </div>

        <h2>📊 Thống kê hệ thống</h2>
        <table class="table table-bordered stats-table">
            <thead>
                <tr>
                    <th>Loại dữ liệu</th>
                    <th>Số lượng</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>🗺️ Tour</td>
                    <td><?= $stats['tour'] ?></td>
                </tr>
                <tr>
                    <td>👥 Thành viên</td>
                    <td><?= $stats['user'] ?></td>
                </tr>
                <tr>
                    <td>📑 Đơn đặt tour</td>
                    <td><?= $stats['booking'] ?></td>
                </tr>
                <tr>
                    <td>💬 Bình luận</td>
                    <td><?= $stats['comment'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php include __DIR__ . "/../home/home_banner.php"; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lịch sử đặt tour</title>
    <style>
        .history-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background: #fdfdfd;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .history-container h2 {
            text-align: center;
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        .history-table thead {
            background: #3498db;
            color: #fff;
        }

        .history-table th,
        .history-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .history-table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .history-table tbody tr:hover {
            background: #ecf0f1;
            cursor: pointer;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            margin: 0 3px;
            background: #e74c3c;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="history-container">
        <h2>Lịch sử đặt tour của bạn</h2>

        <?php if (!empty($bookings)): ?>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Tên tour</th>
                        <th>Ngày đặt</th>
                        <th>Ngày đi</th>
                        <th>Số lượng khách</th>
                        <th>Giá tour</th>
                        <th>Khách sạn</th>
                        <th>Yêu cầu khác</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td><?= htmlspecialchars($b['MaDat']) ?></td>
                            <td><?= htmlspecialchars($b['TenTour']) ?></td>
                            <td><?= htmlspecialchars($b['NgayDat']) ?></td>
                            <td><?= htmlspecialchars($b['NgayDi']) ?></td>
                            <td><?= htmlspecialchars($b['SoLuongKhach']) ?></td>
                            <td><?= number_format($b['GiaTour']) ?> VND</td>
                            <td><?= htmlspecialchars($b['CapKS']) ?></td>
                            <td><?= htmlspecialchars($b['Khac']) ?></td>
                            <td>
                                <a href="/webdulich/booking/successEdit?maDat=<?= $b['MaDat'] ?>&tour_id=<?= $b['MaTour'] ?>"
                                    class="btn" style="background:#f39c12;">✏️ Sửa</a>
                                <form action="/webdulich/booking/cancelhome" method="POST" style="display:inline;">
                                    <input type="hidden" name="maDat" value="<?= $b['MaDat'] ?>">
                                    <button type="submit" class="btn" onclick="return confirm('Bạn chắc chắn muốn hủy đơn này?')">❌ Hủy</button>
                                </form>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center; color:#7f8c8d;">Bạn chưa có đơn đặt tour nào.</p>
        <?php endif; ?>
    </div>
</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <?php if (!empty($_SESSION['toastr'])): ?>
        <script>
            $(document).ready(function() {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": "3000"
                };
                toastr["<?= $_SESSION['toastr']['type'] ?>"]("<?= $_SESSION['toastr']['msg'] ?>");
            });
        </script>
        <?php unset($_SESSION['toastr']); ?>
    <?php endif; ?>

<?php include __DIR__ . "/../home/home_footer.php"; ?>
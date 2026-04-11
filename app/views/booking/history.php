<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lịch sử đặt tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
         
            font-family: 'Segoe UI', sans-serif;
        }

        .history-container {
            max-width: 1200px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
            font-weight: bold;
        }

        table {
            border-radius: 12px;
            overflow: hidden;
        }

        thead {
            background: linear-gradient(135deg, #bfa25a, #8e6f3e);
            color: #fff;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        .badge {
            font-size: 0.9rem;
            padding: 6px 12px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="history-container">
        <h2> Lịch sử đặt tour</h2>
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Mã đặt</th>
                    <th>Tên khách</th>
                    <th>Email</th>
                    <th>Tên tour</th>
                    <th>Ngày đặt</th>
                    <th>Ngày đi</th>
                    <th>Số lượng khách</th>
                    <th>Cấp KS</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= $b['MaDat'] ?></td>
                        <td><?= htmlspecialchars($b['HoTen']) ?></td>
                        <td><?= htmlspecialchars($b['EmailTVien']) ?></td>
                        <td><?= htmlspecialchars($b['TenTour']) ?></td>
                        <td><?= !empty($b['NgayDat']) ? date('d/m/Y', strtotime($b['NgayDat'])) : '-' ?></td>
                        <td><?= !empty($b['NgayDi']) ? date('d/m/Y', strtotime($b['NgayDi'])) : '-' ?></td>
                        <td><?= !empty($b['SoLuongKhach']) ? $b['SoLuongKhach'] : '-' ?></td>
                        <td><?= !empty($b['CapKS']) ? $b['CapKS'] : '-' ?></td>
                        <td>
                            <?php if (!empty($b['NgayDi'])): ?>
                                <span class="badge bg-success">Đã đặt</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Đã hủy</span>
                                <form action="/webdulich/booking/delete" method="POST" style="display:inline;">
                                    <input type="hidden" name="maDat" value="<?= $b['MaDat'] ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Xóa</button>
                                </form>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>
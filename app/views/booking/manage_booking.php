<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn đặt tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {

            font-family: 'Segoe UI', sans-serif;
        }

        .manage-container {
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
            color: #111;
            font-weight: bold;
        }

        thead {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: #fff;
        }

        tbody tr:hover {
            background-color: #e08a8a;
        }

        .action-btn {
            margin-right: 5px;
        }

        .search-bar {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="manage-container">
        <h2> Quản lý đơn đặt tour</h2>
        <form method="GET" action="/webdulich/booking/manage" class="search-bar">
            <input type="text" name="keyword" class="form-control"
                placeholder="Tìm theo Mã đặt, Tên khách, Tên tour"
                value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
            <button type="submit" class="btn btn-primary">🔍 Tìm kiếm</button>
            <a href="/webdulich/booking/export?keyword=<?= urlencode($_GET['keyword'] ?? '') ?>"
                class="btn btn-success">📊 Xuất Excel</a>
            <a href="/webdulich/booking/manage" class="btn btn-secondary">🔄 Làm mới</a>
        </form>
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Mã đặt</th>
                    <th>Tên khách</th>
                    <th>Tour</th>
                    <th>Giá tour</th>
                    <th>Ngày đi</th>
                    <th>Số lượng khách</th>
                    <th>Yêu cầu khác</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bookings)): ?>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= $b['MaDat'] ?></td>
                        <td><?= htmlspecialchars($b['HoTen']) ?></td>
                        <td><?= htmlspecialchars($b['TenTour']) ?></td>
                        <td><?= number_format($b['GiaTour'], 0, ',', '.') ?> đ</td>
                        <td><?= date('d/m/Y', strtotime($b['NgayDi'])) ?></td>
                        <td><?= $b['SoLuongKhach'] ?></td>
                        <td><?= htmlspecialchars($b['Khac']) ?></td>
                        <td>
                            <a href="/webdulich/booking/edit?maDat=<?= $b['MaDat'] ?>"
                                class="btn btn-warning btn-sm action-btn">Sửa</a>
                            <form action="/webdulich/booking/cancel" method="POST" style="display:inline;" onsubmit="return confirmCancel();">
                                <input type="hidden" name="maDat" value="<?= $b['MaDat'] ?>">
                                <input type="hidden" name="ngayDi" value="<?= $b['NgayDi'] ?>">
                                <input type="hidden" name="source" value="manage">
                                <button type="submit" class="btn btn-danger btn-sm action-btn">Hủy</button>
                            </form>
                            <a href="/webdulich/booking/detail?maDat=<?= $b['MaDat'] ?>"
                                class="btn btn-info btn-sm action-btn">Chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr><td colspan="9" class="text-center">Không có dữ liệu</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        function confirmCancel() {
            return confirm("Bạn có chắc chắn muốn hủy tour này không?");
        }
    </script>
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

</body>

</html>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn đặt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
           
            font-family: 'Segoe UI', sans-serif;
        }

        .booking-detail {
            max-width: 850px;
            margin: 60px auto;
            background: #ffffff;
            border-radius: 18px;
            padding: 35px 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
        }

        .booking-detail h3 {
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: 1px;
            color: #1c1c1c;
        }

        .booking-detail h3 span {
            color: #bfa25a;
        }

        .list-group-item {
            border: none;
            padding: 14px 0;
            font-size: 15px;
            border-bottom: 1px dashed #ddd;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .list-group-item strong {
            color: #333;
            width: 180px;
            display: inline-block;
        }

        .price {
            color: #bfa25a;
            font-weight: 700;
            font-size: 16px;
        }

        .list-group-item:hover {
            background: #faf8f2;
            transition: 0.3s;
        }

        @media (max-width: 576px) {
            .booking-detail {
                padding: 25px;
            }

            .list-group-item strong {
                width: auto;
                display: block;
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>

    <div class="container booking-detail">
        <h3>Chi tiết đơn đặt <span>#<?= $booking['MaDat'] ?></span></h3>

        <ul class="list-group">
            <li class="list-group-item">
                <strong>Khách hàng:</strong>
                <?= htmlspecialchars($booking['HoTen']) ?>
            </li>

            <li class="list-group-item">
                <strong>Email:</strong>
                <?= htmlspecialchars($booking['EmailTVien']) ?>
            </li>

            <li class="list-group-item">
                <strong>Tour:</strong>
                <?= htmlspecialchars($booking['TenTour']) ?>
            </li>

            <li class="list-group-item">
                <strong>Giá tour:</strong>
                <span class="price"><?= number_format($booking['GiaTour'], 0, ',', '.') ?> đ</span>
            </li>

            <li class="list-group-item">
                <strong>Ngày đặt:</strong>
                <?= $booking['NgayDat'] ?>
            </li>

            <li class="list-group-item">
                <strong>Ngày đi:</strong>
                <?= $booking['NgayDi'] ?>
            </li>

            <li class="list-group-item">
                <strong>Số lượng khách:</strong>
                <?= $booking['SoLuongKhach'] ?>
            </li>

            <li class="list-group-item">
                <strong>Cấp khách sạn:</strong>
                <?= htmlspecialchars($booking['CapKS']) ?>
            </li>

            <li class="list-group-item">
                <strong>Yêu cầu khác:</strong>
                <?= htmlspecialchars($booking['Khac']) ?>
            </li>
        </ul>
        <div class="mt-4 text-center">
            <a href="/webdulich/booking/manage" class="btn btn-secondary">
                ⬅️ Quay lại quản lý đơn đặt
            </a>
        </div>
    </div>



</body>

</html>
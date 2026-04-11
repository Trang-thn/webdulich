<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa đơn đặt tour</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {

            font-family: 'Segoe UI', sans-serif;
        }

        .edit-booking {
            max-width: 700px;
            margin: 60px auto;
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
        }

        .edit-booking h2 {
            text-align: center;
            margin-bottom: 35px;
            letter-spacing: 1px;
            color: #1c1c1c;
        }

        .edit-booking h2 span {
            color: #bfa25a;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }
        .form-control,
        .form-select,
        textarea {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #ddd;
            background: #fafafa;
            transition: 0.3s;
        }

        .form-control:focus,
        .form-select:focus,
        textarea:focus {
            border-color: #bfa25a;
            box-shadow: 0 0 8px rgba(191, 162, 90, 0.4);
            background: #fff;
        }
        .btn-primary {
            background: #bfa25a;
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
    </style>
</head>

<body>

    <div class="container edit-booking">
        <h2> Sửa đơn đặt tour <span>#<?= $booking['MaDat'] ?></span></h2>

        <form method="POST" action="/webdulich/booking/update">
            <input type="hidden" name="maDat" value="<?= $booking['MaDat'] ?>">
            <input type="hidden" name="tour_id" value="<?= $booking['MaTour'] ?>">

            <div class="mb-3">
                <label class="form-label">Số lượng khách</label>
                <input type="number" name="soLuongKhach" class="form-control"
                    value="<?= $booking['SoLuongKhach'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày đi</label>
                <input type="date" name="ngayDi" min="<?= date('Y-m-d') ?>" class="form-control"
                    value="<?= $booking['NgayDi'] ? date('Y-m-d', strtotime($booking['NgayDi'])) : '' ?>" required>

            </div>

            <div class="mb-3">
                <label class="form-label">Cấp khách sạn</label>
                <select name="capKS" class="form-select">
                    <option <?= $booking['CapKS'] == '3 sao' ? 'selected' : '' ?>>3 sao</option>
                    <option <?= $booking['CapKS'] == '4 sao' ? 'selected' : '' ?>>4 sao</option>
                    <option <?= $booking['CapKS'] == '5 sao' ? 'selected' : '' ?>>5 sao</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Yêu cầu khác</label>
                <textarea name="khac" class="form-control" rows="3"><?= htmlspecialchars($booking['Khac']) ?></textarea>
            </div>

            <div class="d-flex gap-3">
                <input type="hidden" name="source" value="edit">
                <button type="submit" class="btn btn-primary"> Lưu thay đổi</button>
                <a href="/webdulich/booking/manage" class="btn btn-secondary">⬅ Quay lại</a>
            </div>
        </form>
    </div>

</body>

</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nhập Excel - Thành viên</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="" rel="stylesheet">

    <style>
        body { font-family: 'Segoe UI', sans-serif; }
        .import-user {
            max-width: 700px;
            margin: 60px auto;
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
        }
        .import-user h2 {
            text-align: center;
            margin-bottom: 35px;
            letter-spacing: 1px;
            color: #1c1c1c;
        }
        .import-user h2 span { color: #bfa25a; }
        .form-label { font-weight: 600; color: #333; }
        .form-control {
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #ddd;
            background: #fafafa;
            transition: 0.3s;
        }
        .form-control:focus {
            border-color: #bfa25a;
            box-shadow: 0 0 8px rgba(191, 162, 90, 0.4);
            background: #fff;
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
            .import-user { padding: 25px; }
        }
    </style>
</head>
<body>

<div class="container add-user">
    <h2>📥 Nhập dữ liệu thành viên từ <span>Excel</span></h2>

    <form method="POST" enctype="multipart/form-data" action="/webdulich/user/import">
        <div class="mb-3">
            <label class="form-label">Chọn file Excel (.xlsx, .xls, .csv)</label>
            <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls,.csv" required>
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary">💾 Nhập dữ liệu</button>
            <a href="/webdulich/user/manage" class="btn btn-secondary">⬅️ Quay lại</a>
        </div>
    </form>

    <?php if (isset($_SESSION['import_errors'])): ?>
        <div class="mt-4">
            <h5 class="text-danger">⚠️ Các lỗi khi nhập dữ liệu:</h5>
            <ul class="text-danger">
                <?php foreach ($_SESSION['import_errors'] as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; unset($_SESSION['import_errors']); ?>
            </ul>
        </div>
    <?php endif; ?>
</div>


</body>
</html>

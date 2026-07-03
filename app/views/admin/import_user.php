<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nhập Excel - Thành viên</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/webdulich/public/css/import_user.css">
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

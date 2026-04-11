<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý thành viên</title>
    <link href="" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; }
        .manage-container {
            max-width: 1200px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        h2 { text-align:center; margin-bottom:25px; color:#111; font-weight:bold; }
        thead { background: linear-gradient(135deg, #6c757d, #495057); color:#fff; }
        tbody tr:hover { background-color:#f9f9f9; }
        .action-btn { margin-right:5px; }
        .search-bar { margin-bottom:20px; display:flex; gap:10px; }
    </style>
</head>
<body>
<div class="manage-container">
    <h2>👥 Quản lý thành viên</h2>
    <form method="GET" action="/webdulich/user/manage" class="search-bar">
        <input type="text" name="keyword" class="form-control"
               placeholder="Tìm theo ID, Username, Họ tên, Email"
               value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
        <button type="submit" class="btn btn-primary">🔍 Tìm kiếm</button>
        <a href="/webdulich/user/export?keyword=<?= urlencode($_GET['keyword'] ?? '') ?>" class="btn btn-success">📊 Xuất Excel</a>
        <a href="/webdulich/user/import" class="btn btn-success">📥 Nhập Excel</a>
        <a href="/webdulich/user/add" class="btn btn-success">➕ Thêm mới</a>
        <a href="/webdulich/user/manage" class="btn btn-secondary">🔄 Làm mới</a>
    </form>

    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>STT</th>
                <th>ID</th>
                <th>Username</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Địa chỉ</th>
                <th>Số CMT</th>
                <th>Số ĐT</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): $i=0; ?>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= ++$i ?></td>
                        <td><?= $u['MaTVien'] ?></td>
                        <td><?= htmlspecialchars($u['Username']) ?></td>
                        <td><?= htmlspecialchars($u['HoTen']) ?></td>
                        <td><?= htmlspecialchars($u['EmailTVien']) ?></td>
                        <td><?= htmlspecialchars($u['DiaChi']) ?></td>
                        <td><?= htmlspecialchars($u['SoCMT']) ?></td>
                        <td><?= htmlspecialchars($u['SoDT']) ?></td>
                        <td>
                            <a href="/webdulich/user/edit?id=<?= $u['MaTVien'] ?>" class="btn btn-warning btn-sm action-btn">Sửa</a>
                            <form action="/webdulich/user/delete" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $u['MaTVien'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm action-btn">Xóa</button>
                            </form>
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

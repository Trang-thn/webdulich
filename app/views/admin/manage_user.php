<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý thành viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <form id="search-form" class="search-bar">
        <input type="text" name="keyword" class="form-control"
               placeholder="Tìm theo ID, Username, Họ tên, Email">
        <button type="submit" class="btn btn-primary">🔍 Tìm kiếm</button>
        <a href="/webdulich/user/export" class="btn btn-success">📊 Xuất Excel</a>
        <a href="/webdulich/user/import" class="btn btn-success">📥 Nhập Excel</a>
        <a href="/webdulich/user/add" class="btn btn-success">➕ Thêm mới</a>
        <button type="button" onclick="loadUsers()" class="btn btn-secondary">🔄 Làm mới</button>
    </form>

    <table id="user-table" class="table table-bordered table-striped align-middle">
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
        <tbody></tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // Load danh sách user từ API
    function loadUsers(keyword = '') {
        fetch('/webdulich/api/users/list?keyword=' + encodeURIComponent(keyword))
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('#user-table tbody');
                tbody.innerHTML = '';
                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach((u, i) => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${i+1}</td>
                                <td>${u.MaTVien}</td>
                                <td>${u.Username}</td>
                                <td>${u.HoTen}</td>
                                <td>${u.EmailTVien}</td>
                                <td>${u.DiaChi}</td>
                                <td>${u.SoCMT}</td>
                                <td>${u.SoDT}</td>
                                <td>
                                    <a href="/webdulich/user/edit?id=${u.MaTVien}" class="btn btn-warning btn-sm action-btn">Sửa</a>
                                    <button onclick="deleteUser(${u.MaTVien})" class="btn btn-danger btn-sm action-btn">Xóa</button>
                                </td>
                            </tr>`;
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="9" class="text-center">Không có dữ liệu</td></tr>`;
                }
            });
    }

    // Xóa user qua API
    function deleteUser(id) {
        if (!confirm('Bạn có chắc muốn xóa thành viên này?')) return;
        const formData = new FormData();
        formData.append('id', id);

        fetch('/webdulich/api/users/delete', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    toastr.success(data.message);
                    loadUsers();
                } else {
                    toastr.error(data.message);
                }
            });
    }

    // Tìm kiếm
    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const keyword = this.querySelector('[name="keyword"]').value;
        loadUsers(keyword);
    });

    // Load dữ liệu khi mở trang
    loadUsers();
</script>
</body>
</html>

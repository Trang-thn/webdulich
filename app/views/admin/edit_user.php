<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa thành viên</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/webdulich/public/css/edit_user.css">
</head>
<body>

<div class="container edit-user">
    <h2>✏️ Sửa thông tin thành viên <span id="user-id"></span></h2>

    <form id="edit-form">
        <input type="hidden" name="MaTVien">

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="Username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="HoTen" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="EmailTVien" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="DiaChi" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Số CMT</label>
            <input type="text" name="SoCMT" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Số ĐT</label>
            <input type="text" name="SoDT" class="form-control">
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
            <a href="/webdulich/user/manage" class="btn btn-secondary">⬅️ Quay lại</a>
        </div>
    </form>

    <div id="msg"></div>
</div>

<script>
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');

    function loadUser(id) {
        fetch('/webdulich/api/users/detail?id=' + id)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const u = data.data;
                    document.getElementById('user-id').textContent = '#' + u.MaTVien;
                    document.querySelector('[name="MaTVien"]').value = u.MaTVien;
                    document.querySelector('[name="Username"]').value = u.Username;
                    document.querySelector('[name="HoTen"]').value = u.HoTen;
                    document.querySelector('[name="EmailTVien"]').value = u.EmailTVien;
                    document.querySelector('[name="DiaChi"]').value = u.DiaChi;
                    document.querySelector('[name="SoCMT"]').value = u.SoCMT;
                    document.querySelector('[name="SoDT"]').value = u.SoDT;
                } else {
                    document.getElementById('msg').textContent = data.message;
                    document.getElementById('msg').style.color = 'red';
                }
            })
            .catch(err => {
                document.getElementById('msg').textContent = 'Lỗi kết nối API: ' + err;
                document.getElementById('msg').style.color = 'red';
            });
    }

    document.getElementById('edit-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('/webdulich/api/users/update', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                const msg = document.getElementById('msg');
                if (data.status === 'success') {
                    msg.textContent = data.message;
                    msg.style.color = 'green';
                    setTimeout(() => window.location.href = '/webdulich/user/manage', 1500);
                } else {
                    msg.textContent = data.message;
                    msg.style.color = 'red';
                }
            })
            .catch(err => {
                const msg = document.getElementById('msg');
                msg.textContent = 'Lỗi kết nối API: ' + err;
                msg.style.color = 'red';
            });
    });

    if (id) loadUser(id);
</script>

</body>
</html>

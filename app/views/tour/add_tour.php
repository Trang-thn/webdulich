<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thêm Tour Mới</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" >
  <link rel="stylesheet" href="/webdulich/public/css/add_tour.css">
</head>
<body>

<div class="container add-tour">
  <h2>➕ Thêm Tour Mới</h2>

  <form id="addTourForm" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Tên tour</label>
      <input type="text" name="TenTour" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Giá tour (VND)</label>
      <input type="number" name="GiaTour" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Thời gian tour</label>
      <input type="text" name="TGTour" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Điểm khởi hành</label>
      <input type="text" name="DiemKhoiHanh" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Ngày khởi hành</label>
      <input type="date" name="NgayKhoiHanh" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Mô tả tour</label>
      <textarea name="NoiDungTour" rows="4" class="form-control"></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Ảnh tour</label>
      <input type="file" name="AnhTour[]" multiple accept="image/*" class="form-control">
    </div>

    <div class="d-flex gap-3">
      <button type="submit" class="btn btn-primary">💾 Đăng tour</button>
      <a href="/webdulich/tour/manage" class="btn btn-secondary">⬅️ Quay lại</a>
    </div>
  </form>

  <div id="msg"></div>
</div>

<script>
document.getElementById('addTourForm').addEventListener('submit', e => {
  e.preventDefault();
  const formData = new FormData(e.target);

  fetch('/webdulich/api/tours/add', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
      const msg = document.getElementById('msg');
      msg.textContent = data.message;
      msg.style.color = data.status === 'success' ? 'green' : 'red';
      if (data.status === 'success') {
        setTimeout(() => window.location.href = '/webdulich/tour/manage', 1500);
      }
    })
    .catch(err => {
      const msg = document.getElementById('msg');
      msg.textContent = 'Lỗi khi thêm tour: ' + err;
      msg.style.color = 'red';
    });
});
</script>

</body>
</html>

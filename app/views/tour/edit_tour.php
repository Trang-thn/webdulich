<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Sửa Tour</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { font-family: 'Segoe UI', sans-serif; }
    .edit-tour {
      max-width: 700px;
      margin: 60px auto;
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
    }
    .edit-tour h2 {
      text-align: center;
      margin-bottom: 35px;
      letter-spacing: 1px;
      color: #1c1c1c;
    }
    .edit-tour h2 span { color: #bfa25a; }
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
      .edit-tour { padding: 25px; }
    }
    #msg { text-align:center; margin-top:15px; font-weight:600; }
    img { margin-top: 10px; border-radius: 4px; max-width: 100%; }
  </style>
</head>
<body>

<div class="container edit-tour">
  <h2>✏️ Sửa Tour</h2>

  <form id="editTourForm" enctype="multipart/form-data">
    <input type="hidden" name="MaTour" id="MaTour">

    <div class="mb-3">
      <label class="form-label">Tên tour</label>
      <input type="text" id="TenTour" name="TenTour" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Giá tour (VND)</label>
      <input type="number" id="GiaTour" name="GiaTour" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Thời gian tour</label>
      <input type="text" id="TGTour" name="TGTour" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Điểm khởi hành</label>
      <input type="text" id="DiemKhoiHanh" name="DiemKhoiHanh" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Ngày khởi hành</label>
      <input type="datetime-local" id="NgayKhoiHanh" name="NgayKhoiHanh" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Mô tả tour</label>
      <textarea id="NoiDungTour" name="NoiDungTour" rows="4" class="form-control"></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Ảnh hiện tại</label>
      <div id="currentImage"></div>
    </div>

    <div class="mb-3">
      <label class="form-label">Cập nhật ảnh mới</label>
      <input type="file" id="AnhTour" name="AnhTour[]" multiple accept="image/*" class="form-control">
    </div>

    <div class="d-flex gap-3">
      <button type="submit" class="btn btn-primary">💾 Cập nhật tour</button>
      <a href="/webdulich/tour/manage" class="btn btn-secondary">⬅️ Quay lại</a>
    </div>
  </form>

  <div id="msg"></div>
</div>

<script>
// Lấy id từ URL
const params = new URLSearchParams(window.location.search);
const id = params.get('id');

// Nạp dữ liệu tour vào form
fetch('/webdulich/api/tours/detail?id=' + id)
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success' && data.data) {
      const tour = data.data;
      document.getElementById('MaTour').value = tour.MaTour || '';
      document.getElementById('TenTour').value = tour.TenTour || '';
      document.getElementById('GiaTour').value = tour.GiaTour || '';
      document.getElementById('TGTour').value = tour.TGTour || '';
      document.getElementById('DiemKhoiHanh').value = tour.DiemKhoiHanh || '';
      document.getElementById('NgayKhoiHanh').value = tour.NgayKhoiHanh
        ? tour.NgayKhoiHanh.replace(' ', 'T').slice(0,16)
        : '';
      document.getElementById('NoiDungTour').value = tour.NoiDungTour || '';

      // Hiển thị ảnh hiện tại
      const imgs = tour.AnhTour ? tour.AnhTour.split(',') : [];
      const imgContainer = document.getElementById('currentImage');
      imgContainer.innerHTML = imgs.map(img =>
        `<img src="/webdulich/public/images/images/${img}" width="100" style="margin-right:5px;">`
      ).join('');
    } else {
      document.getElementById('msg').textContent = 'Không thể tải dữ liệu tour';
      document.getElementById('msg').style.color = 'red';
    }
  })
  .catch(err => {
    document.getElementById('msg').textContent = 'Lỗi khi tải dữ liệu tour: ' + err;
    document.getElementById('msg').style.color = 'red';
  });

// Xử lý submit form cập nhật
document.getElementById('editTourForm').addEventListener('submit', e => {
  e.preventDefault();
  const formData = new FormData(e.target);
  formData.append('MaTour', id);

  fetch('/webdulich/api/tours/edit', { method: 'POST', body: formData })
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
      msg.textContent = 'Lỗi khi cập nhật tour: ' + err;
      msg.style.color = 'red';
    });
});
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý Tour</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/webdulich/public/css/manage_tour.css">
</head>
<body>
<div class="manage-container">
  <h2>🌏 Quản lý Tour</h2>
  <form id="search-form" class="search-bar">
    <input type="text" name="keyword" class="form-control"
           placeholder="Tìm theo ID, Tên tour, Điểm khởi hành...">
    <button type="submit" class="btn btn-primary">🔍 Tìm kiếm</button>
    <a href="/webdulich/tour/add" class="btn btn-success">➕ Thêm mới</a>
    <button type="button" onclick="loadTours()" class="btn btn-secondary">🔄 Làm mới</button>
  </form>

  <table id="tour-table" class="table table-bordered table-striped align-middle">
    <thead>
      <tr>
        <th>STT</th>
        <th>ID</th>
        <th>Tên tour</th>
        <th>Giá</th>
        <th>Thời gian</th>
        <th>Khởi hành</th>
        <th>Ảnh</th>
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
function loadTours(keyword = '') {
  const url = keyword
    ? '/webdulich/api/tours/search?keyword=' + encodeURIComponent(keyword)
    : '/webdulich/api/tours';

  fetch(url).then(res => res.json()).then(data => {
      const tbody = document.querySelector('#tour-table tbody');
      tbody.innerHTML = '';
      if (data.status === 'success' && data.data.length > 0) {
        data.data.forEach((tour, i) => {
          const imgs = tour.AnhTour ? tour.AnhTour.split(',') : [];
          const firstImg = imgs[0] ? `<img src="/webdulich/public/images/images/${imgs[0]}" width="100">` : '';
          tbody.innerHTML += `
            <tr>
              <td>${i+1}</td>
              <td>${tour.MaTour}</td>
              <td>${tour.TenTour}</td>
              <td>${new Intl.NumberFormat('vi-VN').format(tour.GiaTour)} đ</td>
              <td>${tour.TGTour}</td>
              <td>${tour.DiemKhoiHanh}</td>
              <td>${firstImg}</td>
              <td>
                <a href="/webdulich/tour/edit?id=${tour.MaTour}" class="btn btn-warning btn-sm action-btn">Sửa</a>
                <button onclick="deleteTour(${tour.MaTour})" class="btn btn-danger btn-sm action-btn">Xóa</button>
              </td>
            </tr>`;
        });
      } else {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center">Không có dữ liệu</td></tr>`;
      }
    });
}

  function deleteTour(id) {
    if (!confirm('Bạn có chắc muốn xóa tour này?')) return;
    const formData = new FormData();
    formData.append('id', id);

    fetch('/webdulich/api/tours/delete', { method: 'POST', body: formData })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          toastr.success(data.message);
          loadTours();
        } else {
          toastr.error(data.message);
        }
      });
  }

  document.getElementById('search-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const keyword = this.querySelector('[name="keyword"]').value;
    loadTours(keyword); // gọi API và render kết quả ngay trong bảng
  });

  loadTours();
</script>
</body>
</html>

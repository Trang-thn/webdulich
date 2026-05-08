<?php include __DIR__ . "/../home/home_banner.php"; ?>

<h2 class="title">🌏 Danh sách Tour Du Lịch</h2>
<div id="tour-grid" class="tour-grid"></div>

<?php include __DIR__ . "/../home/home_footer.php"; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
  fetch('/webdulich/api/tours') // gọi API @list
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        const container = document.getElementById('tour-grid');
        container.innerHTML = '';
        data.data.forEach(tour => {
          const imgs = tour.AnhTour ? tour.AnhTour.split(',') : [];
          const firstImg = imgs[0] ? `/webdulich/public/images/images/${imgs[0]}` : '/webdulich/public/images/no-image.png';

          container.innerHTML += `
            <div class="tour-card">
              <img src="${firstImg}" alt="${tour.TenTour}">
              <div class="tour-info">
                <h3>${tour.TenTour}</h3>
                <p>📍 ${tour.DiemKhoiHanh}</p>
                <p>⏰ ${tour.TGTour}</p>
                <p>🛫 ${tour.NgayKhoiHanh ? new Date(tour.NgayKhoiHanh).toLocaleDateString('vi-VN') : 'Chưa có ngày khởi hành'}</p>
                <div class="price">${new Intl.NumberFormat('vi-VN').format(tour.GiaTour)}đ</div>
              </div>
              <a class="btn" href="/webdulich/detail?id=${tour.MaTour}">Chi tiết</a>
              <a class="btn btn-book" href="/webdulich/booking/form?tour_id=${tour.MaTour}">Đặt tour</a>
            </div>
          `;
        });
      } else {
        alert('Không thể tải danh sách tour: ' + data.message);
      }
    })
    .catch(err => alert('Lỗi khi tải tour: ' + err));
});
</script>

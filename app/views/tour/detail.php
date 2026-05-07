<?php include __DIR__ . "/../home/home_banner.php"; ?>

<div class="detail-wrapper">
    <!-- LEFT: Thông tin tour -->
    <div class="detail-left">
        <h1 class="tour-title" id="tour-title"></h1>
        <div class="tour-meta">
            <p><b>⏰ Thời gian:</b> <span id="tg-tour"></span></p>
            <p><b>📍 Điểm xuất phát:</b> <span id="diem-khoi-hanh"></span></p>
            <p>🛫 <span id="ngay-khoi-hanh"></span></p>
        </div>
        <div class="tour-gallery" id="tour-gallery"></div>
        <div class="tour-content">
            <h2>📌 Chương trình tour</h2>
            <div id="noi-dung-tour"></div>
        </div>
    </div>

    <!-- RIGHT: Hướng dẫn & hỗ trợ -->
    <div class="detail-right">
        <div class="box">
            <h3>📘 HƯỚNG DẪN ĐẶT TOUR</h3>
            <ul>
                <li>Quý khách để lại thông tin đặt tour.</li>
                <li>Nhân viên CSKH sẽ liên hệ xác nhận.</li>
                <li>Chưa cần thanh toán ngay.</li>
                <li>Hotline: <b>+8433 233 7357</b></li>
            </ul>
        </div>

        <div class="box">
            <h3>📞 HỖ TRỢ KHÁCH HÀNG</h3>
            <p>HN: <b>+8433 233 7357</b></p>
            <p>QT: <b>+19722026548</b></p>
            <p>Email: <b>info@dongphuongtours.com</b></p>

            <div class="price-big" id="gia-tour"></div>
            <a id="btn-book" class="btn-book">Đặt tour ngay</a>
            <a href="/webdulich/tour" class="btn-back">⬅ Quay lại danh sách tour</a>
        </div>

        <!-- Bình luận -->
        <div class="tour-comments mt-5">
            <h2>💬 Bình luận cho tour này</h2>
            <form id="comment-form">
                <input type="hidden" name="maTour" id="maTour-hidden">
                <label for="noiDungCom">Nội dung bình luận</label>
                <textarea name="noiDungCom" id="noiDungCom" required></textarea>
                <label for="vote">Đánh giá</label>
                <select name="vote" id="vote" required>
                    <option value="5">⭐⭐⭐⭐⭐</option>
                    <option value="4">⭐⭐⭐⭐</option>
                    <option value="3">⭐⭐⭐</option>
                    <option value="2">⭐⭐</option>
                    <option value="1">⭐</option>
                </select>
                <button type="submit" class="btn btn-primary mt-2">Gửi bình luận</button>
            </form>
            <div id="comment-msg" style="margin-top:10px;font-weight:600;"></div>
            <div class="comment-list mt-4" id="comment-list"></div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../home/home_footer.php"; ?>

<script>
const params = new URLSearchParams(window.location.search);
const id = params.get('id');

// Load tour
if (id) {
  fetch('/webdulich/api/tours/detail?id=' + id)
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        // Nếu API trả về data.data (không có tour bên trong)
        const tour = data.data; 

        document.getElementById('tour-title').textContent = tour.TenTour;
        document.getElementById('tg-tour').textContent = tour.TGTour;
        document.getElementById('diem-khoi-hanh').textContent = tour.DiemKhoiHanh;
        document.getElementById('ngay-khoi-hanh').textContent = tour.NgayKhoiHanh 
            ? new Date(tour.NgayKhoiHanh).toLocaleDateString('vi-VN') 
            : 'Chưa có ngày khởi hành';
        document.getElementById('noi-dung-tour').innerHTML = tour.NoiDungTour.replace(/\n/g, '<br>');
        document.getElementById('gia-tour').textContent = 
            new Intl.NumberFormat('vi-VN').format(tour.GiaTour) + 'đ';
        document.getElementById('btn-book').href = '/webdulich/booking/form?tour_id=' + tour.MaTour;
        document.getElementById('maTour-hidden').value = tour.MaTour;

        // Render gallery ảnh
        const gallery = document.getElementById('tour-gallery');
        gallery.innerHTML = '';
        const imgs = tour.AnhTour ? tour.AnhTour.split(',') : [];
        imgs.forEach(img => {
          gallery.innerHTML += `<img src="/webdulich/public/images/images/${img.trim()}" alt="${tour.TenTour}" style="max-width:150px;margin:5px;border-radius:4px;">`;
        });
      }
    });

  // Load comments
  fetch('/webdulich/api/comments/listByTour?id=' + id)
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        const list = document.getElementById('comment-list');
        list.innerHTML = '';
        if (data.data.length > 0) {
          data.data.forEach(c => {
            const div = document.createElement('div');
            div.className = 'comment-item border-bottom py-2';
            div.innerHTML = `<b>${c.Username}:</b>
                             <p>${c.NoiDungCom}</p>
                             <span>Đánh giá: ${'⭐'.repeat(c.Vote)}</span>`;
            list.appendChild(div);
          });
        } else {
          list.innerHTML = '<p>Chưa có bình luận nào cho tour này.</p>';
        }
      }
    });
}

// Submit comment
document.getElementById('comment-form').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch('/webdulich/api/comments/add', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
      const msg = document.getElementById('comment-msg');
      msg.textContent = data.message;
      msg.style.color = data.status === 'success' ? 'green' : 'red';
      if (data.status === 'success') {
        this.reset();
      }
    })
    .catch(err => {
      document.getElementById('comment-msg').textContent = 'Lỗi kết nối API: ' + err;
      document.getElementById('comment-msg').style.color = 'red';
    });
});
</script>

<?php
require_once __DIR__ . "/../../../config/database.php";
$conn = Database::getConnection();

// Xóa tour (prepared statement để an toàn)
if (isset($_POST['deleteMaTour'])) {
  $MaTour = intval($_POST['deleteMaTour']);
  $stmt = $conn->prepare("DELETE FROM tour WHERE MaTour = ?");
  $stmt->bind_param("i", $MaTour);
  if ($stmt->execute()) {
    echo "ok";
  } else {
    echo "error: " . $stmt->error;
  }
  exit;
}
?>
<?php if (isset($_GET['status'])): ?>
  <?php if ($_GET['status'] === 'success'): ?>
    <div class="alert alert-success">Đăng/Cập nhật tour thành công!</div>
  <?php elseif ($_GET['status'] === 'error'): ?>
    <div class="alert alert-danger">Có lỗi khi đăng/cập nhật tour!</div>
  <?php endif; ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý Tour</title>
  <link rel="stylesheet" href="/webdulich/public/css/admin.css">
  <style>
    .selected { background: #f0f8ff; }
    .modal { display:none; position:fixed; z-index:10; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,.35); }
    .modal-content { background:#fff; margin:60px auto; padding:20px; border-radius:8px; width:600px; max-width:90%; position:relative; }
    .close { position:absolute; right:12px; top:8px; font-size:22px; cursor:pointer; }
    table { width:100%; border-collapse:collapse; margin-top:12px; }
    th, td { border:1px solid #ddd; padding:8px; text-align:left; }
    th { background:#f7f7f7; }
    .actions button { margin-left:6px; }
    .alert { padding:10px 12px; border-radius:6px; margin:10px 0; }
    .alert-success { background:#e6ffed; color:#0f5132; border:1px solid #badbcc; }
    .alert-danger  { background:#ffe6e6; color:#842029; border:1px solid #f5c2c7; }
  </style>
</head>
<body>
  <div class="main-container">
    <h2>Quản lý Tour</h2>

    <div class="actions">
      <input type="text" id="searchInput" placeholder="Tìm kiếm..." style="padding:6px 10px; width:200px; border-radius:6px; border:1px solid #ccc;">
      <button onclick="searchTour()">Tìm kiếm</button>
      <button onclick="resetSearch()">Hiển thị tất cả</button>
      <button onclick="openAddTour()">+ Thêm</button>
      <button onclick="editSelectedTour()">Sửa</button>
      <button onclick="deleteSelectedTour()">Xóa</button>
    </div>

    <table>
      <tr>
        <th>ID</th>
        <th>Tên tour</th>
        <th>Giá</th>
        <th>Thời gian</th>
        <th>Khởi hành</th>
        <th>Ảnh</th>
      </tr>
      <?php
      // Lấy đầy đủ trường, bao gồm NgayKhoiHanh để JS điền vào modal sửa
      $result = mysqli_query($conn, "SELECT MaTour, TenTour, GiaTour, TGTour, DiemKhoiHanh, NgayKhoiHanh, AnhTour, NoiDungTour FROM tour ORDER BY MaTour");
      while ($row = mysqli_fetch_assoc($result)) {
        $tourJson = htmlspecialchars(json_encode($row, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
        $imgs = !empty($row['AnhTour']) ? explode(",", $row['AnhTour']) : [];
        $firstImg = $imgs[0] ?? "";
        $firstImgTag = $firstImg ? "<img src='/webdulich/public/images/images/{$firstImg}' width='100' style='border-radius:6px;'>" : "<em>Không có ảnh</em>";
        echo "<tr data-tour='{$tourJson}' onclick=\"selectTour(this)\">
                  <td>{$row['MaTour']}</td>
                  <td>{$row['TenTour']}</td>
                  <td>" . number_format((float)$row['GiaTour'], 0, ',', '.') . " đ</td>
                  <td>{$row['TGTour']}</td>
                  <td>{$row['DiemKhoiHanh']}</td>
                  <td>{$firstImgTag}</td>
                </tr>";
      }
      ?>
    </table>
  </div>

  <!-- Modal thêm tour -->
  <div id="addTourModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeAddTour()">&times;</span>
      <h3>Thêm Tour Mới</h3>
      <form action="/webdulich/app/views/tour/add_tour.php" method="POST" enctype="multipart/form-data">
        <label>Tên tour:</label>
        <input type="text" name="TenTour" required>

        <label>Giá tour (VND):</label>
        <input type="number" name="GiaTour" required>

        <label>Thời gian tour:</label>
        <input type="text" name="TGTour" required>

        <label>Điểm khởi hành:</label>
        <input type="text" name="DiemKhoiHanh" required>

        <label for="NgayKhoiHanh">Ngày khởi hành:</label>
        <input type="datetime-local" name="NgayKhoiHanh" required min="<?= date('Y-m-d\TH:i') ?>">

        <label>Mô tả tour:</label>
        <textarea name="NoiDungTour" rows="4"></textarea>

        <label>Thêm ảnh:</label>
        <input type="file" name="AnhTour[]" accept="image/*" multiple>

        <button type="submit">Đăng tour</button>
      </form>
    </div>
  </div>

  <!-- Modal sửa tour -->
  <div id="editTourModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeEditTour()">&times;</span>
      <h3>Sửa Tour</h3>
      <form action="/webdulich/app/views/tour/edit_tour.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="MaTour" id="editMaTour">

        <label>Tên tour:</label>
        <input type="text" name="TenTour" id="editTenTour" required>

        <label>Giá tour (VND):</label>
        <input type="number" name="GiaTour" id="editGiaTour" required>

        <label>Thời gian tour:</label>
        <input type="text" name="TGTour" id="editTGTour" required>

        <label>Điểm khởi hành:</label>
        <input type="text" name="DiemKhoiHanh" id="editDiemKhoiHanh" required>

        <label for="NgayKhoiHanh">Ngày khởi hành:</label>
        <input type="datetime-local" name="NgayKhoiHanh" id="editNgayKhoiHanh" required>

        <label>Mô tả tour:</label>
        <textarea name="NoiDungTour" id="editNoiDungTour" rows="4"></textarea>

        <label>Ảnh hiện tại:</label>
        <div id="oldImagesContainer" style="display:flex; gap:10px; flex-wrap:wrap;"></div>

        <img id="editAnhPreview" src="" width="120" style="display:none; margin:10px 0; border-radius:6px;">

        <label>Thay ảnh (nếu cần):</label>
        <input type="file" name="AnhTour[]" accept="image/*" multiple>

        <label><input type="checkbox" name="replaceAll" value="1"> Thay toàn bộ ảnh bằng ảnh mới</label>

        <button type="submit">Cập nhật tour</button>
      </form>
    </div>
  </div>

<script>
let selectedTour = null;
let selectedRow  = null;

function selectTour(row) {
  document.querySelectorAll("table tr").forEach(tr => tr.classList.remove("selected"));
  row.classList.add("selected");
  selectedRow = row; 
  const json = row.getAttribute('data-tour');
  selectedTour = JSON.parse(json);
}

function openAddTour()  { document.getElementById("addTourModal").style.display  = "block"; }
function closeAddTour() { document.getElementById("addTourModal").style.display  = "none";  }
function closeEditTour(){ document.getElementById("editTourModal").style.display = "none";  }

window.addEventListener('click', function(e) {
  const addM  = document.getElementById("addTourModal");
  const editM = document.getElementById("editTourModal");
  if (e.target === addM)  closeAddTour();
  if (e.target === editM) closeEditTour();
});
window.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') { closeAddTour(); closeEditTour(); }
});

function editSelectedTour() {
  if (!selectedTour) { alert("Vui lòng chọn tour cần sửa"); return; }
  document.getElementById("editTourModal").style.display = "block";

  // Fill basic fields
  document.getElementById("editMaTour").value        = selectedTour.MaTour;
  document.getElementById("editTenTour").value       = selectedTour.TenTour;
  document.getElementById("editGiaTour").value       = selectedTour.GiaTour;
  document.getElementById("editTGTour").value        = selectedTour.TGTour;
  document.getElementById("editDiemKhoiHanh").value  = selectedTour.DiemKhoiHanh;
  document.getElementById("editNoiDungTour").value   = selectedTour.NoiDungTour || "";

  // Ngày khởi hành: nếu DB lưu dạng 'YYYY-MM-DD HH:MM:SS', chuyển sang 'YYYY-MM-DDTHH:MM'
  let ngay = selectedTour.NgayKhoiHanh || "";
  if (ngay && ngay.includes(" ")) {
    const [datePart, timePart] = ngay.split(" ");
    const hm = timePart.slice(0,5); // HH:MM
    ngay = `${datePart}T${hm}`;
  }
  document.getElementById("editNgayKhoiHanh").value = ngay;

  // Render old images with "keep" checkboxes
  const container = document.getElementById("oldImagesContainer");
  container.innerHTML = ""; // clear previous

  const imagesStr = selectedTour.AnhTour || "";
  const images = imagesStr ? imagesStr.split(",").map(s => s.trim()).filter(s => s !== "") : [];

  if (images.length === 0) {
    container.innerHTML = "<em>Không có ảnh hiện tại</em>";
    document.getElementById("editAnhPreview").style.display = "none";
  } else {
    images.forEach(img => {
      const item = document.createElement("div");
      item.style.cssText = "display:inline-block; text-align:center;";
      item.innerHTML = `
        <img src="/webdulich/public/images/images/${img}" width="100" style="border-radius:6px; display:block; margin-bottom:6px;">
        <label style="font-size:12px;">
          <input type="checkbox" name="keepImages[]" value="${img}" checked> Giữ lại
        </label>
      `;
      container.appendChild(item);
    });
    // Preview ảnh đầu tiên
    document.getElementById("editAnhPreview").src = "/webdulich/public/images/images/" + images[0];
    document.getElementById("editAnhPreview").style.display = "block";
  }
}

function resetSearch() {
  document.getElementById("searchInput").value = "";
  const rows = document.querySelectorAll("table tr");
  rows.forEach((row, index) => {
    if (index === 0) return;
    row.style.display = "";
  });
}

function searchTour() {
  const keyword = document.getElementById("searchInput").value.toLowerCase().trim();
  const rows = document.querySelectorAll("table tr");

  rows.forEach((row, index) => {
    if (index === 0) return; 
    const tourData = row.getAttribute("data-tour");
    if (!tourData) return;
    const tour = JSON.parse(tourData);
    const tenTour = (tour.TenTour || "").toLowerCase();
    row.style.display = tenTour.includes(keyword) ? "" : "none";
  });
}

function deleteSelectedTour() {
  if (!selectedTour) { alert("Vui lòng chọn tour cần xóa"); return; }
  if (!confirm("Bạn có chắc chắn muốn xóa tour: " + selectedTour.TenTour + " ?")) return;

  fetch("/webdulich/app/views/admin/manage_tour.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "deleteMaTour=" + encodeURIComponent(selectedTour.MaTour)
  })
  .then(res => res.text())
  .then(result => {
    if (result.trim() === "ok") {
      if (selectedRow) selectedRow.remove();
      selectedTour = null;
      selectedRow  = null;
    } else {
      alert("Có lỗi khi xóa tour: " + result);
    }
  })
  .catch(err => {
    alert("Có lỗi kết nối!");
    console.error(err);
  });
}
</script>
</body>
</html>

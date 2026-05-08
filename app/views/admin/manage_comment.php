<style>
    .admin-search {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        justify-content: center;
    }

    .admin-search input[type="text"] {
        padding: 8px 12px;
        border: 1px solid #bbb;
        border-radius: 6px;
        font-size: 14px;
        width: 260px;
        transition: border-color 0.3s;
    }

    .admin-search input[type="text"]:focus {
        border-color: #0077cc;
        outline: none;
    }

    .admin-search button {
        padding: 8px 16px;
        background-color: #0077cc;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    .admin-search button:hover {
        background-color: #005fa3;
        transform: scale(1.05);
    }

    .admin-box {
        max-width: 1000px;
        margin: 30px auto;
        padding: 25px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        font-family: Arial, sans-serif;
    }

    .admin-box h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .admin-table th,
    .admin-table td {
        border: 1px solid #eee;
        padding: 12px;
        text-align: left;
    }

    .admin-table th {
        background-color: #f8f8f8;
        font-weight: bold;
        color: #444;
    }

    .admin-table tr:nth-child(even) {
        background-color: #fafafa;
    }

    .admin-table td.actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .action-btn:hover {
        opacity: 0.85;
    }

    .delete-btn {
        background: #ff4d4d;
        color: #fff;
    }

    .approve-btn {
        background: #28a745;
        color: #fff;
    }

    .approve-btn[disabled] {
        background: #aaa;
        color: #fff;
        cursor: not-allowed;
    }
</style>

<div class="admin-box">
    <h2>Quản lý bình luận (Admin)</h2>
    <div class="admin-search">
        <input type="text" id="searchTour" placeholder="Tìm kiếm bình luận theo mã tour...">
        <button onclick="searchComments()">Tìm kiếm</button>
        <button onclick="loadAllComments()">Làm mới</button>

    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Mã bình luận</th>
                <th>Mã thành viên</th>
                <th>Mã tour</th>
                <th>Nội dung</th>
                <th>Đánh giá</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody id="commentTableBody"></tbody>
    </table>
</div>

<script>
    function renderComments(comments) {
        const tbody = document.getElementById("commentTableBody");
        if (!comments || comments.length === 0) {
            tbody.innerHTML = "<tr><td colspan='7'>Không có bình luận</td></tr>";
            return;
        }
        tbody.innerHTML = comments.map(c => `
        <tr>
            <td>${c.MaCom}</td>
            <td>${c.MaTVien}</td>
            <td>${c.MaTour}</td>
            <td>${c.NoiDungCom}</td>
            <td>${"⭐".repeat(c.Vote)}</td>
            <td>${c.TrangThai == 1 ? "Đã duyệt" : "Chưa duyệt"}</td>
            <td class="actions">
                <button class="action-btn delete-btn" onclick="deleteComment('${c.MaCom}')">Xóa</button>
                ${c.TrangThai == 0 
                    ? `<button class="action-btn approve-btn" onclick="approveComment('${c.MaCom}')">Phê duyệt</button>` 
                    : `<button class="action-btn approve-btn" disabled>Đã phê duyệt</button>`}
            </td>
        </tr>
    `).join("");
    }

    // Lấy tất cả bình luận
    // Lấy tất cả bình luận (RESTful GET)
    // Lấy tất cả bình luận (RESTful GET)
    function loadAllComments() {
        fetch("/webdulich/api/comments", {
                method: "GET"
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    renderComments(data.data);
                    // reset ô tìm kiếm sau khi làm mới
                    document.getElementById("searchTour").value = "";
                } else {
                    document.getElementById("commentTableBody").innerHTML =
                        "<tr><td colspan='7'>Không có dữ liệu</td></tr>";
                }
            })
            .catch(() => {
                document.getElementById("commentTableBody").innerHTML =
                    "<tr><td colspan='7'>Lỗi tải dữ liệu</td></tr>";
            });
    }



    // Tìm kiếm bình luận theo tour
    // Tìm kiếm bình luận theo tour (cho admin)
    function searchComments() {
        const maTour = document.getElementById("searchTour").value.trim();
        if (!maTour) return;
        fetch(`/webdulich/api/comments/allByTour?id=${maTour}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    renderComments(data.data);
                } else {
                    document.getElementById("commentTableBody").innerHTML =
                        "<tr><td colspan='7'>Không có dữ liệu</td></tr>";
                }
            })
            .catch(() => {
                document.getElementById("commentTableBody").innerHTML =
                    "<tr><td colspan='7'>Lỗi tải dữ liệu</td></tr>";
            });
    }



    // Xóa bình luận
    function deleteComment(maCom) {
        if (!confirm("Bạn có chắc muốn xoá bình luận này không?")) return;
        fetch("/webdulich/api/comments/delete", {
                method: "POST",
                body: new URLSearchParams({
                    maCom
                })
            })
            .then(res => res.json())
            .then(() => loadAllComments());
    }

    // Phê duyệt bình luận
    function approveComment(maCom) {
        fetch("/webdulich/api/comments/approve", {
                method: "POST",
                body: new URLSearchParams({
                    maCom
                })
            })

            .then(res => res.json())
            .then(() => loadAllComments());
    }

    // Load mặc định khi mở trang
    loadAllComments();
</script>
<head>
    <meta charset="UTF-8">
    <title>Quản lý bình luận</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/webdulich/public/css/manage_comment.css">
</head>

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

    loadAllComments();
</script>
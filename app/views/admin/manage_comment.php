<style>
    .admin-search {
    display: flex;
    gap: 8px; /* khoảng cách giữa các nút */
    margin-bottom: 15px;
}
.admin-search form {
    display: inline-block;
}


    .admin-search input[type="text"] {
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        width: 250px;
    }

    .admin-search button {
        padding: 8px 16px;
        margin-left: 8px;
        background-color: #0077cc;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .admin-search button:hover {
        background-color: #005fa3;
    }

    .admin-box {
        max-width: 900px;
        margin: 30px auto;
        padding: 25px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background: #fafafa;
        font-family: Arial, sans-serif;
    }

    .admin-box h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table th,
    .admin-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    .admin-table th {
        background-color: #f2f2f2;
    }

    /* để form nằm ngang */
    .admin-table td.actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    /* nút chung */
    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        border: none;
        cursor: pointer;
    }

    /* nút Xóa */
    .delete-btn {
        background: #ff4d4d;
        color: #fff;
    }

    /* nút Phê duyệt */
    .approve-btn {
        background: #28a745;
        color: #fff;
    }

    /* nút Đã phê duyệt */
    .approve-btn[disabled] {
        background: #aaa;
        color: #fff;
        cursor: not-allowed;
    }
</style>

<div class="admin-box">
    <h2>Quản lý bình luận (Admin)</h2>
    <div class="admin-search">
        <form method="GET" action="/webdulich/comment/search">
            <input type="text" name="MaTour" placeholder="Tìm kiếm bình luận...">
            <button type="submit">Tìm kiếm</button>
        </form>
        <form method="GET" action="/webdulich/comment/admin" style="display:inline-block;">
            <button type="submit">Làm mới</button>
        </form>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Mã bình luận</th>
                <th>Mã thành viên</th>
                <th>Mã tour</th>
                <th>Nội dung</th>
                <th>Đánh giá</th>
                <th>Trạng thái</th> <!-- thêm cột mới -->
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['MaCom']; ?></td>
                    <td><?php echo htmlspecialchars($row['MaTVien']); ?></td>
                    <td><?php echo $row['MaTour']; ?></td>
                    <td><?php echo $row['NoiDungCom']; ?></td>
                    <td><?php echo str_repeat("⭐", $row['Vote']); ?></td>

                    <!-- Trạng thái -->
                    <td>
                        <?php echo ($row['TrangThai'] == 1) ? "Đã duyệt" : "Chưa duyệt"; ?>
                    </td>

                    <td class="actions">
                        <form method="POST" action="/webdulich/comment/deleteAdmin"
                            onsubmit="return confirm('Bạn có chắc muốn xoá bình luận này không?');">
                            <input type="hidden" name="maCom" value="<?php echo $row['MaCom']; ?>">
                            <button type="submit" class="action-btn delete-btn">Xóa</button>
                        </form>

                        <?php if ($row['TrangThai'] == 0) { ?>
                            <form method="POST" action="/webdulich/comment/approveAdmin">
                                <input type="hidden" name="maCom" value="<?php echo $row['MaCom']; ?>">
                                <button type="submit" class="action-btn approve-btn">Phê duyệt</button>
                            </form>
                        <?php } else { ?>
                            <button type="button" class="action-btn approve-btn" disabled>Đã phê duyệt</button>
                        <?php } ?>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>
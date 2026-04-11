<h2>🖼️ Quản lý ảnh tour</h2>

<a class="btn" href="index.php?action=addGallery">➕ Thêm ảnh</a>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Ảnh</th>
        <th>Mô tả</th>
        <th>Hành động</th>
    </tr>

    <?php while ($row = $images->fetch_assoc()): ?>
    <tr>
        <td><?= $row['MaGL'] ?></td>
        <td>
            <img src="/webdulich/public/images/images/<?= $row['LinkAnh'] ?>" width="120">
        </td>
        <td><?= $row['MoTa'] ?></td>
        <td>
            <a class="btn-danger"
               href="index.php?action=deleteGallery&id=<?= $row['MaGL'] ?>">
               ❌ Xóa
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

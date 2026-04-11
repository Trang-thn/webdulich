<?php
require_once "config/db.php";

class LoaiTour {

    public static function getAll() {
        global $conn;
        return $conn->query("SELECT * FROM LOAITOUR");
    }

    public static function insert($ten) {
        global $conn;
        $sql = "INSERT INTO LOAITOUR(TenLoai) VALUES ('$ten')";
        return $conn->query($sql);
    }

    public static function delete($id) {
        global $conn;
        return $conn->query("DELETE FROM LOAITOUR WHERE MaLoai='$id'");
    }
}

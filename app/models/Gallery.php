<?php
require_once __DIR__ . "/../../config/database.php";

class Gallery {

    public static function getAll() {
        global $conn;
        return $conn->query("SELECT * FROM GALLERY");
    }

    public static function getByTour($maTour) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM GALLERY WHERE MaTour = ?");
        $stmt->bind_param("i", $maTour);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function insert($maTour, $anh, $moTa) {
        global $conn;
        $stmt = $conn->prepare(
            "INSERT INTO GALLERY (MaTour, LinkAnh, MoTa) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("iss", $maTour, $anh, $moTa);
        return $stmt->execute();
    }

    public static function delete($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM GALLERY WHERE MaGL = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

<?php
class Database {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            self::$conn = mysqli_connect("localhost", "root", "", "webdulich");
            if (!self::$conn) {
                die("Kết nối thất bại: " . mysqli_connect_error());
            }
            mysqli_set_charset(self::$conn, "utf8");
        }
        return self::$conn;
    }
}

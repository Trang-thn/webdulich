<?php
class Admin
{
     private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    public function countTable($table)
    {
        $allowed = ['TOUR', 'THANHVIEN', 'DATTOUR', 'COMMENT'];
        if (!in_array(strtoupper($table), $allowed)) {
            return 0;
        }
        $sql = "SELECT COUNT(*) as total FROM $table";
        $result = $this->conn->query($sql);
        return $result ? ($result->fetch_assoc()['total'] ?? 0) : 0;
    }

    public function getByUsername($username)
    {
        $sql = "SELECT * FROM admin WHERE UserAdmin=? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}

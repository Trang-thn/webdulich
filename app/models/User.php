<?php
require_once __DIR__ . "/../../config/database.php";

class User
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

 
    public function getAll($keyword = null)
    {
        if ($keyword) {
            $sql = "SELECT * FROM THANHVIEN WHERE Username LIKE ? OR HoTen LIKE ? OR EmailTVien LIKE ?";
            $stmt = $this->conn->prepare($sql);
            $kw = "%$keyword%";
            $stmt->bind_param("sss", $kw, $kw, $kw);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        $result = mysqli_query($this->conn, "SELECT * FROM THANHVIEN");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM THANHVIEN WHERE MaTVien=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function existsUsername($username): bool {
    $sql = "SELECT COUNT(*) FROM THANHVIEN WHERE Username = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    return $row[0] > 0;
}


    public function add($data)
    {
        $sql = "INSERT INTO THANHVIEN (Username, PassWord, HoTen, EmailTVien, DiaChi, SoCMT, SoDT)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $pass = password_hash("123456", PASSWORD_DEFAULT);
        $stmt->bind_param("sssssss", $data['Username'], $pass, $data['HoTen'], $data['EmailTVien'], $data['DiaChi'], $data['SoCMT'], $data['SoDT']);
        return $stmt->execute();
    }

    public function update($data)
    {
        $sql = "UPDATE THANHVIEN SET Username=?, HoTen=?, EmailTVien=?, DiaChi=?, SoCMT=?, SoDT=? WHERE MaTVien=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssi", $data['Username'], $data['HoTen'], $data['EmailTVien'], $data['DiaChi'], $data['SoCMT'], $data['SoDT'], $data['MaTVien']);
        return $stmt->execute();
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM THANHVIEN WHERE MaTVien=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }


    public function getByUsername($username)
    {
        $sql = "SELECT * FROM thanhvien WHERE Username=? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function login($username, $password)
    {
        $sql = "SELECT * FROM THANHVIEN WHERE Username=? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['PassWord'])) {
            return $user;
        }
        return null;
    }

    public function register($data) {
    $sql = "INSERT INTO THANHVIEN (Username, PassWord, HoTen, EmailTVien, DiaChi, SoCMT, SoDT, VaiTro)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'user')";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sssssss",
        $data['username'],
        $data['password'],
        $data['hoten'],
        $data['email'],
        $data['diachi'],
        $data['socmt'],
        $data['sodt']
    );
    return $stmt->execute();
}

    public function getProfile($maTVien)
    {
        return $this->getById($maTVien);
    }

    public function updateProfile($maTVien, $data)
    {
        $sql = "UPDATE THANHVIEN SET HoTen=?, EmailTVien=?, DiaChi=?, SoCMT=?, SoDT=? WHERE MaTVien=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssi", $data['HoTen'], $data['EmailTVien'], $data['DiaChi'], $data['SoCMT'], $data['SoDT'], $maTVien);
        return $stmt->execute();
    }

    public function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
    }
}

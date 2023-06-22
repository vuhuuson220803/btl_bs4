<?php
// Thiết lập kết nối với cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Xử lý việc đăng ký và lưu thông tin người dùng vào cơ sở dữ liệu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["password-repeat"];

    // Thực hiện kiểm tra cơ bản
    if (empty($username) || empty($password) || empty($passwordRepeat)) {
        echo "Vui lòng điền đầy đủ thông tin.";
    } elseif ($password != $passwordRepeat) {
        echo "Mật khẩu không khớp.";
    } else {
        // Mã hóa mật khẩu để tăng cường bảo mật
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Chuẩn bị và thực thi câu lệnh SQL
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);
        if ($stmt->execute()) {
            echo "Đăng ký thành công. Đang chuyển hướng đến trang đăng nhập...";
            header("Location: dang-nhap.htm");
            exit();
        } else {
            echo "Lỗi: " . $stmt->error;
        }
    }
}

$conn->close();
?>

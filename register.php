<?php
// Nama : Ibnu Hanafi Assalam
// NIM   : A12.2023.06994

// Langsung membuat koneksi ke database
$servername = "localhost"; // atau IP address jika menggunakan server lain
$username = "root"; // username MySQL
$password = ""; // password MySQL (kosong untuk default XAMPP)
$dbname = "users"; // Ganti dengan nama database yang sesuai

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$error_message = '';  // Variable untuk menampung pesan error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validasi input
    if (empty($username) || empty($password)) {
        $error_message = "Username dan password harus diisi!";
    } else {
        // Hash password sebelum disimpan di database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username sudah ada di database
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Username sudah terdaftar!";
        } else {
            // Proses registrasi jika username belum ada
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            // Cek apakah eksekusi query berhasil
            if ($stmt->execute()) {
                // Redirect ke halaman login setelah registrasi berhasil
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Gagal mendaftar! Error: " . $stmt->error;
            }
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Registrasi Pengguna</h2>

        <!-- Tampilkan pesan error jika ada -->
        <?php if (!empty($error_message)) echo "<div class='alert alert-danger'>$error_message</div>"; ?>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>

        <p class="mt-3 text-center">Sudah memiliki akun? <a href="login.php">Login</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
// Nama : Ibnu Hanafi Assalam
// NIM   : A12.2023.06994

session_start();  // Pastikan hanya dipanggil sekali

// Cek apakah pengguna sudah login
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek jika cookie 'username' ada, dan masukkan ke dalam form
$remembered_username = "";
if (isset($_COOKIE["username"])) {
    $remembered_username = $_COOKIE["username"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST["username"];
    $input_password = $_POST["password"];

    // Query untuk memeriksa username di database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika username ditemukan
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($input_password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"]; // Simpan session

            // Cek apakah pengguna ingin diingat
            if (isset($_POST["remember"])) {
                // Set cookie untuk 'username' selama 30 hari
                setcookie("username", $input_username, time() + (86400 * 30), "/"); // 86400 = 1 day
            } else {
                // Hapus cookie jika 'Remember Me' tidak dicentang
                setcookie("username", "", time() - 3600, "/");
            }

            // Redirect ke halaman dashboard setelah login berhasil
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<p style='color:red;'>Password salah!</p>";
        }
    } else {
        echo "<p style='color:red;'>Username tidak ditemukan!</p>";
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Login Pengguna</h2>

        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo $remembered_username; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <!-- Checkbox Remember Me -->
                <label for="remember">Remember Me</label>
                <input type="checkbox" name="remember" id="remember">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <p class="mt-3 text-center">Belum memiliki akun? <a href="register.php">Daftar</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
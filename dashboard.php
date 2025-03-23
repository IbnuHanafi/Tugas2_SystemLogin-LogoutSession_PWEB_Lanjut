<?php
// Nama : Ibnu Hanafi Assalam
// NIM   : A12.2023.06994

session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION["user_id"])) {
    // Jika session tidak ada, arahkan pengguna ke halaman login
    header("Location: login.php");
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION["user_id"];

// Cek apakah user_id ada di session
echo "User ID dari session: " . $user_id;  // Debugging line

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

// Query untuk mendapatkan data pengguna berdasarkan user_id
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah pengguna ditemukan
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $username = $user["username"];
} else {
    echo "Pengguna tidak ditemukan!";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Selamat datang, <?php echo $username; ?>!</h2>
        <p>Ini adalah halaman dashboard Anda.</p>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
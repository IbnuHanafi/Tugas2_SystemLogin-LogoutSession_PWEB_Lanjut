<?php
// Nama : Ibnu Hanafi Assalam
// NIM   : A12.2023.06994

session_start();

// Hapus semua data session
session_unset(); // Menghapus semua variabel session
session_destroy(); // Menghancurkan session

// Arahkan pengguna ke halaman login setelah logout
header("Location: login.php");
exit();

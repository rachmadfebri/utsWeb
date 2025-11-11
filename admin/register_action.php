<?php
require_once 'config.php'; // Menggunakan config admin

// Validasi input dasar
if (empty($_POST['nama_lengkap']) || empty($_POST['email']) || empty($_POST['password'])) {
    header("Location: register.php?error=Semua field wajib diisi.");
    exit();
}

$nama_lengkap = $_POST['nama_lengkap'];
$email = $_POST['email'];
$password = $_POST['password'];

// HASH PASSWORD! Ini sangat penting untuk keamanan.
// Ini akan menghasilkan string 60 karakter (atau lebih)
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Hubungkan ke DB
$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    header("Location: register.php?error=Koneksi database gagal.");
    exit();
}

// Cek dulu apakah email sudah ada
$query_cek = 'SELECT id FROM users WHERE email = $1';
$result_cek = pg_query_params($dbconn, $query_cek, array($email));

if (pg_num_rows($result_cek) > 0) {
    header("Location: register.php?error=Email sudah terdaftar.");
    pg_close($dbconn);
    exit();
}

// Masukkan user baru
// Pastikan kolom Anda (nama_lengkap, email, password) sudah benar
$query_insert = 'INSERT INTO users (nama_lengkap, email, password) VALUES ($1, $2, $3)';
$result_insert = pg_query_params($dbconn, $query_insert, array($nama_lengkap, $email, $password_hash));

if ($result_insert) {
    header("Location: login.php?success=Registrasi berhasil. Silakan login.");
} else {
    header("Location: register.php?error=Registrasi gagal. Coba lagi.");
}

pg_close($dbconn);
?>
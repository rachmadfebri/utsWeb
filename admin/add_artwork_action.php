<?php
session_start();
require_once 'auth_check.php'; // Pastikan user login
require_once 'config.php';

// Ambil data dari form
$judul = $_POST['judul'] ?? '';
$url_gambar = $_POST['url_gambar'] ?? '';
$deskripsi_singkat = $_POST['deskripsi_singkat'] ?? '';
$deskripsi_lengkap = $_POST['deskripsi_lengkap'] ?? '';
$user_id = $_SESSION['user_id']; // Ambil ID user dari session

// Validasi sederhana
if (empty($judul) || empty($url_gambar) || empty($user_id)) {
    // Seharusnya tidak terjadi jika form memiliki 'required'
    header("Location: manage_artworks.php?message=Error: Data tidak lengkap.");
    exit();
}

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    header("Location: manage_artworks.php?message=Error: Koneksi database gagal.");
    exit();
}

$query = 'INSERT INTO artworks (user_id, judul, deskripsi_singkat, deskripsi_lengkap, url_gambar, tanggal_dibuat) 
          VALUES ($1, $2, $3, $4, $5, NOW())';
          
$params = array(
    $user_id,
    $judul,
    $deskripsi_singkat,
    $deskripsi_lengkap,
    $url_gambar
);

$result = pg_query_params($dbconn, $query, $params);

if ($result) {
    header("Location: manage_artworks.php?message=Karya baru berhasil ditambahkan!");
} else {
    header("Location: manage_artworks.php?message=Error: Gagal menambahkan karya.");
}

pg_close($dbconn);
?>
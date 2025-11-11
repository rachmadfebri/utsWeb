<?php
session_start();
require_once 'auth_check.php'; 
require_once 'config.php';

// Ambil data dari form
$karya_id = $_POST['id'] ?? '';
$judul = $_POST['judul'] ?? '';
$url_gambar = $_POST['url_gambar'] ?? '';
$deskripsi_singkat = $_POST['deskripsi_singkat'] ?? '';
$deskripsi_lengkap = $_POST['deskripsi_lengkap'] ?? '';
$user_id = $_SESSION['user_id'];

// Validasi
if (empty($karya_id) || empty($judul) || empty($url_gambar) || empty($user_id)) {
    header("Location: manage_artworks.php?message=Error: Data tidak lengkap.");
    exit();
}

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    header("Location: manage_artworks.php?message=Error: Koneksi database gagal.");
    exit();
}

$query = 'UPDATE artworks SET 
            judul = $1, 
            deskripsi_singkat = $2, 
            deskripsi_lengkap = $3, 
            url_gambar = $4 
          WHERE id = $5 AND user_id = $6'; // Keamanan: Pastikan update HANYA karya milik user ini
          
$params = array(
    $judul,
    $deskripsi_singkat,
    $deskripsi_lengkap,
    $url_gambar,
    $karya_id,
    $user_id
);

$result = pg_query_params($dbconn, $query, $params);

if ($result) {
    header("Location: manage_artworks.php?message=Karya berhasil diperbarui!");
} else {
    header("Location: manage_artworks.php?message=Error: Gagal memperbarui karya.");
}

pg_close($dbconn);
?>
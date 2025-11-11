<?php
session_start();
require_once 'auth_check.php'; 
require_once 'config.php';

// 1. Ambil ID dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_artworks.php?message=Error: ID Karya tidak valid.");
    exit();
}
$karya_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// 2. Hapus karya dari DB
$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    header("Location: manage_artworks.php?message=Error: Koneksi database gagal.");
    exit();
}

// Keamanan: Pastikan hapus HANYA karya milik user ini
$query = 'DELETE FROM artworks WHERE id = $1 AND user_id = $2';
$result = pg_query_params($dbconn, $query, array($karya_id, $user_id));

if ($result) {
    if (pg_affected_rows($result) > 0) {
        header("Location: manage_artworks.php?message=Karya berhasil dihapus.");
    } else {
        header("Location: manage_artworks.php?message=Error: Karya tidak ditemukan atau Anda tidak punya akses.");
    }
} else {
    header("Location: manage_artworks.php?message=Error: Gagal menghapus karya.");
}

pg_close($dbconn);
?>
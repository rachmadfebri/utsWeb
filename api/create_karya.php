<?php
session_start(); // Wajib
require_once 'config.php';

// Cek Keamanan: Apakah user sudah login?
if (!isset($_SESSION['user_id'])) {
    json_response(['success' => false, 'message' => 'Anda harus login untuk menambah karya.'], 401);
}
// Ambil user_id dari SESSION
$user_id = $_SESSION['user_id']; 

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['judul']) || !isset($input['url_gambar'])) {
    json_response(['success' => false, 'message' => 'Data tidak lengkap.'], 400);
}

$judul = $input['judul'];
$deskripsi_singkat = $input['deskripsi_singkat'] ?? '';
$deskripsi_lengkap = $input['deskripsi_lengkap'] ?? '';
$url_gambar = $input['url_gambar'];

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['success' => false, 'message' => 'Koneksi database gagal.'], 500);
}

$query = 'INSERT INTO artworks (user_id, judul, deskripsi_singkat, deskripsi_lengkap, url_gambar, tanggal_dibuat) 
          VALUES ($1, $2, $3, $4, $5, NOW())';
          
$params = array(
    $user_id, // Gunakan user_id dari session
    $judul,
    $deskripsi_singkat,
    $deskripsi_lengkap,
    $url_gambar
);

$result = pg_query_params($dbconn, $query, $params);

if ($result) {
    json_response(['success' => true, 'message' => 'Karya berhasil ditambahkan.']);
} else {
    json_response(['success' => false, 'message' => 'Gagal menambahkan karya.', 'db_error' => pg_last_error($dbconn)], 500);
}
pg_close($dbconn);
?>
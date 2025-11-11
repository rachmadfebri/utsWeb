<?php
session_start(); // Wajib
require_once 'config.php';

// Cek Keamanan
if (!isset($_SESSION['user_id'])) {
    json_response(['success' => false, 'message' => 'Anda harus login untuk mengedit.'], 401);
}
$user_id = $_SESSION['user_id']; // Ambil user_id dari SESSION

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['id']) || !isset($input['judul'])) {
    json_response(['success' => false, 'message' => 'Data tidak lengkap.'], 400);
}

$karya_id = $input['id'];
$judul = $input['judul'];
$deskripsi_singkat = $input['deskripsi_singkat'] ?? '';
$deskripsi_lengkap = $input['deskripsi_lengkap'] ?? '';
$url_gambar = $input['url_gambar'];

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['success' => false, 'message' => 'Koneksi database gagal.'], 500);
}

// Tambahkan "AND user_id = $6" untuk keamanan
$query = 'UPDATE artworks SET 
            judul = $1, 
            deskripsi_singkat = $2, 
            deskripsi_lengkap = $3, 
            url_gambar = $4 
          WHERE id = $5 AND user_id = $6'; // Pastikan user hanya bisa edit karyanya sendiri
          
$params = array(
    $judul,
    $deskripsi_singkat,
    $deskripsi_lengkap,
    $url_gambar,
    $karya_id,
    $user_id // Tambahkan user_id di parameter
);

$result = pg_query_params($dbconn, $query, $params);

if ($result) {
    if (pg_affected_rows($result) > 0) {
        json_response(['success' => true, 'message' => 'Karya berhasil diperbarui.']);
    } else {
        json_response(['success' => false, 'message' => 'Tidak ada karya yang diperbarui (ID tidak ditemukan atau bukan milik Anda).']);
    }
} else {
    json_response(['success' => false, 'message' => 'Gagal memperbarui karya.', 'db_error' => pg_last_error($dbconn)], 500);
}
pg_close($dbconn);
?>
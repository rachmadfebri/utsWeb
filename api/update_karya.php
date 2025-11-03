<?php
require_once 'config.php';

$input = json_decode(file_get_contents('php://input'), true);

// Validasi, pastikan ID ada
if (!$input || !isset($input['id']) || !isset($input['judul']) || !isset($input['url_gambar'])) {
    json_response(['success' => false, 'message' => 'Data tidak lengkap (ID, judul, url_gambar wajib).'], 400);
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

$query = 'UPDATE artworks SET 
            judul = $1, 
            deskripsi_singkat = $2, 
            deskripsi_lengkap = $3, 
            url_gambar = $4 
          WHERE id = $5';
          
$params = array(
    $judul,
    $deskripsi_singkat,
    $deskripsi_lengkap,
    $url_gambar,
    $karya_id
);

$result = pg_query_params($dbconn, $query, $params);

if ($result) {
    // pg_affected_rows bisa digunakan untuk mengecek apakah ada baris yang benar-benar ter-update
    if (pg_affected_rows($result) > 0) {
        json_response(['success' => true, 'message' => 'Karya berhasil diperbarui.']);
    } else {
        json_response(['success' => false, 'message' => 'Tidak ada karya yang diperbarui (mungkin ID tidak ditemukan).']);
    }
} else {
    json_response(['success' => false, 'message' => 'Gagal memperbarui karya.', 'db_error' => pg_last_error($dbconn)], 500);
}

pg_close($dbconn);
?>
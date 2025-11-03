<?php
require_once 'config.php';

// Mengambil data JSON yang dikirim dari frontend
$input = json_decode(file_get_contents('php://input'), true);

// Validasi
if (!$input || !isset($input['judul']) || !isset($input['url_gambar'])) {
    json_response(['success' => false, 'message' => 'Data tidak lengkap.'], 400);
}

// Untuk sementara, kita hardcode user_id = 1, sama seperti di get_profil.php
// Dalam aplikasi nyata, ini harus diambil dari SESSION login
$user_id = 1; 

$judul = $input['judul'];
$deskripsi_singkat = $input['deskripsi_singkat'] ?? '';
$deskripsi_lengkap = $input['deskripsi_lengkap'] ?? '';
$url_gambar = $input['url_gambar'];

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['success' => false, 'message' => 'Koneksi database gagal.'], 500);
}

// Query untuk memasukkan data baru
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
    json_response(['success' => true, 'message' => 'Karya berhasil ditambahkan.']);
} else {
    json_response(['success' => false, 'message' => 'Gagal menambahkan karya.', 'db_error' => pg_last_error($dbconn)], 500);
}

pg_close($dbconn);
?>
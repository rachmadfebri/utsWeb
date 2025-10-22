<?php
require_once 'config.php';

// pastikan ID karya ada di URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    json_response(['error' => 'ID Karya tidak valid.'], 400);
}

$work_id = intval($_GET['id']);

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['error' => 'Tidak dapat terhubung ke database'], 500);
}

// mengambil detail satu karya berdasarkan ID
$query = 'SELECT w.judul, w.deskripsi_lengkap, w.url_gambar, w.tanggal_dibuat, u.nama_lengkap, u.jabatan 
          FROM works w 
          JOIN users u ON w.user_id = u.id 
          WHERE w.id = $1';

$result = pg_query_params($dbconn, $query, array($work_id));

if (!$result) {
    json_response(['error' => 'Query gagal'], 500);
}

$work = pg_fetch_assoc($result);

if (!$work) {
    json_response(['error' => 'Karya tidak ditemukan.'], 404);
}

json_response($work);

pg_close($dbconn);
?>

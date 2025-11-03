<?php
require_once 'config.php';

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['error' => 'Tidak dapat terhubung ke database'], 500);
}

// mengambil semua karya, diurutkan dari yang terbaru
$query = 'SELECT id, judul, deskripsi_singkat, url_gambar FROM artworks ORDER BY tanggal_dibuat DESC';
$result = pg_query($dbconn, $query);

if (!$result) {
    json_response(['error' => 'Query gagal'], 500);
}

$works = pg_fetch_all($result);

// jika tidak ada karya, kembalikan array kosong
json_response($works ? $works : []);

pg_close($dbconn);
?>

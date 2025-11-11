<?php
// 1. TAMBAHKAN SESSION
session_start();
require_once 'config.php';

// 2. CEK JIKA USER LOGIN
// Jika tidak ada user_id di session, kita kembalikan array kosong
if (!isset($_SESSION['user_id'])) {
    json_response([]); // Kembalikan array kosong, karena tidak ada karya untuk ditampilkan
    exit();
}

// 3. AMBIL user_id DARI SESSION
$user_id = $_SESSION['user_id'];

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['error' => 'Tidak dapat terhubung ke database'], 500);
}

// 4. UBAH QUERY: Tambahkan "WHERE user_id = $1"
$query = 'SELECT id, judul, deskripsi_singkat, url_gambar 
          FROM artworks 
          WHERE user_id = $1 
          ORDER BY tanggal_dibuat DESC';
          
// 5. Gunakan pg_query_params karena kita punya parameter $1
$result = pg_query_params($dbconn, $query, array($user_id));

if (!$result) {
    json_response(['error' => 'Query gagal: ' . pg_last_error($dbconn)], 500);
}

$works = pg_fetch_all($result);

// Kembalikan hasil (yang sekarang sudah difilter)
json_response($works ? $works : []);

pg_close($dbconn);
?>
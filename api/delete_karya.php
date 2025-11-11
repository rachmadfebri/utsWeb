<?php
session_start(); // Wajib
require_once 'config.php';

// Cek Keamanan
if (!isset($_SESSION['user_id'])) {
    json_response(['success' => false, 'message' => 'Anda harus login untuk menghapus.'], 401);
}
$user_id = $_SESSION['user_id']; // Ambil user_id dari SESSION

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['id'])) {
    json_response(['success' => false, 'message' => 'ID Karya tidak ada.'], 400);
}

$karya_id = $input['id'];

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['success' => false, 'message' => 'Koneksi database gagal.'], 500);
}

// Tambahkan "AND user_id = $2" untuk keamanan
$query = 'DELETE FROM artworks WHERE id = $1 AND user_id = $2'; // Pastikan user hanya bisa hapus karyanya sendiri
$result = pg_query_params($dbconn, $query, array($karya_id, $user_id));

if ($result) {
    if (pg_affected_rows($result) > 0) {
        json_response(['success' => true, 'message' => 'Karya berhasil dihapus.']);
    } else {
        json_response(['success' => false, 'message' => 'Karya tidak ditemukan (atau bukan milik Anda).']);
    }
} else {
    json_response(['success' => false, 'message' => 'Gagal menghapus karya.', 'db_error' => pg_last_error($dbconn)], 500);
}
pg_close($dbconn);
?>
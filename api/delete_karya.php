<?php
require_once 'config.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['id'])) {
    json_response(['success' => false, 'message' => 'ID Karya tidak ada.'], 400);
}

$karya_id = $input['id'];

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['success' => false, 'message' => 'Koneksi database gagal.'], 500);
}

$query = 'DELETE FROM artworks WHERE id = $1';
$result = pg_query_params($dbconn, $query, array($karya_id));

if ($result) {
    if (pg_affected_rows($result) > 0) {
        json_response(['success' => true, 'message' => 'Karya berhasil dihapus.']);
    } else {
        json_response(['success' => false, 'message' => 'Karya tidak ditemukan.']);
    }
} else {
    json_response(['success' => false, 'message' => 'Gagal menghapus karya.', 'db_error' => pg_last_error($dbconn)], 500);
}

pg_close($dbconn);
?>
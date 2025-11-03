<?php
// Tampilkan semua error untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['error' => 'Tidak dapat terhubung ke database'], 500);
}

// Untuk contoh, kita selalu ambil pengguna dengan ID = 1
$user_id = 1;

// [DIUBAH] Menggunakan 'role_user' agar cocok dengan struktur database Anda
$query = 'SELECT nama_lengkap, role_user, bio, avatar_url FROM users WHERE id = $1';
$result = pg_query_params($dbconn, $query, array($user_id));

if (!$result) {
    // Menambahkan pesan error detail jika masih gagal
    $db_error_message = pg_last_error($dbconn);
    json_response([
        'error' => 'Query gagal dieksekusi.',
        'db_error' => $db_error_message
    ], 500);
}

$user = pg_fetch_assoc($result);

if (!$user) {
    json_response(['error' => 'Pengguna tidak ditemukan.'], 404);
}

// [DIUBAH] Mengirimkan 'jabatan' ke frontend agar cocok dengan kode JavaScript
// Ini adalah praktik yang baik untuk menjaga konsistensi API
$response_data = [
    'nama_lengkap' => $user['nama_lengkap'],
    'jabatan' => $user['role_user'], // Mengubah nama kunci di sini
    'bio' => $user['bio'],
    'avatar_url' => $user['avatar_url']
];

json_response($response_data);

pg_close($dbconn);
?>

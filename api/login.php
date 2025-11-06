<?php
require_once 'config.php';

// Mengambil data JSON yang dikirim dari frontend
$input = json_decode(file_get_contents('php://input'), true);

// Validasi input awal
if (!$input || !isset($input['email']) || !isset($input['password'])) {
    json_response(['success' => false, 'message' => 'Input tidak lengkap.'], 400);
}

$email = $input['email'];
$password = $input['password'];

// Menghubungkan ke database
$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['success' => false, 'message' => 'Koneksi database gagal.'], 500);
}

// [DIPERBAIKI] Mengambil dari kolom 'password', bukan 'password_hash'
$query = 'SELECT id, password FROM users WHERE email = $1';
$result = pg_query_params($dbconn, $query, array($email));

if (!$result) {
    json_response(['success' => false, 'message' => 'Terjadi kesalahan pada query.'], 500);
}

$user = pg_fetch_assoc($result);

// [DIPERBAIKI] Memverifikasi dengan data dari kolom 'password'
if ($user && $password === $user['password']) {
    // Password cocok! Login berhasil.
    json_response(['success' => true]);
} else {
    // Pengguna tidak ditemukan atau password salah.
    json_response(['success' => false, 'message' => 'Email atau password salah.']);
}

pg_close($dbconn);
?>
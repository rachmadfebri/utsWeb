<?php
require_once 'config.php';

// Ambil data JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['nama_lengkap']) || empty($input['email']) || empty($input['password'])) {
    json_response(['success' => false, 'message' => 'Semua field wajib diisi.'], 400);
}

$nama_lengkap = $input['nama_lengkap'];
$email = $input['email'];
$password = $input['password'];

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['success' => false, 'message' => 'Koneksi database gagal.'], 500);
}

// Cek dulu apakah email sudah ada
$query_cek = 'SELECT id FROM users WHERE email = $1';
$result_cek = pg_query_params($dbconn, $query_cek, array($email));

if (pg_num_rows($result_cek) > 0) {
    json_response(['success' => false, 'message' => 'Email sudah terdaftar.'], 400);
    pg_close($dbconn);
    exit();
}

// Masukkan user baru
$query_insert = 'INSERT INTO users (nama_lengkap, email, password) VALUES ($1, $2, $3)';
$result_insert = pg_query_params($dbconn, $query_insert, array($nama_lengkap, $email, $password_hash));

if ($result_insert) {
    json_response(['success' => true, 'message' => 'Registrasi berhasil. Silakan login.']);
} else {
    json_response(['success' => false, 'message' => 'Registrasi gagal. Coba lagi.'], 500);
}

pg_close($dbconn);
?>
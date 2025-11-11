<?php
// TAMBAHKAN session_start()
session_start();
require_once 'config.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['email']) || !isset($input['password'])) {
    json_response(['success' => false, 'message' => 'Input tidak lengkap.'], 400);
}

$email = $input['email'];
$password = $input['password'];

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['success' => false, 'message' => 'Koneksi database gagal.'], 500);
}

// TAMBAHKAN 'nama_lengkap' ke query
$query = 'SELECT id, nama_lengkap, password FROM users WHERE email = $1';
$result = pg_query_params($dbconn, $query, array($email));

if (!$result) {
    json_response(['success' => false, 'message' => 'Terjadi kesalahan pada query.'], 500);
}

$user = pg_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    // ---- TAMBAHKAN BLOK INI ----
    // Password cocok! Buat session di sini
    // sama seperti di admin/login_action.php
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
    // ----------------------------

    json_response(['success' => true]);
} else {
    json_response(['success' => false, 'message' => 'Email atau password salah.']);
}

pg_close($dbconn);
?>
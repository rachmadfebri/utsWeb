<?php
require_once 'config.php';

// mendapatkan data JSON dari body request
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['email']) || !isset($data['password'])) {
    json_response(['success' => false, 'message' => 'Data tidak lengkap.'], 400);
}

$email = $data['email'];
$password = $data['password'];

// koneksi
$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    json_response(['success' => false, 'message' => 'Error: Tidak dapat terhubung ke PostgreSQL.'], 500);
}

// prepared statement untuk keamanan
$query = 'SELECT id, password_hash FROM users WHERE email = $1';
$result = pg_query_params($dbconn, $query, array($email));

if (!$result) {
    json_response(['success' => false, 'message' => 'Query gagal.'], 500);
}

$user = pg_fetch_assoc($result);

if ($user && password_verify($password, $user['password_hash'])) {
    // login berhasil
    json_response(['success' => true, 'message' => 'Login berhasil.']);
} else {
    // login gagal
    json_response(['success' => false, 'message' => 'Email atau password salah.'], 401);
}

pg_close($dbconn);
?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// -------------------------
// Wajib ada di awal untuk mengakses $_SESSION
session_start(); 
require_once 'config.php';

if (empty($_POST['email']) || empty($_POST['password'])) {
    header("Location: login.php?error=Email dan password wajib diisi.");
    exit();
}

$email = $_POST['email'];
$password_input = $_POST['password'];

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    header("Location: login.php?error=Koneksi database gagal.");
    exit();
}

// Ambil data user, termasuk HASH password dari DB
$query = 'SELECT id, nama_lengkap, password FROM users WHERE email = $1';
$result = pg_query_params($dbconn, $query, array($email));

if (!$result || pg_num_rows($result) == 0) {
    header("Location: login.php?error=Email atau password salah.");
    pg_close($dbconn);
    exit();
}

$user = pg_fetch_assoc($result);
$hashed_password_from_db = $user['password'];

// Verifikasi password yang diinput dengan hash di database
if (password_verify($password_input, $hashed_password_from_db)) {
    // Password COCOK!
    
    // Simpan data user di SESSION
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
    
    // Alihkan ke dashboard admin
    header("Location: index.php");
    exit();

} else {
    // Password TIDAK COCOK
    header("Location: login.php?error=Email atau password salah.");
    exit();
}

pg_close($dbconn);
?>
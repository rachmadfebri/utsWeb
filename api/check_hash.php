<?php
// Tampilkan SEMUA error
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

echo "<h1>Tes Diagnostik Verifikasi Password Final</h1>";

$dbconn = pg_connect($conn_string);
if (!$dbconn) {
    die("<h2 style='color:red;'>Koneksi database gagal.</h2>");
}
echo "<p style='color:green;'>Koneksi Berhasil!</p><hr>";

// Password yang akan kita tes (sama seperti yang Anda ketik di form login)
$password_to_check = 'password123';

// Ambil hash dari database untuk pengguna dengan ID = 1
$query = 'SELECT password FROM users WHERE id = $1';
$result = pg_query_params($dbconn, $query, array(1));

if ($result && pg_num_rows($result) > 0) {
    $user = pg_fetch_assoc($result);
    $hashed_password_from_db = $user['password'];

    echo "<p><strong>Password yang akan diverifikasi:</strong> " . htmlspecialchars($password_to_check) . "</p>";
    echo "<p><strong>Hash yang tersimpan di database:</strong> " . htmlspecialchars($hashed_password_from_db) . "</p>";
    
    // Ini adalah pengecekan paling penting
    $hash_length = strlen($hashed_password_from_db);
    echo "<p><strong>Panjang hash:</strong> <strong style='font-size: 1.2em;'>" . $hash_length . "</strong> karakter.</p>";
    echo "<hr>";

    // Kita lakukan verifikasi di sini
    if (password_verify($password_to_check, $hashed_password_from_db)) {
        echo "<h2 style='color:green;'>VERIFIKASI BERHASIL!</h2>";
        echo "<p>Hash di database cocok dengan password '{$password_to_check}'. Login seharusnya berhasil.</p>";
        echo "<p>Jika login masih gagal, masalahnya ada di cara data dikirim dari index.html.</p>";
    } else {
        echo "<h2 style='color:red;'>VERIFIKASI GAGAL!</h2>";
        echo "<p>Hash di database TIDAK COCOK dengan password '{$password_to_check}'.</p>";
        
        if ($hash_length < 60) {
            echo "<p style='background-color: #ffdddd; padding: 10px; border: 1px solid red;'><strong>INI MASALAHNYA:</strong> Panjang hash hanya <strong>{$hash_length}</strong> karakter. Seharusnya 60. Ini berarti kolom 'password' di database Anda terlalu pendek dan memotong hash-nya.</p>";
        }
        
        echo "<p><strong>LANGKAH PERBAIKAN:</strong></p>";
        echo "<ol>";
        echo "<li>Buka alat database Anda (pgAdmin/DBeaver) dan jalankan perintah SQL ini: <code>ALTER TABLE users ALTER COLUMN password TYPE VARCHAR(255);</code></li>";
        echo "<li>Setelah itu, jalankan kembali file <strong>api/hash_password.php</strong> satu kali lagi untuk menyimpan hash yang utuh.</li>";
        echo "<li>Hapus file hash_password.php setelah selesai.</li>";
        echo "</ol>";
    }

} else {
    echo "<p style='color:red;'>Tidak dapat menemukan pengguna dengan ID = 1.</p>";
}

pg_close($dbconn);
?>


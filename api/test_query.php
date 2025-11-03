<?php
// Tampilkan SEMUA error, tanpa terkecuali, untuk debugging.
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Tes Diagnostik Query Karya</h1>";

// --- Detail Koneksi (Salin dari config.php) ---
$host = 'localhost';
$port = '5432';
$dbname = 'db_web_portofolio';
$user = 'postgres'; // Pastikan ini username Anda
$pass = ''; // <<<< GANTI DENGAN PASSWORD ANDA DI SINI

$conn_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$pass}";
// ----------------------------------------------

echo "<p>Mencoba menghubungkan ke database...</p>";
$dbconn = pg_connect($conn_string);

if (!$dbconn) {
    echo "<h2 style='color:red;'>GAGAL TERHUBUNG!</h2>";
    echo "<p>Pesan Error: " . pg_last_error() . "</p>";
    exit(); // Hentikan skrip di sini jika koneksi gagal
}

echo "<p style='color:green;'>Koneksi Berhasil!</p>";
echo "<hr>";

// --- Tes Query ---
$query = 'SELECT id, judul, deskripsi_singkat, url_gambar FROM artworks ORDER BY tanggal_dibuat DESC';

echo "<p>Mencoba menjalankan query berikut:</p>";
echo "<pre><code>" . htmlspecialchars($query) . "</code></pre>";

$result = pg_query($dbconn, $query);

if (!$result) {
    echo "<h2 style='color:red;'>QUERY GAGAL!</h2>";
    // Baris ini akan memberitahu kita MENGAPA query gagal
    echo "<p>Pesan Error dari PostgreSQL: <strong>" . htmlspecialchars(pg_last_error($dbconn)) . "</strong></p>";
} else {
    echo "<h2 style='color:green;'>QUERY BERHASIL!</h2>";
    $works = pg_fetch_all($result);
    if ($works) {
        echo "<p>Total " . count($works) . " karya ditemukan:</p>";
        echo "<pre>";
        print_r($works);
        echo "</pre>";
    } else {
        echo "<p style='color:orange;'>Query berhasil, tetapi tidak ada data di dalam tabel 'works'.</p>";
    }
}

pg_close($dbconn);
?>
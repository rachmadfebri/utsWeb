<?php
// File ini dipanggil di halaman yang terproteksi (misal: index.php)
// Dia sudah dipanggil SETELAH session_start() di header.php

if (!isset($_SESSION['user_id'])) {
    // Jika user belum login, lempar kembali ke halaman login
    header("Location: login.php?error=Silakan login terlebih dahulu.");
    exit(); // Pastikan skrip berhenti di sini
}

// Jika lolos, skrip akan lanjut mengeksekusi sisa halaman (misal: dashboard)
?>
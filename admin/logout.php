<?php
session_start(); // Wajib ada untuk mengakses session

// Hapus semua data session
session_unset();
session_destroy();

// Alihkan kembali ke halaman login
header("Location: login.php?success=Anda telah berhasil logout.");
exit();
?>
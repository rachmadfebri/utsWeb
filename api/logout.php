<?php
// Selalu mulai session untuk mengaksesnya
session_start();

// Hapus semua variabel session
session_unset();

// Hancurkan session
session_destroy();

// Kirim respons JSON yang memberitahu bahwa logout berhasil
// Ini lebih baik daripada redirect, agar JS bisa menanganinya
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Logout berhasil.']);
exit();
?>
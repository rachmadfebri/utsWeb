<?php 
$page_title = 'Dashboard';
require_once 'header.php'; // 1. Mulai session, Tampilkan header
require_once 'auth_check.php'; // 2. Jalankan penjaga (Auth)
?>

<div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
  <div class="container-fluid py-5">
    <h1 class="display-5 fw-bold">Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</h1>
    <p class="col-md-8 fs-4">
      Gunakan panel ini untuk mengelola konten portofolio Anda.
    </p>
    <a href="manage_artworks.php" class="btn btn-primary btn-lg">
      Mulai Kelola Karya
    </a>
  </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Lihat Halaman Publik</h5>
                <p class="card-text">Lihat bagaimana tampilan portofolio Anda saat ini untuk pengunjung.</p>
                <a href="../profile.html" target="_blank" class="btn btn-secondary">Lihat Profil</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Akun Anda</h5>
                <p class="card-text">Keluar dari sesi admin Anda saat ini.</p>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; // 4. Tampilkan footer ?>
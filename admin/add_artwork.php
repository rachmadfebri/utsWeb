<?php 
$page_title = 'Tambah Karya Baru';
require_once 'header.php'; 
require_once 'auth_check.php'; 
?>

<h2>Tambah Karya Baru</h2>
<p>Isi form di bawah ini untuk menambahkan karya baru ke portofolio Anda.</p>
<hr>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="add_artwork_action.php" method="POST">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Karya*</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="url_gambar" class="form-label">URL Gambar*</label>
                        <input type="url" class="form-control" id="url_gambar" name="url_gambar" placeholder="https://..." required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_singkat" class="form-label">Deskripsi Singkat</label>
                        <input type="text" class="form-control" id="deskripsi_singkat" name="deskripsi_singkat" maxlength="150">
                        <div class="form-text">Maksimal 150 karakter, akan muncul di kartu pratinjau.</div>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_lengkap" class="form-label">Deskripsi Lengkap</label>
                        <textarea class="form-control" id="deskripsi_lengkap" name="deskripsi_lengkap" rows="5"></textarea>
                        <div class="form-text">Penjelasan detail tentang karya Anda.</div>
                    </div>
                    
                    <a href="manage_artworks.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Karya</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
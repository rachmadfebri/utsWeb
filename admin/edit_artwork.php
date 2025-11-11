<?php 
$page_title = 'Edit Karya';
require_once 'header.php'; 
require_once 'auth_check.php'; 
require_once 'config.php';

// 1. Ambil ID dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_artworks.php?message=Error: ID Karya tidak valid.");
    exit();
}
$karya_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// 2. Ambil data karya dari DB
$dbconn = pg_connect($conn_string);
$query = 'SELECT * FROM artworks WHERE id = $1 AND user_id = $2';
$result = pg_query_params($dbconn, $query, array($karya_id, $user_id));
$karya = pg_fetch_assoc($result);

// 3. Jika karya tidak ditemukan ATAU bukan milik user yg login
if (!$karya) {
    pg_close($dbconn);
    header("Location: manage_artworks.php?message=Error: Karya tidak ditemukan atau Anda tidak punya akses.");
    exit();
}
pg_close($dbconn);
?>

<h2>Edit Karya: <?php echo htmlspecialchars($karya['judul']); ?></h2>
<p>Perbarui detail karya Anda di bawah ini.</p>
<hr>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="edit_artwork_action.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $karya['id']; ?>">
                
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Karya*</label>
                        <input type="text" class="form-control" id="judul" name="judul" 
                               value="<?php echo htmlspecialchars($karya['judul']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="url_gambar" class="form-label">URL Gambar*</label>
                        <input type="url" class="form-control" id="url_gambar" name="url_gambar" 
                               value="<?php echo htmlspecialchars($karya['url_gambar']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_singkat" class="form-label">Deskripsi Singkat</label>
                        <input type="text" class="form-control" id="deskripsi_singkat" name="deskripsi_singkat" 
                               value="<?php echo htmlspecialchars($karya['deskripsi_singkat']); ?>" maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_lengkap" class="form-label">Deskripsi Lengkap</label>
                        <textarea class="form-control" id="deskripsi_lengkap" name="deskripsi_lengkap" rows="5"><?php echo htmlspecialchars($karya['deskripsi_lengkap']); ?></textarea>
                    </div>
                    
                    <a href="manage_artworks.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Karya</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
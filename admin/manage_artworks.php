<?php 
$page_title = 'Kelola Karya';
require_once 'header.php'; 
require_once 'auth_check.php'; 
require_once 'config.php'; 

$dbconn = pg_connect($conn_string);
$user_id = $_SESSION['user_id']; 
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Daftar Karya Anda</h2>
    <a href="add_artwork.php" class="btn btn-success">
        <i class="bi bi-plus-lg"></i> Tambah Karya Baru
    </a>
</div>

<?php if (isset($_GET['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Deskripsi Singkat</th>
                        <th>Tgl Dibuat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = 'UPDATE artworks 
                            SET user_id = 2 
                            WHERE user_id = 1';
                    $query = 'SELECT id, judul, deskripsi_singkat, tanggal_dibuat 
                              FROM artworks 
                              WHERE user_id = $1 
                              ORDER BY tanggal_dibuat DESC';
                              
                    $result = pg_query_params($dbconn, $query, array($user_id));
                    
                    if ($result && pg_num_rows($result) > 0):
                        while ($row = pg_fetch_assoc($result)):
                    ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['judul']); ?></td>
                            <td><?php echo htmlspecialchars($row['deskripsi_singkat']); ?></td>
                            <td><?php echo date('d M Y', strtotime($row['tanggal_dibuat'])); ?></td>
                            <td class="text-end">
                                <a href="edit_artwork.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </a>
                                <a href="delete_artwork_action.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus karya ini?');">
                                    <i class="bi bi-trash-fill"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="5" class="text-center">Belum ada karya.</td>
                        </tr>
                    <?php
                    endif;
                    pg_close($dbconn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
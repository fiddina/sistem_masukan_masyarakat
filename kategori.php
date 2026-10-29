<?php
require_once 'config/database.php';
$page_title = 'Kategori Masukan';
require_once 'partials/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nama = trim($_POST['nama_kategori'] ?? '');
    $ket = trim($_POST['keterangan'] ?? '');
    if ($nama === '') {
        flash('error','Nama kategori wajib diisi.');
    } else {
        try {
            if ($id) {
                $stmt=$pdo->prepare("UPDATE kategori SET nama_kategori=?, keterangan=? WHERE id=?");
                $stmt->execute([$nama,$ket,$id]);
                flash('success','Kategori berhasil diperbarui.');
            } else {
                $stmt=$pdo->prepare("INSERT INTO kategori(nama_kategori,keterangan) VALUES(?,?)");
                $stmt->execute([$nama,$ket]);
                flash('success','Kategori berhasil ditambahkan.');
            }
        } catch (PDOException $e) { flash('error','Kategori sudah ada atau tidak dapat disimpan.'); }
    }
    header('Location: kategori.php'); exit;
}
if (isset($_GET['hapus'])) {
    try {
        $stmt=$pdo->prepare("DELETE FROM kategori WHERE id=?");
        $stmt->execute([(int)$_GET['hapus']]);
        flash('success','Kategori berhasil dihapus.');
    } catch(PDOException $e) { flash('error','Kategori tidak dapat dihapus karena masih digunakan oleh data masukan.'); }
    header('Location: kategori.php'); exit;
}
$edit=null;
if(isset($_GET['edit'])) {
    $stmt=$pdo->prepare("SELECT * FROM kategori WHERE id=?"); $stmt->execute([(int)$_GET['edit']]); $edit=$stmt->fetch();
}
$data=$pdo->query("SELECT * FROM kategori ORDER BY nama_kategori")->fetchAll();
?>
<div class="form-card">
<h3><?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?></h3>
<form method="post">
<input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
<div class="field"><label>Nama Kategori</label><input name="nama_kategori" required value="<?= e($edit['nama_kategori'] ?? '') ?>"></div>
<div class="field"><label>Keterangan</label><textarea name="keterangan"><?= e($edit['keterangan'] ?? '') ?></textarea></div>
<button class="btn btn-primary">Simpan</button>
<?php if($edit): ?><a class="btn btn-secondary" href="kategori.php">Batal</a><?php endif; ?>
</form>
</div>
<br>
<div class="table-wrap"><table class="table"><thead><tr><th>No</th><th>Kategori</th><th>Keterangan</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($data as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= e($r['nama_kategori']) ?></td><td><?= e($r['keterangan']) ?></td><td class="actions"><a class="btn btn-warning" href="?edit=<?= $r['id'] ?>">Edit</a><a class="btn btn-danger" href="?hapus=<?= $r['id'] ?>" onclick="return confirm('Hapus kategori ini?')">Hapus</a></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php require_once 'partials/footer.php'; ?>

<?php
require_once 'config/database.php';
$page_title='Detail Masukan';
require_once 'partials/header.php';
$id=(int)($_GET['id']??0);
$stmt=$pdo->prepare("SELECT m.*,k.nama_kategori FROM masukan m JOIN kategori k ON k.id=m.kategori_id WHERE m.id=?");
$stmt->execute([$id]);$r=$stmt->fetch();
if(!$r){echo '<div class="alert error">Data tidak ditemukan.</div>';require_once 'partials/footer.php';exit;}
?>
<div class="form-card">
<h2><?= e($r['judul_masukan']) ?></h2>
<table class="table">
<tr><th width="220">Nama Pengirim</th><td><?= e($r['nama_pengirim']) ?></td></tr>
<tr><th>No. HP</th><td><?= e($r['no_hp']) ?></td></tr>
<tr><th>Alamat</th><td><?= nl2br(e($r['alamat'])) ?></td></tr>
<tr><th>Tanggal Masuk</th><td><?= e($r['tanggal_masuk']) ?></td></tr>
<tr><th>Kategori</th><td><?= e($r['nama_kategori']) ?></td></tr>
<tr><th>Isi Masukan</th><td><?= nl2br(e($r['isi_masukan'])) ?></td></tr>
<tr><th>Lokasi</th><td><?= e($r['lokasi_masalah']) ?></td></tr>
<tr><th>Status</th><td><span class="badge"><?= e($r['status']) ?></span></td></tr>
<tr><th>Pihak Terkait</th><td><?= e($r['pihak_terkait']) ?></td></tr>
<tr><th>Tanggal Diteruskan</th><td><?= e($r['tanggal_diteruskan']) ?></td></tr>
<tr><th>Keterangan Tindak Lanjut</th><td><?= nl2br(e($r['keterangan_tindak_lanjut'])) ?></td></tr>
<tr><th>Lampiran</th><td><?php if($r['lampiran']): ?><a href="uploads/<?= e($r['lampiran']) ?>" target="_blank">Lihat lampiran</a><?php else: ?>Tidak ada<?php endif; ?></td></tr>
</table>
<br><a class="btn btn-warning" href="masukan_form.php?id=<?= $r['id'] ?>">Edit</a>
<a class="btn btn-secondary" href="masukan.php">Kembali</a>
</div>
<?php require_once 'partials/footer.php'; ?>

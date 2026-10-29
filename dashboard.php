<?php
require_once 'config/database.php';
$page_title = 'Dashboard';
require_once 'partials/header.php';

$total = (int)$pdo->query("SELECT COUNT(*) FROM masukan")->fetchColumn();
$baru = (int)$pdo->query("SELECT COUNT(*) FROM masukan WHERE status='Baru'")->fetchColumn();
$proses = (int)$pdo->query("SELECT COUNT(*) FROM masukan WHERE status IN ('Diverifikasi','Diteruskan','Diproses')")->fetchColumn();
$selesai = (int)$pdo->query("SELECT COUNT(*) FROM masukan WHERE status='Selesai'")->fetchColumn();

$per_kategori = $pdo->query("SELECT k.nama_kategori, COUNT(m.id) jumlah FROM kategori k LEFT JOIN masukan m ON m.kategori_id=k.id GROUP BY k.id ORDER BY jumlah DESC")->fetchAll();
$terbaru = $pdo->query("SELECT m.*, k.nama_kategori FROM masukan m JOIN kategori k ON k.id=m.kategori_id ORDER BY m.created_at DESC LIMIT 5")->fetchAll();
?>
<div class="cards">
<div class="card"><div class="muted">Total Masukan</div><div class="num"><?= $total ?></div></div>
<div class="card"><div class="muted">Masukan Baru</div><div class="num"><?= $baru ?></div></div>
<div class="card"><div class="muted">Dalam Proses</div><div class="num"><?= $proses ?></div></div>
<div class="card"><div class="muted">Selesai</div><div class="num"><?= $selesai ?></div></div>
</div>

<div class="grid2">
<div class="table-wrap">
<h3>Jumlah Berdasarkan Kategori</h3>
<table class="table"><thead><tr><th>Kategori</th><th>Jumlah</th></tr></thead><tbody>
<?php foreach($per_kategori as $r): ?><tr><td><?= e($r['nama_kategori']) ?></td><td><?= e($r['jumlah']) ?></td></tr><?php endforeach; ?>
</tbody></table>
</div>
<div class="table-wrap">
<h3>Masukan Terbaru</h3>
<table class="table"><thead><tr><th>Judul</th><th>Kategori</th><th>Status</th></tr></thead><tbody>
<?php foreach($terbaru as $r): ?><tr><td><?= e($r['judul_masukan']) ?></td><td><?= e($r['nama_kategori']) ?></td><td><span class="badge"><?= e($r['status']) ?></span></td></tr><?php endforeach; ?>
<?php if (!$terbaru): ?><tr><td colspan="3">Belum ada data.</td></tr><?php endif; ?>
</tbody></table>
</div>
</div>
<?php require_once 'partials/footer.php'; ?>

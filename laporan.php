<?php
require_once 'config/database.php';
$page_title='Laporan Masukan';
require_once 'partials/header.php';

$mulai=$_GET['mulai']??'';$akhir=$_GET['akhir']??'';$status=$_GET['status']??'';$kategori=(int)($_GET['kategori']??0);
$where=[];$params=[];
if($mulai){$where[]='m.tanggal_masuk>=?';$params[]=$mulai;}
if($akhir){$where[]='m.tanggal_masuk<=?';$params[]=$akhir;}
if($status){$where[]='m.status=?';$params[]=$status;}
if($kategori){$where[]='m.kategori_id=?';$params[]=$kategori;}
$sql="SELECT m.*,k.nama_kategori FROM masukan m JOIN kategori k ON k.id=m.kategori_id";
if($where)$sql.=" WHERE ".implode(' AND ',$where);
$sql.=" ORDER BY m.tanggal_masuk DESC";
$stmt=$pdo->prepare($sql);$stmt->execute($params);$data=$stmt->fetchAll();
$cats=$pdo->query("SELECT * FROM kategori ORDER BY nama_kategori")->fetchAll();
?>
<div class="form-card no-print">
<form method="get"><div class="grid2">
<div class="field"><label>Tanggal Mulai</label><input type="date" name="mulai" value="<?= e($mulai) ?>"></div>
<div class="field"><label>Tanggal Akhir</label><input type="date" name="akhir" value="<?= e($akhir) ?>"></div>
<div class="field"><label>Kategori</label><select name="kategori"><option value="0">Semua</option><?php foreach($cats as $c): ?><option value="<?= $c['id'] ?>" <?= $kategori==$c['id']?'selected':'' ?>><?= e($c['nama_kategori']) ?></option><?php endforeach; ?></select></div>
<div class="field"><label>Status</label><select name="status"><option value="">Semua</option><?php foreach(['Baru','Diverifikasi','Diteruskan','Diproses','Selesai'] as $s): ?><option <?= $status===$s?'selected':'' ?>><?= $s ?></option><?php endforeach; ?></select></div>
</div><button class="btn btn-primary">Tampilkan</button> <button type="button" class="btn btn-secondary" onclick="window.print()">Cetak</button></form>
</div><br>
<div class="report-head print-only"><h2>LAPORAN DATA MASUKAN MASYARAKAT</h2><p>Sekretariat DPRD Kota Medan</p></div>
<div class="table-wrap"><table class="table"><thead><tr><th>No</th><th>Tanggal</th><th>Pengirim</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Lokasi</th></tr></thead><tbody>
<?php foreach($data as $i=>$r): ?><tr><td><?= $i+1 ?></td><td><?= e($r['tanggal_masuk']) ?></td><td><?= e($r['nama_pengirim']) ?></td><td><?= e($r['judul_masukan']) ?></td><td><?= e($r['nama_kategori']) ?></td><td><?= e($r['status']) ?></td><td><?= e($r['lokasi_masalah']) ?></td></tr><?php endforeach; ?>
<?php if(!$data): ?><tr><td colspan="7">Tidak ada data sesuai filter.</td></tr><?php endif; ?>
</tbody></table></div>
<?php require_once 'partials/footer.php'; ?>

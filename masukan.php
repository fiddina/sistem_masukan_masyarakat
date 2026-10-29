<?php
require_once 'config/database.php';
$page_title = 'Data Masukan Masyarakat';
require_once 'partials/header.php';

$q = trim($_GET['q'] ?? '');
$kategori = (int)($_GET['kategori'] ?? 0);
$status = $_GET['status'] ?? '';
$where=[]; $params=[];
if($q!==''){ $where[]="(m.nama_pengirim LIKE ? OR m.judul_masukan LIKE ? OR m.isi_masukan LIKE ? OR m.lokasi_masalah LIKE ?)"; $x="%$q%"; array_push($params,$x,$x,$x,$x); }
if($kategori){$where[]="m.kategori_id=?";$params[]=$kategori;}
if($status!==''){$where[]="m.status=?";$params[]=$status;}
$sql="SELECT m.*,k.nama_kategori FROM masukan m JOIN kategori k ON k.id=m.kategori_id";
if($where)$sql.=" WHERE ".implode(" AND ",$where);
$sql.=" ORDER BY m.tanggal_masuk DESC,m.id DESC";
$stmt=$pdo->prepare($sql);$stmt->execute($params);$data=$stmt->fetchAll();
$cats=$pdo->query("SELECT * FROM kategori ORDER BY nama_kategori")->fetchAll();
?>
<div class="searchbar">
<form method="get" style="display:flex;gap:8px;flex-wrap:wrap;width:100%">
<input name="q" placeholder="Cari nama, judul, isi, lokasi..." value="<?= e($q) ?>">
<select name="kategori"><option value="0">Semua kategori</option><?php foreach($cats as $c): ?><option value="<?= $c['id'] ?>" <?= $kategori==$c['id']?'selected':'' ?>><?= e($c['nama_kategori']) ?></option><?php endforeach; ?></select>
<select name="status"><option value="">Semua status</option><?php foreach(['Baru','Diverifikasi','Diteruskan','Diproses','Selesai'] as $s): ?><option <?= $status===$s?'selected':'' ?>><?= $s ?></option><?php endforeach; ?></select>
<button class="btn btn-secondary">Filter</button><a class="btn btn-primary" href="masukan_form.php">+ Tambah Masukan</a>
</form>
</div>
<div class="table-wrap"><table class="table"><thead><tr><th>No</th><th>Tanggal</th><th>Pengirim</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($data as $i=>$r): ?><tr>
<td><?= $i+1 ?></td><td><?= e($r['tanggal_masuk']) ?></td><td><?= e($r['nama_pengirim']) ?></td><td><?= e($r['judul_masukan']) ?></td><td><?= e($r['nama_kategori']) ?></td><td><span class="badge"><?= e($r['status']) ?></span></td>
<td class="actions"><a class="btn btn-secondary" href="masukan_detail.php?id=<?= $r['id'] ?>">Detail</a><a class="btn btn-warning" href="masukan_form.php?id=<?= $r['id'] ?>">Edit</a><a class="btn btn-danger" href="masukan_hapus.php?id=<?= $r['id'] ?>" onclick="return confirm('Hapus data ini?')">Hapus</a></td>
</tr><?php endforeach; ?>
<?php if(!$data): ?><tr><td colspan="7">Data tidak ditemukan.</td></tr><?php endif; ?>
</tbody></table></div>
<?php require_once 'partials/footer.php'; ?>

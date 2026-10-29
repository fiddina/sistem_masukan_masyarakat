<?php
require_once 'config/database.php';
require_once 'config/functions.php';
require_once 'config/auth.php';
require_login();
$id=(int)($_GET['id']??0);
$data=null;
if($id){$stmt=$pdo->prepare("SELECT * FROM masukan WHERE id=?");$stmt->execute([$id]);$data=$stmt->fetch();if(!$data) redirect('masukan.php');}
$cats=$pdo->query("SELECT * FROM kategori ORDER BY nama_kategori")->fetchAll();
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $fields=[
      trim($_POST['nama_pengirim']??''), trim($_POST['no_hp']??''), trim($_POST['alamat']??''),
      $_POST['tanggal_masuk']??date('Y-m-d'), (int)($_POST['kategori_id']??0),
      trim($_POST['judul_masukan']??''), trim($_POST['isi_masukan']??''), trim($_POST['lokasi_masalah']??''),
      $_POST['status']??'Baru', trim($_POST['pihak_terkait']??''), ($_POST['tanggal_diteruskan']??'') ?: null,
      trim($_POST['keterangan_tindak_lanjut']??'')
    ];
    if($fields[0]===''||$fields[4]===0||$fields[5]===''||$fields[6]===''){$error='Nama pengirim, kategori, judul, dan isi masukan wajib diisi.';}
    else {
        $lampiran = upload_lampiran($_FILES['lampiran']??[]);
        if($id){
            if($lampiran){
                $sql="UPDATE masukan SET nama_pengirim=?,no_hp=?,alamat=?,tanggal_masuk=?,kategori_id=?,judul_masukan=?,isi_masukan=?,lokasi_masalah=?,status=?,pihak_terkait=?,tanggal_diteruskan=?,keterangan_tindak_lanjut=?,lampiran=? WHERE id=?";
                $fields[]=$lampiran;
            } else {
                $sql="UPDATE masukan SET nama_pengirim=?,no_hp=?,alamat=?,tanggal_masuk=?,kategori_id=?,judul_masukan=?,isi_masukan=?,lokasi_masalah=?,status=?,pihak_terkait=?,tanggal_diteruskan=?,keterangan_tindak_lanjut=? WHERE id=?";
            }
            $fields[]=$id;
            $stmt=$pdo->prepare($sql);$stmt->execute($fields);flash('success','Data masukan berhasil diperbarui.');
        } else {
            $sql="INSERT INTO masukan(nama_pengirim,no_hp,alamat,tanggal_masuk,kategori_id,judul_masukan,isi_masukan,lokasi_masalah,status,pihak_terkait,tanggal_diteruskan,keterangan_tindak_lanjut,lampiran) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $fields[]=$lampiran;$stmt=$pdo->prepare($sql);$stmt->execute($fields);flash('success','Data masukan berhasil ditambahkan.');
        }
        redirect('masukan.php');
    }
}
$page_title=$id?'Edit Masukan':'Tambah Masukan';
require_once 'partials/header.php';
if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<div class="form-card">
<form method="post" enctype="multipart/form-data">
<div class="grid2">
<div class="field"><label>Nama Pengirim *</label><input name="nama_pengirim" required value="<?= e($_POST['nama_pengirim']??$data['nama_pengirim']??'') ?>"></div>
<div class="field"><label>No. HP</label><input name="no_hp" value="<?= e($_POST['no_hp']??$data['no_hp']??'') ?>"></div>
</div>
<div class="field"><label>Alamat</label><textarea name="alamat"><?= e($_POST['alamat']??$data['alamat']??'') ?></textarea></div>
<div class="grid2">
<div class="field"><label>Tanggal Masuk *</label><input type="date" name="tanggal_masuk" required value="<?= e($_POST['tanggal_masuk']??$data['tanggal_masuk']??date('Y-m-d')) ?>"></div>
<div class="field"><label>Kategori *</label><select name="kategori_id" required><option value="">-- Pilih --</option><?php foreach($cats as $c): ?><option value="<?= $c['id'] ?>" <?= ((int)($_POST['kategori_id']??$data['kategori_id']??0)==$c['id'])?'selected':'' ?>><?= e($c['nama_kategori']) ?></option><?php endforeach; ?></select></div>
</div>
<div class="field"><label>Judul Masukan *</label><input name="judul_masukan" required value="<?= e($_POST['judul_masukan']??$data['judul_masukan']??'') ?>"></div>
<div class="field"><label>Isi Masukan *</label><textarea name="isi_masukan" required><?= e($_POST['isi_masukan']??$data['isi_masukan']??'') ?></textarea></div>
<div class="field"><label>Lokasi Permasalahan</label><input name="lokasi_masalah" value="<?= e($_POST['lokasi_masalah']??$data['lokasi_masalah']??'') ?>"></div>
<hr>
<h3>Tindak Lanjut</h3>
<div class="grid2">
<div class="field"><label>Status</label><select name="status"><?php foreach(['Baru','Diverifikasi','Diteruskan','Diproses','Selesai'] as $s): ?><option <?= (($_POST['status']??$data['status']??'Baru')===$s)?'selected':'' ?>><?= $s ?></option><?php endforeach; ?></select></div>
<div class="field"><label>Pihak Terkait</label><input name="pihak_terkait" value="<?= e($_POST['pihak_terkait']??$data['pihak_terkait']??'') ?>"></div>
</div>
<div class="grid2">
<div class="field"><label>Tanggal Diteruskan</label><input type="date" name="tanggal_diteruskan" value="<?= e($_POST['tanggal_diteruskan']??$data['tanggal_diteruskan']??'') ?>"></div>
<div class="field"><label>Lampiran (maks. 5 MB)</label><input type="file" name="lampiran" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></div>
</div>
<div class="field"><label>Keterangan Tindak Lanjut</label><textarea name="keterangan_tindak_lanjut"><?= e($_POST['keterangan_tindak_lanjut']??$data['keterangan_tindak_lanjut']??'') ?></textarea></div>
<button class="btn btn-primary">Simpan</button> <a class="btn btn-secondary" href="masukan.php">Batal</a>
</form></div>
<?php require_once 'partials/footer.php'; ?>

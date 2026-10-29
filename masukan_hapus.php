<?php
require_once 'config/database.php';
require_once 'config/auth.php';
require_login();
$id=(int)($_GET['id']??0);
$stmt=$pdo->prepare("SELECT lampiran FROM masukan WHERE id=?");$stmt->execute([$id]);$r=$stmt->fetch();
if($r){
    $del=$pdo->prepare("DELETE FROM masukan WHERE id=?");$del->execute([$id]);
    if($r['lampiran'] && file_exists(__DIR__.'/uploads/'.$r['lampiran'])) @unlink(__DIR__.'/uploads/'.$r['lampiran']);
    flash('success','Data masukan berhasil dihapus.');
}
header('Location: masukan.php');exit;
?>
<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/functions.php';
require_login();
$page_title = $page_title ?? 'Sistem Informasi';
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page_title) ?> - Pengelolaan Masukan Masyarakat</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="layout">
<aside class="sidebar">
    <div class="brand">Sistem Informasi</div>
    <div class="brand-sub">Pengelolaan Masukan Masyarakat</div>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="masukan.php">Data Masukan</a>
        <a href="kategori.php">Kategori</a>
        <a href="laporan.php">Laporan</a>
        <a href="logout.php" class="logout">Logout</a>
    </nav>
</aside>
<main class="main">
<header class="topbar">
    <div>
        <strong><?= e($page_title) ?></strong>
    </div>
    <div> <strong><?= e($_SESSION['nama'] ?? 'Administrator') ?></strong></div>
</header>
<div class="content">
<?php if ($msg = flash('success')): ?><div class="alert success"><?= e($msg) ?></div><?php endif; ?>
<?php if ($msg = flash('error')): ?><div class="alert error"><?= e($msg) ?></div><?php endif; ?>

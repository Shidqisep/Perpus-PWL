<?php
    // ambil URL path (misalnya "home" atau "about")
    $currentPage = strtolower(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman <?= $data['judul'] ?></title>
    <link rel="stylesheet" href= "<?= BASEURL ?>/css/styles.css">
    <link rel="icon" type="image/x-icon" href="<?= BASEURL ?>/asset/favicon.png">
</head>
<body>
    <navbar class="navbar">
            <a class="navbar-link <?= ($currentPage == 'home' || $currentPage == 'public') ? ' active' : '' ?>" href="<?= BASEURL ?>/home">Home</a>
            <a class="navbar-link <?= ($currentPage == 'buku') ? ' active' : '' ?>" href="<?= BASEURL ?>/buku">Daftar Buku</a>
            <a class="navbar-link <?= ($currentPage == 'peminjaman') ? ' active' : '' ?>" href="<?= BASEURL ?>/peminjaman">Daftar Peminjaman</a>
             <a class="navbar-link <?= ($currentPage == 'anggota') ? ' active' : '' ?>" href="<?= BASEURL ?>/anggota">Daftar Anggota</a>
    </navbar>
    
<?php Flasher::flash(); ?>

<a class="add-btn" id="addBuku" href="<?= BASEURL ?>/buku/tambah">Tambah buku</a>

<form action="<?= BASEURL ?>/buku/index" method="get" class="form-cari">
    <input class="searchBar" type="text" name="keyword" id="keyword" autocomplete="off" value="<?= htmlspecialchars($data['keyword'] ?? '') ?>">
    <button class="navbar-link" type="submit">Cari</button>
</form>

<div class="pagination">
    <?php $kw = isset($data['keyword']) && $data['keyword'] !== '' ? '?keyword=' . urlencode($data['keyword']) : ''; ?>
    <?php if ($data['jumlah_halaman'] > 1): ?>
        <a class="page-number <?= ($data['halaman_sekarang'] == 1) ? 'disabled' : '' ?>" href="<?= BASEURL ?>/buku/index/<?= $data['halaman_sekarang'] - 1 ?><?= $kw ?>"><</a>
        <?php endif; ?>
    <?php for ($i = 1; $i <= $data['jumlah_halaman']; $i++) : ?>
        <a class="page-number <?= $data['halaman_sekarang'] == $i ? 'active' : '' ?>" href="<?= BASEURL ?>/buku/index/<?= $i ?><?= $kw ?>"><?= $i ?></a>
    <?php endfor; ?>
    <a class="page-number <?= ($data['halaman_sekarang'] == $data['jumlah_halaman']) ? 'disabled' : '' ?>" href="<?= BASEURL ?>/buku/index/<?= $data['halaman_sekarang'] + 1 ?><?= $kw ?>">></a>
</div>

<div class="table-buku">
    <table>
    <tr>
        <th>No</th>
        <th>Cover</th>
        <th>judul</th>
        <th>penulis</th>
        <th>penerbit</th>
        <th>tahun terbit</th>
        <th>kategori</th>
        <th>jumlah_stok</th>
        <th>Aksi</th>
    </tr>

    <?php $i = 1?>
    <?php foreach ($data['buku'] as $buku) 
        : ?>
    <tr>
        <td><?= $i?></td>
        <td><img src="<?= BASEURL ?>/asset/<?= $buku["cover"] ?>" alt="<?= $buku["judul"] ?>" width="50"></td>
        <td><?= $buku["judul"]?></td>
        <td><?= $buku["penulis"]?></td>
        <td><?= $buku["penerbit"]?></td>
        <td><?= $buku["tahun_terbit"]?></td>
        <td><?= $buku["kategori"]?></td>
        <td><?= $buku["jumlah_stok"]?></td>
        <td >
        <div class="action-btn">
        <a class="navbar-link" href="<?= BASEURL ?>/buku/pinjam/<?= $buku['id_buku'] ?>">Pinjam</a>
        <a class="ubah-btn" href="<?= BASEURL ?>/buku/ubah/<?= $buku['id_buku'] ?>">Ubah </a>
        <a class="delete-btn" href="<?= BASEURL ?>/buku/hapus/<?= $buku['id_buku'] ?>" onclick="return confirm('Apakah anda yakin?')">Hapus</a>
        </div>
        </td>
    </tr>
    <?php $i++?>
    <?php endforeach ?>
    </table>
</div>


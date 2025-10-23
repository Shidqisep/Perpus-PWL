
<?php Flasher::flash(); ?>

<a class="add-btn" id="addBuku" href="<?= BASEURL ?>/peminjaman/tambah">Tambah Data Peminjaman</a>

<form action="<?= BASEURL ?>/peminjaman/index" method="get" class="form-cari">
    <input class="searchBar" type="text" name="keyword" id="keyword" autocomplete="off" value="<?= htmlspecialchars($data['keyword'] ?? '') ?>">
    <button class="navbar-link" type="submit">Cari</button>
</form>

<div class="pagination">
    <?php $kw = isset($data['keyword']) && $data['keyword'] !== '' ? '?keyword=' . urlencode($data['keyword']) : ''; ?>
    <?php if ($data['jumlah_halaman'] > 1): ?>
        <a class="page-number <?= ($data['halaman_sekarang'] == 1) ? 'disabled' : '' ?>" href="<?= BASEURL ?>/peminjaman/index/<?= $data['halaman_sekarang'] - 1 ?><?= $kw ?>"><</a>
        <?php endif; ?>
    <?php for ($i = 1; $i <= $data['jumlah_halaman']; $i++) : ?>
        <a class="page-number <?= $data['halaman_sekarang'] == $i ? 'active' : '' ?>" href="<?= BASEURL ?>/peminjaman/index/<?= $i ?><?= $kw ?>"><?= $i ?></a>
    <?php endfor; ?>
    <a class="page-number <?= ($data['halaman_sekarang'] == $data['jumlah_halaman']) ? 'disabled' : '' ?>" href="<?= BASEURL ?>/peminjaman/index/<?= $data['halaman_sekarang'] + 1 ?><?= $kw ?>">></a>  
</div>

<div class="table-buku">
    <table>
    <tr>
        <th>No</th>
        <th>Nama Buku</th>
        <th>Nama Peminjam</th>
        <th>tanggal_pinjam</th>
        <th>tanggal_kembali</th>
        <th>status</th>
        <th>denda</th>
        <th>Aksi</th>
    </tr>

    <?php $i = 1?>
    <?php foreach ($data['peminjaman'] as $peminjaman) 
        : ?>
    <tr>
        <td><?= $i?></td>
        <td><?= $peminjaman["judul_buku"]?></td>
        <td><?= $peminjaman["nama_peminjam"]?></td> 
        <td><?= $peminjaman["tanggal_pinjam"]?></td>
        <td><?= $peminjaman["tanggal_kembali"]?></td>
        <td><?= $peminjaman["status"]?></td>
        <td><?= $data['denda'][$peminjaman['id_peminjaman']] ?></td>
        <td>
        <div class="action-btn">
        <a class="ubah-btn" href="<?= BASEURL ?>/peminjaman/ubahStatus/<?= $peminjaman['id_peminjaman']?>/<?= $peminjaman['id_buku']?>">Ubah Status </a>
        <a class="delete-btn" href="<?= BASEURL ?>/peminjaman/hapus/<?= $peminjaman['id_peminjaman'] ?>" onclick="return confirm('Apakah anda yakin?')">Hapus</a>
        </div>
        </td>
    </tr>
    <?php $i++?>
    <?php endforeach ?>
    </table>
</div>


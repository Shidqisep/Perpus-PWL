
<?php Flasher::flash(); ?>

<a class="add-btn" id="addBuku" href="<?= BASEURL ?>/anggota/tambah">Tambah Anggota</a>

<form action="<?= BASEURL ?>/anggota/index" method="get" class="form-cari">
    <input class="searchBar" type="text" name="keyword" id="keyword" autocomplete="off" value="<?= htmlspecialchars($data['keyword'] ?? '') ?>">
    <button class="navbar-link" type="submit">Cari</button>
</form>

<div class="pagination">
    <?php $kw = isset($data['keyword']) && $data['keyword'] !== '' ? '?keyword=' . urlencode($data['keyword']) : ''; ?>
    <?php if ($data['jumlah_halaman'] > 1): ?>
        <a class="page-number <?= ($data['halaman_sekarang'] == 1) ? 'disabled' : '' ?>" href="<?= BASEURL ?>/anggota/index/<?= $data['halaman_sekarang'] - 1 ?><?= $kw ?>"><</a>
        <?php endif; ?>
    <?php for ($i = 1; $i <= $data['jumlah_halaman']; $i++) : ?>
        <a class="page-number <?= $data['halaman_sekarang'] == $i ? 'active' : '' ?>" href="<?= BASEURL ?>/anggota/index/<?= $i ?><?= $kw ?>"><?= $i ?></a>
    <?php endfor; ?>
    <a class="page-number <?= ($data['halaman_sekarang'] == $data['jumlah_halaman']) ? 'disabled' : '' ?>" href="<?= BASEURL ?>/anggota/index/<?= $data['halaman_sekarang'] + 1 ?><?= $kw ?>">></a>
</div>



<div class="table-buku">
    <table>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>No. HP</th>
        <th>Aksi</th>
    </tr>

    <?php $i = 1?>
    <?php foreach ($data['anggota'] as $anggota) 
        : ?>
    <tr>
        <td><?= $i?></td>
        <td><?= $anggota["nama"]?></td>
        <td><?= $anggota["alamat"]?></td>
        <td><?= $anggota["no_hp"]?></td>
        <td >
        <div class="action-btn">
        <a class="ubah-btn" href="<?= BASEURL ?>/anggota/ubah/<?= $anggota['id_anggota'] ?>">Ubah </a>
        <a class="delete-btn" href="<?= BASEURL ?>/anggota/hapus/<?= $anggota['id_anggota'] ?>" onclick="return confirm('Apakah anda yakin?')">Hapus</a>
        </div>
        </td>
    </tr>
    <?php $i++?>
    <?php endforeach ?>
    </table>
</div>


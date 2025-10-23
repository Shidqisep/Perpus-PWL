<?php

class Peminjaman extends Controller {

    // public function index(){
    //     $data['judul'] = "Peminjaman";
    //     $data['peminjaman'] = $this->model('PeminjamanModel')->getJoinedPeminjaman();
    //     // Hitung denda untuk setiap peminjaman
    //     foreach ($data['peminjaman'] as $peminjaman) {
    //         $data['denda'][$peminjaman['id_peminjaman']] = $this->model('PeminjamanModel')->countDenda($peminjaman['id_peminjaman']);
    //     }
    //     $this->view('templates/header', $data);
    //     $this->view('peminjaman/index', $data);
    //     $this->view('templates/footer', $data);
    // }

    public function index($halaman = 1){
    $data['judul'] = "Peminjaman";
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $limit = 5;
    $offset = ($halaman - 1) * $limit;

    $total = $this->model('PeminjamanModel')->countAllPeminjamanWithKeyword($keyword);
    $data['halaman_sekarang'] = $halaman;
    $data['jumlah_halaman'] = ($total > 0) ? ceil($total / $limit) : 1;
    $data['peminjaman'] = $this->model('PeminjamanModel')->getPeminjamanByPage($limit, $offset, $keyword);
    $data['keyword'] = $keyword;
    foreach ($data['peminjaman'] as $peminjaman) {
            $data['denda'][$peminjaman['id_peminjaman']] = $this->model('PeminjamanModel')->countDenda($peminjaman['id_peminjaman']);
        }

    $this->view('templates/header', $data);
    $this->view('peminjaman/index', $data);
    $this->view('templates/footer');
    }

    public function tambah(){
        $data['judul'] = "Tambah Data Peminjaman";
        $data['buku'] = $this->model('BukuModel')->getAllIdAndJudul();
        $data['anggota'] = $this->model('AnggotaModel')->getAllIdAndNama();
        $this->view('templates/header', $data);
        $this->view('peminjaman/tambah', $data);
        $this->view('templates/footer');
    }

    public function ubahStatus($id_peminjaman, $id_buku){
        if($this->model('PeminjamanModel')->updateStatus($id_peminjaman) > 0 ) {
            Flasher::setFlash('Berhasil', 'diubah', 'success');
            header('Location: ' . BASEURL . '/peminjaman');
            $this->model('BukuModel')->increaseStock($id_buku);
            exit;
        } else {
            Flasher::setFlash('Gagal', 'Status sudah dikembalikan', 'error');
            header('Location: ' . BASEURL . '/peminjaman');
            exit;
        }
    }

    public function tambahPeminjaman(){
        if ($this->model('BukuModel')->getStockBuku($_POST['id_buku']) <= 0) {
            Flasher::setFlash('Gagal', 'Buku tidak tersedia', 'error');
            header('Location: ' . BASEURL . '/peminjaman');
            exit;
        }


        if($this->model('PeminjamanModel')->tambahDataPeminjaman($_POST) > 0 ) {
            Flasher::setFlash('Berhasil', 'ditambahkan', 'success');
            $this->model('BukuModel')->decreaseStock($_POST['id_buku']);
            header('Location: ' . BASEURL . '/peminjaman');
            exit;
        } else {
            Flasher::setFlash('Gagal', 'ditambahkan', 'error');
            header('Location: ' . BASEURL . '/peminjaman');
            exit;
        }
    }

    public function hapus($id_peminjaman){
        if( $this->model('PeminjamanModel')->deletePeminjaman($id_peminjaman) > 0 ) {
            Flasher::setFlash('Berhasil', 'dihapus', 'success');
            header('Location: ' . BASEURL . '/peminjaman');
            exit;
        } else {
            Flasher::setFlash('Gagal', 'dihapus', 'error');
            header('Location: ' . BASEURL . '/peminjaman');
            exit;
        }
    }
}
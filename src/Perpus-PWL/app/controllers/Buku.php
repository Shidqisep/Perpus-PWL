<?php

class Buku extends Controller{

    // public function index($halaman = 1){
    //     $totalBuku = $this->model('BukuModel')->countAllBuku();
    //     $data['judul'] = "Buku";
    //     $data['buku'] = $this->model('BukuModel')->getBukuByPage(5, ($halaman - 1) * 5);
    //     $data['jumlah_halaman'] = ceil($totalBuku / 5);
    //     $this->view('templates/header', $data);
    //     $this->view('buku/index', $data);
    //     $this->view('templates/footer');
    // }

    public function index($halaman = 1){
    $data['judul'] = "Buku";
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $limit = 5;
    $offset = ($halaman - 1) * $limit;

    $total = $this->model('BukuModel')->countAllBukuWithKeyword($keyword);
    $data['halaman_sekarang'] = $halaman;
    $data['jumlah_halaman'] = ($total > 0) ? ceil($total / $limit) : 1;
    $data['buku'] = $this->model('BukuModel')->getBukuByPage($limit, $offset, $keyword);
    $data['keyword'] = $keyword;

    $this->view('templates/header', $data);
    $this->view('buku/index', $data);
    $this->view('templates/footer');
    }

    public function tambah(){
        $data['judul'] = "Tambah Data Buku";
        $this->view('templates/header', $data);
        $this->view('buku/tambah', $data);
        $this->view('templates/footer');
    }

    public function tambahDataBuku(){

        $cover = $this->model('BukuModel')->uploadCover();
        if (!$cover) {
            Flasher::setFlash('Gagal', 'diupload', 'danger');
            header('Location: ' . BASEURL . '/buku/tambah');
            exit;
        }

        $_POST['cover'] = $cover;
        if($this->model('BukuModel')->addBuku($_POST) > 0 ) {
            Flasher::setFlash('Berhasil', 'ditambahkan', 'success');
            header('Location: ' . BASEURL . '/buku');
            exit;
        } else {
            Flasher::setFlash('Gagal', 'ditambahkan', 'error');
            header('Location: ' . BASEURL . '/buku');
            exit;
        }
    }

    public function hapus($id_buku){
        if($this->model('PeminjamanModel')->countBukuInPeminjaman($id_buku) > 0 ) {
            Flasher::setFlash('Gagal', 'buku sedang dipinjam', 'error');
            header('Location: ' . BASEURL . '/buku');
            exit;
        }

        if( $this->model('BukuModel')->deleteBuku($id_buku) > 0 ) {
            Flasher::setFlash('Berhasil', 'dihapus', 'success');
            header('Location: ' . BASEURL . '/buku');
            exit;
        } else {
            Flasher::setFlash('Gagal', 'dihapus', 'danger');
            header('Location: ' . BASEURL . '/buku');
            exit;
        }
    }

    public function ubah($id_buku){
        $data['judul'] = "Ubah Data Buku";
        $data['buku'] = $this->model('BukuModel')->getBukuById($id_buku);
        $this->view('templates/header', $data);
        $this->view('buku/ubah', $data);
        $this->view('templates/footer');
    }

    public function ubahBuku(){
        $cover = $this->model('BukuModel')->uploadCover();
        if (!$cover){
            $_POST['cover'] = $_POST['coverLama'];
        }else{
            $_POST['cover'] = $cover;
        }

        if( $this->model('BukuModel')->updateBuku($_POST) > 0 ){
            Flasher::setFlash('Berhasil', 'diubah', 'success');
            header('Location: ' . BASEURL . '/buku');
        } else {
            Flasher::setFlash('Gagal', 'diubah', 'danger');
            header('Location: ' . BASEURL . '/buku');
        }
    }

    public function pinjam($id_buku){
        $data['judul'] = "Pinjam Buku";
        $data['buku'] = $this->model('BukuModel')->getBukuById($id_buku);
        $data['anggota'] = $this->model('AnggotaModel')->getAllIdAndNama();
        if ($data['buku']['jumlah_stok'] <= 0) {
            Flasher::setFlash('Gagal', 'Buku tidak tersedia', 'error');
            header('Location: ' . BASEURL . '/buku');
            exit;
        }
        $this->view('templates/header', $data);
        $this->view('buku/pinjam', $data);
        $this->view('templates/footer');
    }

    // public function pagination($page = 1){
    //     $data['judul'] = "Buku";
    //     $limit = 5;
    //     $offset = ($page - 1) * $limit;
    //     $data['buku'] = $this->model('BukuModel')->getBukuByPage($limit, $offset);
    //     $totalBuku = $this->model('BukuModel')->countAllBuku();
    //     $data['jumlah_halaman'] = ceil($totalBuku / $limit);
    //     $this->view('templates/header', $data);
    //     $this->view('buku/index', $data);
    //     $this->view('templates/footer');
    // }

    public function cari(){
        if( isset($_POST['keyword']) ) {
            $data['judul'] = "Buku";
            $data['buku'] = $this->model('BukuModel')->cariBuku($_POST['keyword']);
            $this->view('templates/header', $data);
            $this->view('buku/index', $data);
            $this->view('templates/footer');
        } else {
            $data['judul'] = "Buku";
        $data['buku'] = $this->model('BukuModel')->getAllBuku();
        $this->view('templates/header', $data);
        $this->view('buku/index', $data);
        $this->view('templates/footer');
        }
    }


}
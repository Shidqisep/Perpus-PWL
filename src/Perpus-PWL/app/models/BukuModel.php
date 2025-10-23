<?php

class BukuModel{

    private $table = 'buku'; //properti untuk nama table mana
    private $db; //variabel properti untuk db

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllbuku(){
        $this->db->query("SELECT * FROM $this->table WHERE status_buku = 'ada'");
        return $this->db->resultSet();
    }

    public function uploadCover(){
    $namaFile = $_FILES['cover']['name'];
    $error = $_FILES['cover']['error'];
    $tmpName = $_FILES['cover']['tmp_name'];

    // Cek apakah tidak ada file yang diupload
    if ($error === 4) {
        return false; // atau bisa return false jika ingin wajib upload
    }

    // Cek ekstensi file
    $ekstensiValid = ['jpg', 'jpeg', 'png', 'webp'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    if (!in_array($ekstensiGambar, $ekstensiValid)){
        echo "<script>alert('File yang anda upload bukan gambar!!!')
        document.location.href='index.php';
        </script>;";
        return false;
    }
    // Generate nama file baru agar unik
    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;

    // Pindahkan file ke folder tujuan
    if (move_uploaded_file($tmpName, '../public/asset/' . $namaFileBaru)) {
        return $namaFileBaru;
    } else {
        return false;
    }   
    }

    public function addBuku($data){
         $query = "INSERT INTO $this->table (judul, penulis, penerbit, tahun_terbit, kategori, cover, jumlah_stok) VALUES
        (:judul, :penulis, :penerbit, :tahun_terbit, :kategori, :cover, :jumlah_stok)";
        $this->db->query($query);
        $this->db->bind('judul', $data['judul']);
        $this->db->bind('penulis', $data['penulis']);
        $this->db->bind('penerbit', $data['penerbit']);
        $this->db->bind('tahun_terbit', $data['tahun_terbit']);
        $this->db->bind('kategori', $data['kategori']);
        $this->db->bind('cover', $data['cover']);
        $this->db->bind('jumlah_stok', $data['jumlah_stok']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    public function getBukuById($id){
        $this->db->query("SELECT * FROM $this->table WHERE id_buku = :id_buku");
        $this->db->bind('id_buku', $id);
        return $this->db->singleSet();
    }

    public function getIdByJudul($judul){
        $this->db->query("SELECT id_buku FROM $this->table WHERE judul = :judul");
        $this->db->bind('judul', $judul);
        return $this->db->singleSet();
    }

    public function getAllIdAndJudul(){
        $this->db->query("SELECT id_buku, judul FROM $this->table WHERE status_buku = 'ada'");
        return $this->db->resultSet();
    }

    public function decreaseStock($id_buku){
        $query = "UPDATE $this->table SET 
        jumlah_stok = jumlah_stok - 1
        WHERE id_buku = :id_buku AND jumlah_stok > 0";

        $this->db->query($query);
        $this->db->bind('id_buku', $id_buku);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function increaseStock($id_buku){
        $query = "UPDATE $this->table 
        SET jumlah_stok = jumlah_stok + 1 
        WHERE id_buku = :id_buku";
        $this->db->query($query);
        $this->db->bind('id_buku', $id_buku);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateBuku($data){
        $query = "UPDATE $this->table SET 
        judul = :judul,
        penulis = :penulis,
        penerbit = :penerbit,
        tahun_terbit = :tahun_terbit,
        kategori = :kategori,
        cover = :cover,
        jumlah_stok = :jumlah_stok
        WHERE id_buku = :id_buku";

        $this->db->query($query);
        $this->db->bind('judul', $data['judul']);
        $this->db->bind('penulis', $data['penulis']);
        $this->db->bind('penerbit', $data['penerbit']);
        $this->db->bind('tahun_terbit', $data['tahun_terbit']);
        $this->db->bind('kategori', $data['kategori']);
        $this->db->bind('cover', $data['cover']);
        $this->db->bind('jumlah_stok', $data['jumlah_stok']);
        $this->db->bind('id_buku', $data['id_buku']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteBuku($id){
        $query = "UPDATE $this->table SET status_buku = 'tidak ada' WHERE id_buku = :id_buku";
        $this->db->query($query);
        $this->db->bind('id_buku', $id);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function countAllBuku(){
        $this->db->query("SELECT COUNT(*) as total FROM $this->table WHERE status_buku = 'ada'");
        $result = $this->db->singleSet();
        return $result['total'];
    }

    public function cariBuku(){
        $keyword = $_POST['keyword'];
        $query = "SELECT * FROM $this->table WHERE 
        judul LIKE :keyword OR
        penulis LIKE :keyword OR
        penerbit LIKE :keyword OR
        tahun_terbit LIKE :keyword OR
        kategori LIKE :keyword OR
        jumlah_stok LIKE :keyword";
        $this->db->query($query);
        $this->db->bind('keyword', "%$keyword%");
        return $this->db->resultSet();
    }

    public function getStockBuku($id_buku){
        $this->db->query("SELECT jumlah_stok FROM $this->table WHERE id_buku = :id_buku");
        $this->db->bind('id_buku', $id_buku);
        return $this->db->singleSet()['jumlah_stok'];
    }

    public function getBukuByPage($limit, $offset, $keyword = null){
        $limit = (int)$limit;
        $offset = (int)$offset;
        if ($keyword) {
            $query = "SELECT * FROM $this->table WHERE status_buku = 'ada' AND 
            (judul LIKE :keyword OR
            penulis LIKE :keyword OR
            penerbit LIKE :keyword OR
            tahun_terbit LIKE :keyword OR
            kategori LIKE :keyword OR
            jumlah_stok LIKE :keyword)
            LIMIT :limit OFFSET :offset";
            $this->db->query($query);
            $this->db->bind('keyword', "%$keyword%");
        } else {
            $query = "SELECT * FROM $this->table WHERE status_buku = 'ada' LIMIT :limit OFFSET :offset";
            $this->db->query($query);
        }
        $this->db->bind('limit', $limit);
        $this->db->bind('offset', $offset);
        return $this->db->resultSet();
    }

    public function countAllBukuWithKeyword($keyword){
        $this->db->query("SELECT COUNT(*) as total FROM $this->table WHERE status_buku = 'ada' AND 
        (judul LIKE :keyword OR
        penulis LIKE :keyword OR
        penerbit LIKE :keyword OR
        tahun_terbit LIKE :keyword OR
        kategori LIKE :keyword OR
        jumlah_stok LIKE :keyword)");
        $this->db->bind('keyword', "%$keyword%");
        $result = $this->db->singleSet();
        return $result['total'];
    }

}

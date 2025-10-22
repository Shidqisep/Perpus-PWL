<?php


class PeminjamanModel{
    private $table = 'peminjaman';
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function getJoinedPeminjaman(){
        $this->db->query("SELECT
        peminjaman.id_peminjaman,
        peminjaman.id_buku, 
        anggota.nama AS nama_peminjam,
        buku.judul AS judul_buku,
        peminjaman.tanggal_pinjam,
        peminjaman.tanggal_kembali,
        peminjaman.status
        FROM peminjaman
        JOIN anggota ON peminjaman.id_anggota = anggota.id_anggota
        JOIN buku ON peminjaman.id_buku = buku.id_buku
        ORDER BY peminjaman.tanggal_pinjam ASC
            ");
        return $this->db->resultSet();
    }

    public function updateStatus($id_peminjaman){
        $query = "UPDATE $this->table SET 
        status = 'Dikembalikan', 
        tanggal_kembali = NOW() 
        WHERE id_peminjaman = :id_peminjaman";
        $this->db->query($query);
        $this->db->bind('id_peminjaman', $id_peminjaman);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function tambahDataPeminjaman($data){
        $query = "INSERT INTO $this->table 
        (id_anggota, id_buku, tanggal_pinjam, tanggal_kembali, status) 
        VALUES
        (:id_anggota, :id_buku, :tanggal_pinjam, :tanggal_kembali, 'Dipinjam')";
        $this->db->query($query);
        $this->db->bind('id_anggota', $data['id_anggota']);
        $this->db->bind('id_buku', $data['id_buku']);
        $this->db->bind('tanggal_pinjam', $data['tanggal_pinjam']);
        $this->db->bind('tanggal_kembali', $data['tanggal_kembali']);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getStatusPeminjaman($id_peminjaman){
        $this->db->query("SELECT status FROM $this->table WHERE id_peminjaman = :id_peminjaman");
        $this->db->bind('id_peminjaman', $id_peminjaman);
        return $this->db->singleSet();

    }

    public function deletePeminjaman($id_peminjaman){
        //jika belum dikembalikan, maka tidak bisa dihapus
        $status = $this->getStatusPeminjaman($id_peminjaman);
        if($status['status'] === 'Dipinjam'){
            return false;
        }
        $query = "DELETE FROM $this->table WHERE id_peminjaman = :id_peminjaman";
        $this->db->query($query);
        $this->db->bind('id_peminjaman', $id_peminjaman);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function countDenda($id_peminjaman){
        $this->db->query("SELECT 
        DATEDIFF(tanggal_kembali, tanggal_pinjam) AS terlambat
        FROM $this->table
        WHERE id_peminjaman = :id_peminjaman AND status = 'Dikembalikan'");
        $this->db->bind('id_peminjaman', $id_peminjaman);
        $result = $this->db->singleSet();
        if($result && ($result['terlambat'] > 7)){ //jika tanggal pemgembalian lebih dari 7 hari
            return ($result['terlambat'] - 7) * 1000; //denda 1000 per hari
        } else {
            return 0;
        }
    }

    public function countBukuInPeminjaman($id_buku){
        $this->db->query("SELECT COUNT(*) AS count FROM $this->table WHERE id_buku = :id_buku AND status = 'Dipinjam'");
        $this->db->bind('id_buku', $id_buku);
        $this->db->execute();
        $result = $this->db->singleSet();
        return $result['count'];
    }

}

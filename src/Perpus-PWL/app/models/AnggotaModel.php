<?php

class AnggotaModel{
    private $table = 'anggota'; //properti untuk nama table mana
    private $db; //variabel properti untuk db

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllAnggota(){
        $this->db->query("SELECT * FROM $this->table WHERE status_anggota = 'aktif'");
        return $this->db->resultSet();
    }

    public function getIdByNama($nama){
        $this->db->query("SELECT id_anggota FROM $this->table WHERE nama = :nama");
        $this->db->bind('nama', $nama);
        return $this->db->singleSet();
    }

    public function addAnggota($data){
         $query = "INSERT INTO $this->table (nama, alamat, no_hp) VALUES
        (:nama, :alamat, :no_hp)";
        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('alamat', $data['alamat']);
        $this->db->bind('no_hp', $data['no_hp']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getAnggotaById($id_anggota){
        $this->db->query("SELECT * FROM $this->table WHERE id_anggota = :id_anggota");
        $this->db->bind('id_anggota', $id_anggota);
        return $this->db->singleSet();
    }

    public function getAllIdAndNama(){
        $this->db->query("SELECT id_anggota, nama FROM $this->table WHERE status_anggota = 'aktif'");
        return $this->db->resultSet();
    }

    public function updateAnggota($data){
        $query = "UPDATE $this->table SET 
        nama = :nama,
        alamat = :alamat,
        no_hp = :no_hp
        WHERE id_anggota = :id_anggota";

        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('alamat', $data['alamat']);
        $this->db->bind('no_hp', $data['no_hp']);
        $this->db->bind('id_anggota', $data['id_anggota']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteAnggota($id_anggota){
        $query = "UPDATE $this->table SET status_anggota = 'tidak aktif' WHERE id_anggota = :id_anggota";
        $this->db->query($query);
        $this->db->bind('id_anggota', $id_anggota);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function countAllAnggotaWithKeyword($keyword = null){
        $this->db->query("SELECT COUNT(*) as total FROM $this->table WHERE status_anggota = 'aktif' AND (nama LIKE :keyword OR alamat LIKE :keyword OR no_hp LIKE :keyword)");
        $this->db->bind('keyword', "%$keyword%");
        $result = $this->db->singleSet();
        return $result['total'];
    }

    public function getAnggotaByPage($limit, $offset, $keyword = null){
        if($keyword){
            $this->db->query("SELECT * FROM $this->table WHERE status_anggota = 'aktif' AND (nama LIKE :keyword OR alamat LIKE :keyword OR no_hp LIKE :keyword) LIMIT :limit OFFSET :offset");
            $this->db->bind('keyword', "%$keyword%");
        }else {
            $this->db->query("SELECT * FROM $this->table WHERE status_anggota = 'aktif' LIMIT :limit OFFSET :offset");
        }
        $this->db->bind('limit', $limit);
        $this->db->bind('offset', $offset);
        $this->db->execute();
        return $this->db->resultSet();
    }
}
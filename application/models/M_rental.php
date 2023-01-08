<?php

//Model ini merupakan bagian penanganan yang berhubungan dengan pengolahan dan manipulasi database
//Model yang terstruktur agar bisa digunakan berulang kali untuk membuat CRUD.
//Sehingga proses pembuatan CRUD menjadi lebih cepat dan efisien.
//function edit_data untuk mengambil data tertentu dari database.
//function get_data untuk menampilkan data atau mengambil data dari database.
//function insert_data untuk menginputkan data ke database.
//function update_data untuk mengupdate/mengubah data pada database.
//function delete_data untuk menghapus data pada database.

class M_rental extends CI_Model{
    function edit_data($where,$table){
        return $this->db->get_where($table,$where);
    }
    function get_data($table){
        return $this->db->get($table);
    }
    function insert_data($data,$table){
        $this->db->insert($table,$data);
    }
    function update_data($where,$data,$table){
        $this->db->where($where);
        $this->db->update($table,$data);
    }
    function delete_data($where,$table){
        $this->db->where($where);
        $this->db->delete($table);
    }
}
?>

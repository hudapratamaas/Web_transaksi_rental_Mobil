<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Admin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // cek login, jika belum login akan dialihkan ke halaman login
        // untuk melindungi controller admin dari pengguna yang belum melakukan login
        if ($this->session->userdata('status') != "login") {
            redirect(base_url() . 'welcome?pesan=belumlogin');
        }
    }
    //INDEX DASBOARD
    function index()
    {
        //Parsing data dari database
        //$this->db->query() berguna untuk menjalankan query database
        $data['transaksi'] = $this->db->query("select * from transaksi order by transaksi_id desc limit 10")->result();
        $data['kostumer'] = $this->db->query("select * from kostumer order by kostumer_id desc limit 10")->result();
        $data['mobil'] = $this->db->query("select * from mobil order by mobil_id desc limit 10")->result();

        //Pada function index kita akan membuat halaman dashboard
        $this->load->view('admin/header');//Menampilkan header
        $this->load->view('admin/index', $data);//Menampilkan index
        $this->load->view('admin/footer');//Menampilkan footer
    }

    //LOGOUT
    function logout()
    {
        $this->session->sess_destroy();//Untuk menghapus session
        redirect(base_url() . 'welcome?pesan=logout');//Setelah menghapuss session, kita alihkan halaman ke halaman login
    }

    //GANTI PASSWORD
    function ganti_password()
    {
        $this->load->view('admin/header');//Menampilkan header
        $this->load->view('admin/ganti_password');//Menampilkan ganti_password
        $this->load->view('admin/footer');//Menampilkan footer
    }
    function ganti_password_act()
    {
        /*
		Menangkap data yang di kirim dari form ganti password.
		Tetapkan validasinya, bahwa 'password baru' dan 'ulangi password' wajib di isi (required).
        Matches[ulang_pass] untuk memvalidasi kesamaan value. Jadi, form password baru harus = ulangi password
		*/
        $pass_baru = $this->input->post('pass_baru');
        $ulang_pass = $this->input->post('ulang_pass');
        $this->form_validation->set_rules('pass_baru', 'Password Baru', 'required|matches[ulang_pass]');
        $this->form_validation->set_rules('ulang_pass', 'Ulangi Password Baru', 'required');

        //Cek validasi
        //Jika tidak ada masalah, maka update data password admin akan dilakukan
        if ($this->form_validation->run() != false) {
            $data = array(
                'admin_password' => md5($pass_baru)
            );
            $w = array(
                'admin_id' => $this->session->userdata('id')//Untuk menampilkan session 'id' admin yang sedang login
            );
            $this->m_rental->update_data($w, $data, 'admin');

            //Setelah data password admin di update, maka halaman akan di alihkan lagi ke function ganti_password
            redirect(base_url() . 'admin/ganti_password?pesan=berhasil');
        } else {
            $this->load->view('admin/header');//Menampilkan header
            $this->load->view('admin/ganti_password');//Menampilkan ganti_password
            $this->load->view('admin/footer');//Menampilkan footer
        }
    }

    // CRUD MOBIL
    function mobil()
    {
        //Mengambil data mobil dari database menggunakan function get_data() pada model M_rental.php
        $data['mobil'] = $this->m_rental->get_data('mobil')->result();
        $this->load->view('admin/header');//Menampilkan header.php
        $this->load->view('admin/mobil', $data);//Menampilkan mobil.php dan menampilkan data mobil
        $this->load->view('admin/footer');//Menampilkan footer.php
    }

    //TAMBAH DATA MOBIL
    function mobil_add()
    {
        $this->load->view('admin/header');//Menampilkan header.php
        $this->load->view('admin/mobil_add');//Menampilkan mobil_add.php
        $this->load->view('admin/footer');//Menampilkan footer.php 
    }
    function mobil_add_act()
    {
        //Menangkap data yang di kirim dari form tambah mobil (mobil_add.php)
        $merk = $this->input->post('merk');
        $plat = $this->input->post('plat');
        $warna = $this->input->post('warna');
        $tahun = $this->input->post('tahun');
        $status = $this->input->post('status');

        //Menetapkan validasi bahwa 'Merk Mobil' dan 'Status Mobil' itu bersifat required (wajib diisi)
        $this->form_validation->set_rules('merk', 'Merk 
        Mobil', 'required');
        $this->form_validation->set_rules('status', 'Status 
        Mobil', 'required');

        //Cek Validasi
        //Jika tidak ada masalah, maka input tambah data mobil akan dilakukan
        if ($this->form_validation->run() != false) {
            $data = array(
                'mobil_merk' => $merk,
                'mobil_plat' => $plat,
                'mobil_warna' => $warna,
                'mobil_tahun' => $tahun,
                'mobil_status' => $status
            );
            $this->m_rental->insert_data($data, 'mobil');//Menjalankan perintah insert data pada m_rental
            redirect(base_url() . 'admin/mobil');//Jika sudah disimpan maka akan kembali pada halaman mobil.php
        } else {
            $this->load->view('admin/header');
            $this->load->view('admin/mobil_add');
            $this->load->view('admin/footer');
        }
    }

    //EDIT DATA MOBIL
    //Menangkap data id mobil yang ingin di edit di dalam parameter function mobil_edit
    //Ambil data mobil berdasarkan id, kemudian parsing ke view mobil_edit.php
    function mobil_edit($id)
    {
        $where = array(
            'mobil_id' => $id
        );
        //Menjalankan perintah edit_data pada m_rental
        $data['mobil'] = $this->m_rental->edit_data($where, 'mobil')->result();
        $this->load->view('admin/header');//Menampilkan header.php 
        $this->load->view('admin/mobil_edit', $data);//Menampilkan mobil_edit.php
        $this->load->view('admin/footer');//Menampilkan footer.php
    }
    function mobil_update()
    {
        //Menangkap data yang di kirim dari form edit data mobil (mobil_edit.php)
        $id = $this->input->post('id');
        $merk = $this->input->post('merk');
        $plat = $this->input->post('plat');
        $warna = $this->input->post('warna');
        $tahun = $this->input->post('tahun');
        $status = $this->input->post('status');
        //Menetapkan validasi bahwa 'merk' dan 'status' bersifat required (wajib terisi)
        $this->form_validation->set_rules('merk', 'Merk 
        Mobil', 'required');
        $this->form_validation->set_rules('status', 'Status 
        Mobil', 'required');
        //Cek Validasi
        if ($this->form_validation->run() != false) {
            $where = array(
                'mobil_id' => $id
            );
            $data = array(
                'mobil_merk' => $merk,
                'mobil_plat' => $plat,
                'mobil_warna' => $warna,
                'mobil_tahun' => $tahun,
                'mobil_status' => $status
            );
            $this->m_rental->update_data($where, $data, 'mobil');
            redirect(base_url() . 'admin/mobil');//Jika sudah edit data mobil maka akan dikembalikan pada halaman mobil.php
        } else {
            $where = array(
                'mobil_id' => $id
            );
            $data['mobil'] = $this->m_rental->edit_data($where, 'mobil')->result();
            $this->load->view('admin/header');
            $this->load->view('admin/mobil_edit', $data);
            $this->load->view('admin/footer');
        }
    }
    //HAPUS DATA MOBIL
    function mobil_hapus($id)
    {
        $where = array(
            'mobil_id' => $id
        );
        $this->m_rental->delete_data($where, 'mobil');
        redirect(base_url() . 'admin/mobil');
    }
    // AKHIR CRUD MOBIL

    // CRUD KOSTUMER
    function kostumer()
    {
        //Mengambil data kostumer dari database menggunakan function get_data() pada model M_rental.php
        $data['kostumer'] = $this->m_rental->get_data('kostumer')->result();
        $this->load->view('admin/header');//Menampilkan header.php
        $this->load->view('admin/kostumer', $data);//Menampilkan kostumer.php menampilkan data kostumer
        $this->load->view('admin/footer');//Menampilkan footer.php
    }

    //TAMBAH DATA KOSTUMER
    function kostumer_add()
    {
        $this->load->view('admin/header');//Menampilkan header.php 
        $this->load->view('admin/kostumer_add');//Menampilkan kostumer_add.php
        $this->load->view('admin/footer');//Menampilkan footer.php
    }
    function kostumer_add_act()
    {
        //Menangkap data yang di kirim dari form tambah kostumer (kostumer_add.php)
        $nama = $this->input->post('nama');
        $alamat = $this->input->post('alamat');
        $jk = $this->input->post('jk');
        $hp = $this->input->post('hp');
        $ktp = $this->input->post('ktp');
        //Menetapkan validasi bahwa inputan nama itu bersifat required (wajib diisi)
        $this->form_validation->set_rules('nama', 'nama', 'required');
        //Cek Validasi 
        if ($this->form_validation->run() != false) {
            $data = array(
                'kostumer_nama' => $nama,
                'kostumer_alamat' => $alamat,
                'kostumer_jk' => $jk,
                'kostumer_hp' => $hp,
                'kostumer_ktp' => $ktp
            );
            $this->m_rental->insert_data($data, 'kostumer');
            redirect(base_url() . 'admin/kostumer');//Jika tambah data kostumer sudah disimpan maka akan diarahkan kembali ke halaman kostumer.php
        } else {
            $this->load->view('admin/header');
            $this->load->view('admin/kostumer_add');
            $this->load->view('admin/footer');
        }
    }

    //EDIT DATA KOSTUMER
    //Menangkap data id kustomer yang ingin di edit di dalam parameter function kostumer_edit
    //Ambil data kostumer berdasarkan id, kemudian parsing ke view kostumer_edit.php
    function kostumer_edit($id)
    {
        $where = array(
            'kostumer_id' => $id
        );
        //Menjalankan perintah edit_data pada m_rental
        $data['kostumer'] = $this->m_rental->edit_data($where, 'kostumer')->result();
        $this->load->view('admin/header');//Menampilkan header
        $this->load->view('admin/kostumer_edit', $data);//Menampilkan kostumer_edit.php 
        $this->load->view('admin/footer');//Menampilkan footer
    }
    function kostumer_update()
    {
        //Menangkap data yang di kirim dari form edit data kostumer (kostumer_edit.php)
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $alamat = $this->input->post('alamat');
        $jk = $this->input->post('jk');
        $hp = $this->input->post('hp');
        $ktp = $this->input->post('ktp');
        //Menetapkan validasi bahwa inputan nama bersifat required (wajib diisi)
        $this->form_validation->set_rules('nama', 'nama', 'required');
        //Cek Validasi
        if ($this->form_validation->run() != false) {
            $where = array(
                'kostumer_id' => $id
            );
            $data = array(
                'kostumer_nama' => $nama,
                'kostumer_alamat' => $alamat,
                'kostumer_jk' => $jk,
                'kostumer_hp' => $hp,
                'kostumer_ktp' => $ktp
            );
            $this->m_rental->update_data($where, $data, 'kostumer');
            redirect(base_url() . 'admin/kostumer');//Jika sudah edit data kostumer maka akan dikembalikan pada halaman kostumer.php
        } else {
            $where = array(
                'kostumer_id' => $id
            );
            $data['kostumer'] = $this->m_rental->edit_data($where, 'kostumer')->result();
            $this->load->view('admin/header');
            $this->load->view('admin/kostumer_edit', $data);
            $this->load->view('admin/footer');
        }
    }

    //HAPUS DATA KUSTOMER
    function kostumer_hapus($id)
    {
        $where = array(
            'kostumer_id' => $id
        );
        $this->m_rental->delete_data($where, 'kostumer');
        redirect(base_url() . 'admin/kostumer');
    }
    // AKHIR CRUD KOSTUMER
    
    //CRUD TRANSAKSI
    function transaksi () {
        /*
        Menampilkan data dari tabel transaksi, mobil, dan kostumer
        Dengan kondisi :
        transaksi_mobil = mobil_id
        transaksi_kostumer = kostumer_id
        */
        $data ['transaksi'] = $this->db->query ("select * from transaksi, mobil, kostumer where transaksi_mobil = mobil_id and transaksi_kostumer = kostumer_id")->result();
        $this ->load->view ('admin/header');//Menampilkan header
        $this->load->view ('admin/transaksi', $data);//Menampilkan transaksi.php
        $this->load->view ('admin/footer');//Menampilkan footer
    }

    //TAMBAH DATA TRANSAKSI
    function transaksi_add() {
        $w = array('mobil_status' =>'1');
        //Menampilkan 2 data yaitu data mobil dan kostumer
        $data['mobil'] = $this->m_rental->edit_data($w, 'mobil')->result();
        $data['kostumer'] = $this->m_rental->get_data('kostumer')->result();
        $this->load->view('admin/header');//Menampilkan header.php
        $this->load->view('admin/transaksi_add', $data);//Menampilkan transaksi.php
        $this->load->view('admin/footer');//Menampilkan footer.php
    }
    function transaksi_add_act() {
        //Menangkap data yang di kirim dari form tambah transaksi (transaksi_add.php)
        $kostumer = $this->input->post('kostumer');
        $mobil = $this->input->post('mobil');
        $tgl_pinjam = $this->input->post('tgl_pinjam');
        $tgl_kembali = $this->input->post('tgl_kembali');
        $harga = $this->input->post('harga');
        $denda = $this->input->post('denda');

        //Menentukan validasi
        $this->form_validation->set_rules('kostumer', 'Kostumer', 'required');
        $this->form_validation->set_rules('mobil', 'Mobil', 'required');

        $this->form_validation->set_rules('tgl_pinjam', 'Tanggal Pinjam', 'required');
        $this->form_validation->set_rules('tgl_kembali', 'Tanggal Kembali', 'required');

        $this->form_validation->set_rules('harga', 'Harga', 'required');
        $this->form_validation->set_rules('denda', 'Denda', 'required');

        //Cek Validasi
        if ($this->form_validation->run() != false) {
            $data = array (
                'transaksi_karyawan' => $this->session->userdata ('id'),
                'transaksi_kostumer' => $kostumer,
                'transaksi_mobil' => $mobil,
                'transaksi_tgl_pinjam' => $tgl_pinjam,
                'transaksi_tgl_kembali' => $tgl_kembali,
                'transaksi_harga' => $harga,
                'transaksi_denda' => $denda,
                'transaksi_tgl' => date('Y-m-d')
            );
            
            $this->m_rental->insert_data($data, 'transaksi');

            //update status mobil yang dipinjam
            $d = array (
                'mobil_status' => '2'//Jika terjadi transaksi maka status mobil akan menjadi 'Sedang di Rental
            );

            $w = array (
                'mobil_id' => $mobil//Membaca merk mobil yang akan dirental berdasarkan id mobil
            );

            $this->m_rental->update_data ($w, $d, 'mobil');

            redirect (base_url(). 'admin/transaksi');//Ketika transaksi berhasil maka akan diarahkan ke halaman transaksi.php
        }else{//Jika transaksi gagal
            $w = array ('mobil_status' =>'1');//Status Mobil tidak berubah tetap 'Tersedia'
            $data['mobil'] = $this->m_rental->edit_data ($w,'mobil')->result();
            $data['kostumer'] = $this->m_rental->get_data('kostumer')->result();
            $this->load->view('admin/header');
            $this->load->view('admin/transaksi_add_act', $data);
            $this->load->view('admin/footer');
        }
    }

    //HAPUS DATA TRANSAKSI/BATALKAN TRANSAKSI
    function transaksi_hapus($id){
        $w = array(
            'transaksi_id' => $id
        );
        $data = $this->m_rental->edit_data($w,'transaksi')->row();
        $ww = array(
            'mobil_id' => $data->transaksi_mobil
        );
        $data2 = array(
            'mobil_status' => '1'//status mobil akan kembali menjadi tersedia
        );
        $this->m_rental->update_data($ww,$data2,'mobil');
        $this->m_rental->delete_data($w,'transaksi');
        redirect(base_url().'admin/transaksi');//Jika sudah berhasil batalkan transaksi akan diarahkan kembali ke halaman transaksi.php
    }

    //TRANSAKSI SELESAI
    //Membuat form transaksi selesai
    //Mengambil data transaksi berdasarkan id, kemudian ditampilkan ke transaksi_selesai
    function transaksi_selesai($id){
        //Menampilkan data mobil dan data kostumer
        $data['mobil'] = $this->m_rental->get_data('mobil')->result();
        $data['kostumer'] = $this->m_rental->get_data('kostumer')->result();
        /*
        Menampilkan data transaksi, mobil, kostumer yang ingin di proses
        dengan kondisi 
        transaksi_mobil=mobil_id
        transaksi_kostumer=kostumer_id
        transaksi_id='$id'
        */
        $data['transaksi'] = $this->db->query("select * from transaksi,mobil,kostumer where transaksi_mobil=mobil_id and transaksi_kostumer=kostumer_id and transaksi_id='$id'")->result();
        $this->load->view('admin/header');//Menampilkan header
        $this->load->view('admin/transaksi_selesai',$data);//Menampilkan transaksi_selesai.php
        $this->load->view('admin/footer');//Menampilkan footer
    }
    function transaksi_selesai_act(){
        //Menangkap data yang di kirim dari form  transaksi selesai (transaksi_selesai.php)
        $id = $this->input->post('id');
        $tgl_kembali = $this->input->post('tgl_kembali');
        $mobil = $this->input->post('mobil');
        $denda = $this->input->post('denda');
        $tgl_dikembalikan = $this->input->post('tgl_dikembalikan');
        //Menetapkan validasi bahwa inputan tanggal dikembalikan wajib di isi
        $this->form_validation->set_rules('tgl_dikembalikan','Tanggal Di Kembalikan','required');
        //Cek validasi
        if($this->form_validation->run() != false){
            // menghitung selisih hari
            $batas_kembali = strtotime($tgl_kembali);
            $dikembalikan = strtotime($tgl_dikembalikan);
            $selisih = abs(($batas_kembali - $dikembalikan)/(60*60*24));
            $total_denda = $denda*$selisih;
            // update status transaksi
            $data = array(
                'transaksi_tglkembalikan' => $tgl_dikembalikan,
                'transaksi_status' => '1',
                'transaksi_totaldenda' => $total_denda
            );
            $w = array(
                'transaksi_id' => $id
            );
            $this->m_rental->update_data($w,$data,'transaksi');
            // update status mobil
            $data2 = array(
                'mobil_status' => '1'
            );
            $w2 = array(
                'mobil_id' => $mobil
            );
            $this->m_rental->update_data($w2,$data2,'mobil');
            redirect(base_url().'admin/transaksi');
        }else{
            $data['mobil'] = $this->m_rental->get_data('mobil')->result();
            $data['kostumer'] = $this->m_rental->get_data('kostumer')->result();
            $data['transaksi'] = $this->db->query("select * from transaksi,mobil,kostumer where transaksi_mobil=mobil_id and transaksi_kostumer=kostumer_id and transaksi_id='$id'")->result();
            $this->load->view('admin/header');
            $this->load->view('admin/transaksi_selesai',$data);
            $this->load->view('admin/footer');
        }
    }
    // AKHIR TRANSAKSI RENTAL

    


    //LAPORAN
    function laporan() {
        //Aksi untuk memproses inputan
        $dari = $this->input->post('dari');
        $sampai = $this->input->post('sampai');
        
        //Menetapkan aturan validasi bahwa inputan 'Dari Tanggal' dan 'Sampai Tanggal' harus di isi
        $this->form_validation->set_rules('dari', 'Dari Tanggal', 'required');
        $this->form_validation->set_rules('sampai', 'Sampai Tanggal', 'required');
        
        //Cek validasi
        if($this->form_validation->run() != false) {
            $data['laporan'] = $this->db->query("select * from transaksi, mobil, kostumer where transaksi_mobil=mobil_id and transaksi_kostumer=kostumer_id and date(transaksi_tgl) >= '$dari'")->result();
            $this->load->view('admin/header');//Menampilkan header.php
            $this->load->view('admin/laporan_filter', $data);//Menampilkan laporan_filter.php
            $this->load->view('admin/footer');//Menampilkan footer.php
        }else{
            $this->load->view('admin/header');//Menampilkan header.php
            $this->load->view('admin/laporan');//Menampilkan laporan.php
            $this->load->view('admin/footer');//Menampilkan footer.php
        }
    }
    function laporan_print() {
        //Menangkap data tanggal dari dan tanggal sampai
        $dari = $this->input->get('dari');
        $sampai = $this->input->get('sampai');

        if($dari != "" && $sampai != "") {
            $data['laporan'] = $this->db->query("select * from transaksi,mobil,kostumer where transaksi_mobil=mobil_id and 
            transaksi_kostumer=kostumer_id and date(transaksi_tgl) >= '$dari'")->result();
            $this->load->view('admin/laporan_print',$data);//Menampilkan laporan_print.php
        }else{
            redirect("admin/laporan");
        }
    }
    function laporan_pdf() {
        //Memanggil library dompdf_gen
        $this->load->library('dompdf_gen');
        //Menangkap data tanggal dari dan tanggal sampai
        $dari = $this->input->get('dari');
        $sampai = $this->input->get('sampai');

        $data['laporan'] = $this->db->query("select * from transaksi,mobil,kostumer where transaksi_mobil=mobil_id and 
        transaksi_kostumer=kostumer_id and date(transaksi_tgl) >= '$dari'")->result();
        $this->load->view('admin/laporan_pdf', $data);//Menampilkan laporan_pdf.php

        //Konversi ke file PDF
        $paper_size = 'A4'; // ukuran kertas
        $orientation = 'landscape'; //tipe format kertas potrait atau landscape
        $html = $this->output->get_output();

        $this->dompdf->set_paper($paper_size, $orientation);
        //Convert to PDF
        $this->dompdf->load_html($html);
        $this->dompdf->render();
        $this->dompdf->stream("laporan.pdf", array('Attachment'=>0)); 
        // nama file pdf yang di hasilkan
    }   
}

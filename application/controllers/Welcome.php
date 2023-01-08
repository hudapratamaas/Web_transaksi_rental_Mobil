<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	 /* 
	 Controller merupakan kumpulan intruksi aksi yang menghubungkan antara model dan view
	 Data yang disimpan pada database (model) diambil oleh controller
	 Kemudian controller pula yang menampilkannya ke view
	 */

	 /*
	 Function__construct() adalah function yang pertama kali dijalankan pada saat sebuah clas dijalankan
	 Pada function__construct kita memanggil model m_rental, karena kita membutuhkan instruksi pada m_rental untuk proses login ini
	 Setelah function__construct() dijalankan, selanjutnya yang dijalankan adalah function index()
	 Pada function index kita membuat perintah untuk menampilkan view login (login.php)
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_rental');
	}

	public function index()
	{
		$this->load->view('login');
	}
	function login()
	{
		/*
		Menangkap data yang di kirim dari form.
		Tetapkan validasinya, bahwa username dan password harus di isi (required).
		*/
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		//Cek Validasi
		//Cek inputan username dan password sudah sesuai belum dengan admin_username & admin_password di tabel admin
		if ($this->form_validation->run() != false) {
			$where = array(
				'admin_username' => $username,
				'admin_password' => md5($password)
			);
			$data = $this->m_rental->edit_data($where, 'admin');
			$d = $this->m_rental->edit_data($where, 'admin')->row();
			$cek = $data->num_rows(); //num_rows digunakan untuk menghasilkan jumlah rows yang terdapat dari sebuah tabel di database.


			//Jika pengecekan sudah selesai dan sesuai, maka selanjutnya membuat session id, nama, dan status.
			if ($cek > 0) {
				$session = array(
					'id' => $d->admin_id, //Id :kita simpan id admin yang melakukan login
					'nama' => $d->admin_nama, //Nama :menyimpan nama admin yang login
					'status' => 'login' //Status :set dengan login
				);
				/*
				Setelah membuat session kita alihkan halamannya ke halaman controller admin
				karena controller admin akan dijadikan sebagai halaman admin.
				*/
				$this->session->set_userdata($session);
				redirect(base_url() . 'admin');
			} else {
				//Jika login gagal maka akan kembali ke controller welcome
				redirect(base_url() . 'welcome?pesan=gagal');
			}
		} else {
			$this->load->view('login');
		}
	}
}

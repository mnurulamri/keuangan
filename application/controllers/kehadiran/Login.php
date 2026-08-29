<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//if(!session_id()) session_start();
class Login extends CI_Controller 
{
	private $ci;
	private $server;
	private $port;
	private $admin;
	private $password;
	private $conn;
	private $bind;
	private $basedn;
	private $filter;
	private $username;

	public function __construct()
	{
		parent::__construct();

  		$this->server		= "ldap://152.118.39.37";
  		$this->port			= "389";

		$this->load->database();
		$this->load->model('Presensi_model');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
	}

	// Index login
	public function index()
	{
		$data = array( 'title' => 'Login SSO');
		$this->load->view('kehadiran/login_view', $data);
	}

	public function cek_ldap()
	{
		$this->conn = ldap_connect($this->server,$this->port) or die("Tidak dapat terhubung ke server");

		if($this->conn)
		{
            # jika terhubung ke server LDAP
			# Check validation for user input in SignUp form
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');

			if ($this->form_validation->run() == true)  // jika validasi sah
			{
                # 'validasi sah';
                # set username dan password
				$this->username = $this->input->post('username');
				$this->password = $this->input->post('password');

				# set atribut LDAP
				$this->filter = "uid=" . $this->username;
				$this->base_dn = "o=Universitas Indonesia,c=ID";

				$result = @ldap_search($this->conn, $this->base_dn, $this->filter);
				$info = ldap_get_entries($this->conn, $result);
                
				if($info['count'] == 0) {
					ldap_close($this->conn);
					redirect('login', 'refresh');
				}

				$this->DN = $info[0]["dn"];
				$ret = @ldap_bind($this->conn, $this->DN, $this->password);

                # cek password
                if(!$ret)  
				{
                    # jika password salah
					redirect('index.php/kehadiran/login', 'refresh');
				} else {  
                    # jika password ok
                    # cek ke database pegawai pns
					$array = $this->Presensi_model->getStaf($this->username);

					foreach($array as $row){
						$nip = $row['nip'];
						$nama = $row['nama_bergelar'];
						$hak_akses = $row['hak_akses'];
					}

					# set array berisi informasi data session
					$session_data = array('nip_presensi'=>$nip, 'nama_presensi'=>$nama, 'hak_akses_presensi'=>$hak_akses);

					# masukkan elemen data ke dalam session
					$this->session->set_userdata('logged_in_presensi', $session_data);
					redirect('index.php/kehadiran/rekam_kehadiran');
                    //echo '<pre>'; print_r($this->session->userdata['logged_in_presensi']);
                }
            } else {
                echo 'validasi tidak sah';
            }
		} else {

			# jika tidak terhubung ke server LDAP
			echo 'gagal terhubung ke server';
		}
	}
	
	// Cek login
	public function cek_login() {
		if($this->session->userdata('username') == '' && $this->session->userdata('akses_level')=='') {
			$this->session->set_flashdata('sukses','Oops...silakan login dulu');
			redirect(base_url('index.php/kehadiran/login'));
		}	
	}
	
	// Logout
	public function logout() {
		$this->session->unset_userdata('username_presensi');
		$this->session->unset_userdata('nip_presensi');
		$this->session->unset_userdata('hak_akses_presensi');
		session_destroy();
		$this->session->set_flashdata('sukses','Terimakasih, Anda berhasil logout');
		redirect(base_url().'index.php/kehadiran/login');
	}
}
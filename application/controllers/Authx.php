<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->library('session');

        // Cek apakah pengguna sudah login        
        //if (!$this->session->userdata('logged_anggaran')) {
        //    redirect('auth/login');
        //}
    }

    public function index()
    {
        $this->load->view('auth/login_view');
    }

    public function login()
    {
        $this->load->view('auth/login_view');
    }

    public function unit_kerja()
    {
        
        $data['title'] = 'Unit Kerja';
        $data['nama'] = 'xxx';

        $unit_kerja = $this->db->get('units')->result_array();
        $data['unit_kerja'] = $unit_kerja;
        
        //$this->load->view('template/header', $data);
        $this->load->view('auth/role_view', $data);
    }

    public function set_role()
    {
        // Lindungi dari serangan hacker dengan validasi dan sanitasi input
        /*$this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric');
        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, redirect atau tampilkan pesan error
            $this->session->set_flashdata('error', validation_errors());
            redirect('auth/login');
            return;
        }*/

        $username = htmlspecialchars($this->input->post('username', TRUE), ENT_QUOTES, 'UTF-8');

        // ambil data unit dari database unit_kerja
        $sql = "SELECT * FROM unit_kerja";
        $query = $this->db->query($sql);
        $array_unit = $query->result_array();

        // ambil data role dari database users
        $sql = "SELECT * FROM users WHERE username = ?";
        $query = $this->db->query($sql, array($username));
        if ($query->num_rows() == 0) {
            $this->session->set_flashdata('error', 'Username tidak ditemukan!');
            redirect('auth/login');
            return;
        } 
        $user = $query->row_array();
        $role = $user['role'];
        $kode_bidang = $user['kode_bidang'];
		$array_user = $query->result_array();

		if($query->num_rows() == 1) {
	        // jika role adalah admin, redirect ke unit kerja
	        
	        if ($role == 'admin') {
	            
	            $newdata = array(
	                'username'  => $username,
	                'role'  => $role
	            );
	            $this->session->set_userdata('logged_anggaran', $newdata);
	            
	            redirect('auth/unit_kerja');
	
	        } else if($role == 'anggaran' || $role == 'korpum' || $role == 'manajer' || $role == 'kasir' || $role == 'verifikator' || $role == 'yunior_akuntan') {
	            // jika role adalah pengelola, redirect ke form pengelola
	            $this->set_role_bridge_pengelola($role, $username);
	        } else if ($role == 'pum') {
	            // jika role adalah pum, redirect ke form pum
	            $this->set_role_bridge_pum($role, $kode_bidang, $username);
	        } else {
	            // jika role tidak dikenali, tampilkan pesan error
	            $this->session->set_flashdata('error', 'Role tidak dikenali!');
	            redirect('auth/login');
	        }
		} else {
            // set session dengan data user dan tampilkan pilihan role            
            $newdata = array(
                'role'  => $role,
                'username'  => $username,
                'kode_bidang'  => $kode_bidang
            );

            $this->session->set_userdata('logged_anggaran', $newdata);
            
			$data['array_unit'] = $array_unit;
			$data['array_user'] = $array_user;
			$this->load->view('auth/role_view_non_admin', $data);
		}
    }

    public function set_role_pum()
    {
        $newdata = array(
            'role'  => 'pum',
            'username'  => $this->session->userdata('logged_anggaran')['username'],
            'kode_bidang'  => $this->input->post('kode_bidang'),
            'kode_dpsj'  => $this->input->post('kode_dpsj')
        );

        $this->session->set_userdata('logged_anggaran', $newdata);
        redirect('unit_kerja/dashboard');
    }

    public function set_role_admin()
    {
        $newdata = array(
            'role'  => $this->input->post('role'),
            'username'  => $this->session->userdata('logged_anggaran')['username'],
            'kode_bidang'  => $this->input->post('kode_bidang')
        );

        $this->session->set_userdata('logged_anggaran', $newdata);
        
        if($this->session->userdata('logged_anggaran')['role'] == 'pum') {
            // jika role adalah pum, redirect ke form pum
            redirect('unit_kerja/dashboard');
        } else if($this->session->userdata('logged_anggaran')['role'] == 'anggaran' || $this->session->userdata('logged_anggaran')['role'] == 'korpum' || $this->session->userdata('logged_anggaran')['role'] == 'manajer' || $this->session->userdata('logged_anggaran')['role'] == 'kasir' || $this->session->userdata('logged_anggaran')['role'] == 'verifikator' || $this->session->userdata('logged_anggaran')['role'] == 'yunior_akuntan') {
            // jika role adalah pengelola, redirect ke form pengelola
            if($this->session->userdata('logged_anggaran')['role'] == 'anggaran'){
                $url = 'unit_anggaran';
                redirect( $url.'/dashboard' );
            } else if($this->session->userdata('logged_anggaran')['role'] == 'korpum'){
                $url = 'korpum';
                redirect( $url.'/dashboard' );
            } else if($this->session->userdata('logged_anggaran')['role'] == 'manajer'){
                $url = 'manajer';
                redirect( $url.'/dashboard' );
            } else if($this->session->userdata('logged_anggaran')['role'] == 'kasir'){
                $url = 'kasir';
                redirect( $url.'/dashboard' );
            } else if($this->session->userdata('logged_anggaran')['role'] == 'verifikator'){
                $url = 'verifikator';
                redirect( $url.'/dashboard' );
            } else if($this->session->userdata('logged_anggaran')['role'] == 'yunior_akuntan'){
                $url = 'yunior_akuntan';
                redirect( $url.'/dashboard' );
            } else {
                $url = $this->session->userdata('logged_anggaran')['role'];
                redirect( $url.'/dashboard' );
            }
            //echo '<pre>';print_r($url); exit();
            
        }
    }

    public function set_role_admin_sidebar()
    {
        $username  = $this->session->userdata('logged_anggaran')['username'];
        $newdata = array(
            'role'  => 'admin',
            'username'  => $username,
            'kode_bidang'  => '0'
        );

        $this->session->set_userdata('logged_anggaran', $newdata);
        redirect('auth/unit_kerja');
        //echo '<pre>';print_r($this->session->userdata()); exit();
    }

    public function set_role_bridge_pum($role, $kode_bidang, $username)
	{
        // set kode_dpsj berdasarkan kode_bidang
        $sql = "SELECT kode_dpsj FROM unit_kerja WHERE kode_bidang = '$kode_bidang' LIMIT 1";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        $kode_dpsj = $result['kode_dpsj'];

        $newdata = array(
            'role'  => $role,
            'username'  => $username,
            'kode_bidang'  => $kode_bidang,
            'kode_dpsj'  => $kode_dpsj
        );

        $this->session->set_userdata('logged_anggaran', $newdata);
        redirect('unit_kerja/dashboard');
        //echo '<pre>';print_r($this->session->userdata()); exit();
    }

    public function set_role_bridge_pengelola($role, $username)
	{
        $newdata = array(
            'role'  => $role,
            'username'  => $username,
            'kode_bidang'  => '0'
        );

        $this->session->set_userdata('logged_anggaran', $newdata);

        //echo '<pre>';print_r($this->session->userdata()); exit();

        // redirect sesuaikan dengan role yang dipilih
        switch ($role) {
            case 'anggaran':
                redirect('unit_anggaran/dashboard');
                break;
            case 'korpum':
                redirect('korpum/dashboard');
                break;
            case 'manajer':
                redirect('manajer/dashboard');
                break;
            case 'kasir':
                redirect('kasir/dashboard');
                break;
            case 'verifikator':
                redirect('verifikator/dashboard');
                break;
            case 'yunior_akuntan':
                redirect('yunior_akuntan/dashboard');
                break;
        }

        //header("location:".base_url()."Test_autocomplete");
        //redirect('unit_anggaran/monitoring');
        
    }

    public function logout()
    {
        // Hapus semua data session
        $this->session->unset_userdata('logged_anggaran');
        $this->session->sess_destroy();

        // Redirect ke halaman login
        redirect('auth/login');
    }
}
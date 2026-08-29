<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Anggaran_model');
		$this->load->model('Rka_model');
		//$this->load->model('User_model');
		$this->load->library('form_validation');
		$this->load->helper('status_helper');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('Ajax_pagination_realisasi');
		$this->perPage = 2;

        
		$session_data = array(
                        'username'  => 'xxx',
                        'hak_akses' => 1,
                        'role' => 'admin',
                        'kode_org_anggaran' => '00',
                        'cn_anggaran' => ''
                    );
		$this->session->set_userdata('logged_anggaran', $session_data);
	}

	public function index()
	{		
		/*if (!$menu) {
			$menu = array(
				array('id' => 1, 'parent' => 0, 'label' => 'Dashboard', 'link' => '#'),
				array('id' => 2, 'parent' => 0, 'label' => 'Settings', 'link' => '#'),
				array('id' => 3, 'parent' => 1, 'label' => 'Profile', 'link' => '#'),
				array('id' => 4, 'parent' => 1, 'label' => 'Reports', 'link' => '#')
			);
		}*/
		$data['menu'] = $this->menu();
		$data['title'] = 'Test Menu';
		$data['heading'] = 'Test Heading';
		$data['nama'] = 'Test Nama';

		
		$data['nama'] = 'test nama';
		$this->load->view('template/header', $data);
		$this->load->view('template/tes_menu', $data);
		$this->load->view('unit_kerja/realisasi-ajax-index', $data);       
		$this->load->view('template/footer');
	}

	public function menu()
	{		
		$sql = "SELECT * FROM menu where anggaran = 1";
		$query = $this->db->query($sql);
		$menu = $query->result_array();
		return $menu;
	}

	public function test_konek(){
		$this->sdm_db = $this->load->database('sdm', TRUE);
		$sql = "SELECT * FROM pejabat WHERE kd_struktur > 0 AND end_date > date(now()) AND KodeBidang = '015' ORDER BY kd_struktur";
		$query = $this->sdm_db->query($sql);
		$result = $query->result_array();
		print_r($result);
	}
}


<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Starter extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
        
		$session_data = array(
                        'username'  => 'xxx',
                        'hak_akses' => 1,
                        'role' => 'admin',
                        'kode_org_anggaran' => '00',
                        'cn_anggaran' => ''
                    );
		$this->session->set_userdata('logged_anggaran', $session_data);

        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index(){
        $this->load->view('starter');
    }
}
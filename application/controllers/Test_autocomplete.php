<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_autocomplete extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->username = 'mnurulamri';
        $this->jenis = 'staf';
        $this->load->library('session');
		$this->load->helper('url');
    }

    public function index()
    {  
        $data['title'] = 'test title';
        $data['nama'] = 'test nama';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('test_autocomplete');
        $this->load->view('template/footer');
		$this->load->view('test_autocomplete_script');
    }
}
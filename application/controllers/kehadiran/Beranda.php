<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller 
{
	public function __construct()
    {
		parent::__construct();
        $this->load->helper('url');
	}

    public function index()
    {
        $this->load->view('layout/header');
		$this->load->view('layout/sidebar');
        $this->load->view('kehadiran/beranda_view');
        $this->load->view('layout/footer');
    }
}
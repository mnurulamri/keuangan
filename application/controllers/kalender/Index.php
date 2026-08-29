<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Index extends CI_Controller 
{
	public function __construct()
    {
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->remun_db = $this->load->database('remun', TRUE);	
		//$this->load->library('session');
	}
		
	public function index()
	{
		$this->layoutHeader();
		$this->load->view('kalender/kalender_view');
		$this->layoutFooter();
	}
		
	public function layoutHeader()
	{
		$data['nama'] = 'M. Nuurul Amri';
	    $this->load->view('layout/header', $data);
		$this->load->view('layout/sidebar');
	}
	
	public function layoutFooter()
	{
		$this->load->view('layout/sidebar');
	}
}
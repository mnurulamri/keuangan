<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller 
{
	public function __construct()
    {
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->remun_db = $this->load->database('remun', TRUE);	
		$this->load->model('Presensi_model');
		$this->load->library('session');
	}
		
	public function layoutHeader()
	{
		$data['nama'] = $this->session->userdata('nama_presensi');
	    $this->load->view('layout/header', $data);
		$this->load->view('layout/sidebar');
	}
	
	public function layoutFooter()
	{
		$this->load->view('layout/sidebar');
	}

	public function listPegawai()
	{
		$this->layoutHeader();
		$this->load->view('kehadiran/list_pegawai_view');
		$this->layoutFooter();
	}
	
	public function listPegawaiAjax()
	{
	    $bulan = $this->input->post('bulan');
	    $tahun = $this->input->post('tahun');
	    $tanggal = $tahun.'-'.$bulan;
	    
	    $result = $this->Presensi_model->getListPegawai($tanggal);
	    foreach($result as $row){
	        $array_pegawai[] = $row;
	    }
	    
	    $data['array_pegawai'] = $array_pegawai;
	    $this->load->view('kehadiran/list_pegawai_ajax_view', $data);
	}
	
	public function detail()
	{
		$this->layoutHeader();
		$this->load->view('kehadiran/item_pegawai_view');
		$this->layoutFooter();
	}

	/*
    public function presensiDetailPegawai()
	{
        $nip = $this->input->post('nip');
        $bulan_opt = $this->input->post('bulan');
	    $bulan = substr($bulan_opt, 0, 2);
		$tahun = $this->input->post('tahun');

		# tarik data presensi
        $result = $this->Presensi_model->getPresensiDetailPegawai($tahun, $bulan, $nip);
        if($result){
			foreach($result as $row){
				$data_presensi[] = $row;
			}
		} else {
			$data_presensi = array();
		}
		
		# tarik kode jenis Cuti, Sakit dan izin
		$result = $this->Presensi_model->getDataItm($nip);
		if($result){
			foreach($result as $row){
				$data_itm[] = $row;
			}
		} else {
			$data_itm = array();
		}

		# tarik data hari libur
		$result = $this->Presensi_model->getDataLibur($tahun);
		foreach($result as $row){
	        $data_libur[] = $row;
	    }

		# tarik data shift
		$result = $this->Presensi_model->getDataShift();
		if($result){
			foreach($result as $row){
				$data_shift[] = $row;
			}
		} else {
			$data_shift = array();
		}

		# tarik data waktu kerja
		$result = $this->Presensi_model->getDataWaktuKerja();
		foreach($result as $row){
	        $data_waktu_kerja[] = $row;
	    }

		$data['nip'] = $nip;
        $data['data_tahun'] = $tahun;
        $data['data_bulan'] = $bulan_opt;
		$data['data_presensi'] = $data_presensi;
		$data['data_itm'] = $data_itm;
		$data['data_libur'] = $data_libur;
		$data['data_shift'] = $data_shift;
		$data['data_waktu_kerja'] = $data_waktu_kerja;
		echo 'test';
		$this->load->view('presensi_detail', $data);
    }
	*/
	public function presensiLoadData()
	{
	    $result = $this->Presensi_model->getPresensiLoadData();
	    foreach($result as $row){
	        $array_data[] = $row;
	    }
	    
	    $data['array_data'] = $array_data;
	    
	    $this->load->view('layout/header');
		$this->load->view('layout/sidebar'); //$this->load->view('layout/sidebar', $data);
		$this->load->view('presensi_data_view', $data);
		$this->load->view('layout/footer');
	}

	public function hariLibur()
	{
	    $result = $this->Presensi_model->getDataLibur('2022');
	    foreach($result as $row){
	        $array_hari_libur[] = $row;
	    }
	    $data['array_hari_libur'] = $array_hari_libur;
	    $this->load->view('hari_libur_view', $data);
	}
	
}
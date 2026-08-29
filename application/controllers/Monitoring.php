<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->database();
        $this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('status_helper');
        $this->load->helper('tanggal_helper');
		$this->load->helper('menu_helper');
		$this->load->library('session');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index() 
    {
        // set periode penginputan
        $sql = "SELECT * FROM periode ORDER BY tahun DESC, bulan ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
		foreach ($result as $row){
			$array_tahun[] = $row['tahun'];
			$array_bulan[] = $row['bulan'];
		}
        $data['array_tahun'] = array_unique($array_tahun);
        $data['array_bulan'] = $array_bulan;
            
		//set bulan aktif
		$sql = "SELECT * FROM periode WHERE lock_data = 0 ORDER BY tahun DESC, bulan ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
		foreach ($result as $row){
			$array_tahun_aktif[] = $row['tahun'];
			$array_bulan_aktif[] = $row['bulan'];
		}
        $data['array_tahun_aktif'] = array_unique($array_tahun_aktif);
        $data['array_bulan_aktif'] = $array_bulan_aktif;

        $data['title'] = 'Monitoring Pengajuan Dana';
        $data['nama'] = $this->session->userdata('logged_anggaran')['username'];
        
        // ambil kode_status dari input get
        $data['kode_status'] = $this->input->get('kode_status');

        // ambil data status dari tabel status untuk field pum yang bernilai 1
        $sql = "SELECT * FROM status WHERE anggaran = 1";
        $data['status_list'] = $this->db->query($sql)->result_array();

        // ambil data monitoring dari tabel monitoring
        $sql = "SELECT * FROM monitoring";
        $data['monitoring_list'] = $this->db->query($sql)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => $this->menu()) );
        $this->load->view('monitoring/index', $data);        
        $this->load->view('template/footer');
    }

    public function data()
    {
        // inisialisasi parameter
        $status = $this->input->post('status');       
        $tahun = $this->input->post('tahun');       
        $bulan = $this->input->post('bulan');
        //$tahun=2026; $bulan='02';
        $sql = "SELECT b.tanggal, a.nomor_pengajuan, a.kode_unit, a.form, uraian, nominal_pengajuan, anggaran_keterangan_disetujui, 
                anggaran_tgl_disetujui as tgl_penyerahan, anggaran_keterangan_pending as verifikasi_anggaran, manajer_tgl_disetujui as tgl_persetujuan_manajer, 
                kasir_tgl_disetujui as tgl_umko_cair, nominal_umko_cair, realisasi_umko, sisa_umko, tgl_pengajuan_realisasi as tgl_penyerahan_spj, 
                concat(verifikator_keterangan_disetujui, '|', verifikator_keterangan_pending, '|', verifikator_keterangan_batal) as keterangan_retur, 
                verifikator_tgl_pending as tgl_retur_fakultas, verifikator_tgl_disetujui as tgl_selesai_verifikasi, 
                korpum_tgl_disetujui as tgl_verifikasi_korpum, concat(korpum_keterangan_disetujui, '|', korpum_keterangan_pending, '|', korpum_keterangan_batal) as catatan_korpum,
                tgl_pemberian_dokumen_ke_junior_akuntan, no_invoice_pp, tgl_pp, no_pp, pph_21, pph_23, netto, tgl_verifikasi_pp_koord_pum, 
                tgl_pengiriman_dokumen_ke_pau, tgl_retur_dari_pau, keterangan_retur_pau, tgl_transfer_ke_cashcard_ls, nominal, tgl_penyerahan_reimburse, catatan 
                FROM monitoring a, pengajuan_pemohon b 
                WHERE a.id_pengajuan_pemohon = b.id 
                AND SUBSTR(a.nomor_pengajuan, 12, 4) = ? AND SUBSTR(a.nomor_pengajuan, 9, 2) = ?";
        $result = $this->db->query($sql, array($tahun, $bulan))->result_array();

        $this->load->view('monitoring/data', array('data' => $result)); 
    }

	public function menu()
	{			
        
        $role = $this->session->userdata('logged_anggaran')['role'];
		$sql = "SELECT * FROM menu where $role = 1 order by parent, sort";
		$query = $this->db->query($sql);
		$menu = $query->result_array();
		return $menu;
	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_pp extends CI_Controller {
    /**
     * Constructor to initialize the controller
     * Loads necessary models, libraries, and session data
     */
    //protected $perPage;


    public function __construct() {
        parent::__construct();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        //$this->load->model('User_model');
        $this->load->library('form_validation');
		$this->load->helper('url');
        $this->load->helper('status_helper');
        $this->load->helper('menu_helper');
		$this->load->helper('periode_helper');
		$this->load->helper('tanggal_helper');
		$this->load->library('session');
        $this->load->library('Ajax_pagination_pengajuan');
        $this->perPage = 10;

        // Cek apakah user sudah login        
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }
    /**
     * Index method to load the main view for Pengajuan
     */

    public function index() 
    {

        // set periode penginputan
		$data['periode'] = $this->periode();
		if($this->periode()){
	        foreach($data['periode'] as $row){
	            $tahun =$row['tahun'];
	            $bulan =$row['bulan'];
	        }
		} else {
			$tahun = 2045;
			$bulan = '01';
		}

        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;
            
        $data['title'] = 'Invoice PP';
        $data['nama'] = $this->session->userdata['logged_anggaran']['username'];
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('yunior_akuntan/invoice-ajax-index', $data);        
        $this->load->view('template/footer');
	}

    public function data(){
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $tgl = $this->input->post('tgl');

        $conditions = array();
        $conditions['tahun'] = $tahun;
        $conditions['bulan'] = $bulan;

        $data['title'] = 'Invoice PP';
        $data['nama'] = $this->session->userdata['logged_anggaran']['username'];
        $data['perPage'] = $this->perPage;
         
        if($tgl == '00'){
        	$filter_tgl = '';
        } else {
        	$filter_tgl = "AND b.tgl = '$tgl' ";
        }
        
        /*$sql = "SELECT b.nomor_pengajuan, b.uraian, b.no_invoice_pp, b.no_tiket, b.tahun, b.bulan, b.tgl, a.kode_dpsj, a.deskripsi_dpsj, a.kode_kegiatan, a.kode_akun, a.kode_dana, a.aktual, a.pph, a.netto, a.id_pengajuan_pemohon as id_pengajuan_pemohon, tgl_penyerahan, b.no_pp, b.tgl_pp
                FROM view_pengajuan_rincian_realisasi a, invoice_rekap_procost b 
                WHERE a.id_pengajuan_pemohon = b.id_pengajuan_pemohon AND b.tahun = ? AND b.bulan = ? AND invoice_status = '1'";
        */
        $sql = "SELECT b.nomor_pengajuan, b.uraian, b.no_invoice_pp, b.no_tiket, b.tahun, b.bulan, b.tgl, a.kode_dpsj, a.deskripsi_dpsj, a.kode_kegiatan, a.kode_akun, a.kode_dana, a.aktual_report as aktual, a.pph, a.netto, b.id_pengajuan_pemohon as id_pengajuan_pemohon, invoice_status, form, pph_d02, netto_d02, b.no_pp, b.tgl_pp
                FROM view_pengajuan_rincian_realisasi a, invoice_rekap_procost b 
                WHERE a.id_pengajuan_pemohon = b.id_pengajuan_pemohon AND b.tahun = ? AND b.bulan = ? $filter_tgl 
                ORDER BY b.tahun DESC, b.bulan DESC, b.tgl DESC, b.no_invoice_pp ASC, b.nomor_pengajuan ASC"; 
        $query = $this->db->query($sql, array($tahun, $bulan));
        $data['result'] = $query->result_array();
        $this->load->view('yunior_akuntan/invoice-ajax-data', $data);

    }

    public function update_pp(){
        $no_tiket = $this->input->post('no_tiket');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $no_pp = $this->input->post('no_pp');
        $tgl_pp = $this->input->post('tgl_pp');

        $data = array(
            'no_pp' => $no_pp,
            'tgl_pp' => $tgl_pp
        );

        // update tabel invoice_rekap_procost
        $this->db->where('no_tiket', $no_tiket);
        $update = $this->db->update('invoice_rekap_procost', $data);

        // update tabel monitoring
        $data_monitoring = array(
            'no_pp' => $no_pp,
            'tgl_pp' => $tgl_pp
        );
        $this->db->where('id_pengajuan_pemohon', $id_pengajuan_pemohon);
        $update_monitoring = $this->db->update('monitoring', $data_monitoring);

        // return response
        if($update){
            echo json_encode(array('status' => 'success', 'message' => 'PP updated successfully.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to update PP.'));
        }
    }

    public function periode() {
        $sql = "SELECT * FROM periode WHERE lock_data = 0 ORDER BY tahun DESC, bulan DESC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
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
        $this->load->view('invoice_pp/invoice-ajax-index', $data);        
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
        
        $sql = "SELECT * FROM unit_kerja";
        $query = $this->db->query($sql);
        $data['array_segmen'] = $query->result_array();
        
        /*$sql = "SELECT b.nomor_pengajuan, b.uraian, b.no_invoice_pp, b.no_tiket, b.tahun, b.bulan, b.tgl, a.kode_dpsj, a.deskripsi_dpsj, a.kode_kegiatan, a.kode_akun, a.kode_dana, a.aktual, a.pph, a.netto, a.id_pengajuan_pemohon as id_pengajuan_pemohon, tgl_penyerahan, b.no_pp, b.tgl_pp
                FROM view_pengajuan_rincian_realisasi a, invoice_rekap_procost b 
                WHERE a.id_pengajuan_pemohon = b.id_pengajuan_pemohon AND b.tahun = ? AND b.bulan = ? AND invoice_status = '1'";
        */
        $sql = "SELECT b.nomor_pengajuan, b.uraian, b.no_invoice_pp, b.no_tiket, b.tahun, b.bulan, b.tgl, a.kode_dpsj, a.deskripsi_dpsj, a.kode_kegiatan, 
                    a.kode_akun, a.kode_dana, a.aktual_report as aktual, a.pph, a.netto, b.id_pengajuan_pemohon as id_pengajuan_pemohon, invoice_status, form, pph_d02, netto_d02, netto_d01_d02, 
                    b.no_pp, b.tgl_pp, b.no_mdk, b.tgl_mdk, id_monitoring, kode_status
                FROM view_pengajuan_rincian_realisasi a, invoice_rekap_procost b 
                WHERE a.id_pengajuan_pemohon = b.id_pengajuan_pemohon AND b.tahun = ? AND b.bulan = ? $filter_tgl 
                ORDER BY b.tahun DESC, b.bulan DESC, b.tgl DESC, b.no_invoice_pp DESC, b.nomor_pengajuan ASC"; 
        $query = $this->db->query($sql, array($tahun, $bulan));
        $data['result'] = $query->result_array();
        $this->load->view('invoice_pp/invoice-ajax-data', $data);

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

        // set id_pengajuan_pemohon
        // 1. Ambil data ID dari array
        $ids = explode(',', $this->input->post('id_pengajuan_pemohon'));
        // 2. Hilangkan duplikasi jika perlu (opsional)
        $unique_ids = array_unique($ids);
        $id_pengajuan_pemohon = implode(",",$unique_ids);
        $id_pengajuan_pemohon = explode(",", $id_pengajuan_pemohon);
        // 3. Persiapkan array untuk insert_batch
        //echo '<pre>';print_r($id_pengajuan_pemohon); print_r("(".implode(",",$unique_ids).")");echo '</pre>';exit();
        
        // update tabel monitoring
        $data_batch = [];
        $tgl_sekarang = date('Y-m-d');
        
        foreach ($unique_ids as $id) {
            $data_batch[] = array(
                'id_pengajuan_pemohon' => trim($id), // trim untuk memastikan tidak ada spasi
                'no_pp' => $no_pp,
                'tgl_pp' => $tgl_pp,
                'kode_status' => 73
            );
        }
        //echo '<pre>';print_r($data_batch);echo '</pre>';exit();
        // 4. Jalankan insert_batch
        if (!empty($data_batch)) {
            echo 'test';
            $this->db->update_batch('monitoring', $data_batch, 'id_pengajuan_pemohon');
            echo '<pre>'; print_r($data_batch);echo '</pre>';
        }
        
        // update tabel invoice_rekap_procost
        $data = array(
            'no_pp' => $no_pp,
            'tgl_pp' => $tgl_pp
        );
        $this->db->where('no_tiket', $no_tiket);
        $update = $this->db->update('invoice_rekap_procost', $data);

        // update tabel pengajuan_pemohon
        $data_pengajuan_pemohon = array(
            'kode_status' => 73
        );
        $this->db->where_in('id', $id_pengajuan_pemohon);
        $update_pengajuan_pemohon = $this->db->update('pengajuan_pemohon', $data_pengajuan_pemohon);


        // return response
        if($update){
            echo json_encode(array('status' => 'success', 'message' => 'PP updated successfully.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to update PP.'));
        }
    }

    public function update_mdk(){
        $no_tiket = $this->input->post('no_tiket');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $no_mdk = $this->input->post('no_mdk');
        $tgl_mdk = $this->input->post('tgl_mdk');
        
        // set id_pengajuan_pemohon
        // 1. Ambil data ID dari array
        $ids = explode(',', $this->input->post('id_pengajuan_pemohon'));
        // 2. Hilangkan duplikasi jika perlu (opsional)
        $unique_ids = array_unique($ids);
        $id_pengajuan_pemohon = implode(",",$unique_ids);
        $id_pengajuan_pemohon = explode(",", $id_pengajuan_pemohon);
        // 3. Persiapkan array untuk insert_batch
        //echo '<pre>';print_r($id_pengajuan_pemohon); print_r("(".implode(",",$unique_ids).")");echo '</pre>';exit();
        
        // update tabel monitoring
        $data_batch = [];
        $tgl_sekarang = date('Y-m-d');
        
        foreach ($unique_ids as $id) {
            $data_batch[] = array(
                'id_pengajuan_pemohon' => trim($id), // trim untuk memastikan tidak ada spasi
                'no_mdk' => $no_mdk,
                'tgl_mdk' => $tgl_mdk,
                'kode_status' => 72
            );
        }
        //echo '<pre>';print_r($data_batch);echo '</pre>';exit();
        // 4. Jalankan insert_batch
        if (!empty($data_batch)) {
            echo 'test';
            $this->db->update_batch('monitoring', $data_batch, 'id_pengajuan_pemohon');
            echo '<pre>'; print_r($data_batch);echo '</pre>';
        }
        
        // update tabel invoice_rekap_procost
        $data = array(
            'no_mdk' => $no_mdk,
            'tgl_mdk' => $tgl_mdk
        );
        $this->db->where('no_tiket', $no_tiket);
        $update = $this->db->update('invoice_rekap_procost', $data);

        // update tabel pengajuan_pemohon
        $data_pengajuan_pemohon = array(
            'kode_status' => 72
        );
        $this->db->where_in('id', $id_pengajuan_pemohon);
        $update_pengajuan_pemohon = $this->db->update('pengajuan_pemohon', $data_pengajuan_pemohon);


        // return response
        if($update){
            echo json_encode(array('status' => 'success', 'message' => 'MDK updated successfully.'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to update MDK.'));
        }
    }

    public function ajukan_pp(){
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $no_tiket = $this->input->post('no_tiket');

        // update status pengajuan menjadi 72 (Menunggu Pemeriksaan Verifikator UI)
        // ambil id_pengajuan_pemohon berdasarkan no_tiket dari tabel invoice_rekap_procost dan masukkan ke dalam where untuk update status di tabel pengajuan_pemohon dan monitoring
        $sql = "SELECT id_pengajuan_pemohon FROM invoice_rekap_procost WHERE no_tiket = ?";
        $query = $this->db->query($sql, array($no_tiket));
        $result = $query->row();
        $id_pengajuan_pemohon = $result->id_pengajuan_pemohon;

        // update kode_satus ke tabel pengajuan_pemohon dan monitoring menjadi 72 (Menunggu Pemeriksaan Verifikator UI) melalui update batch
        foreach($id_pengajuan_pemohon as $id){
            $update_data[] = array(
                'id_pengajuan_pemohon' => $id,
                'kode_status' => '72'
            );
        }

        if(!empty($update_data)){
            $this->db->update_batch('pengajuan_pemohon', $update_data, 'id');
            $this->db->update_batch('monitoring', $update_data, 'id_pengajuan_pemohon');
        }
        // return response   
        echo json_encode(array('status' => 'success', 'message' => 'PP diajukan successfully.'));
    }
	
	public function status_pengajuan_konfirmasi() {
	    
	    $no_tiket = $this->input->post('no_tiket');
	    //$id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
	    
        $sql = "SELECT b.nomor_pengajuan, b.uraian, b.no_invoice_pp, b.no_tiket, b.tahun, b.bulan, b.tgl, a.kode_dpsj, a.deskripsi_dpsj, a.kode_kegiatan, a.kode_akun, a.kode_dana, a.aktual_report as aktual, a.pph, a.netto, b.id_pengajuan_pemohon as id_pengajuan_pemohon, invoice_status, form, pph_d02, netto_d02
                FROM view_pengajuan_rincian_realisasi a, invoice_rekap_procost b 
                WHERE a.id_pengajuan_pemohon = b.id_pengajuan_pemohon AND b.no_tiket = ?
                ORDER BY b.tahun DESC, b.bulan DESC, b.tgl DESC, b.no_invoice_pp ASC, b.nomor_pengajuan ASC"; 
        $query = $this->db->query($sql, array($no_tiket));
        $data['result'] = $query->result_array();
        
        // set id_pengajuan_pemohon
        foreach ($data['result'] as $row) {
            $array_id_pengajuan_pemohon[] = $row['id_pengajuan_pemohon'];
        }
        $data['array_id_pengajuan_pemohon'] = implode(",", $array_id_pengajuan_pemohon);
        
        // cari keterangan ivoice di tabel monitoring berdasarkan id_pengajuan_pemohon
        $this->db->where('no_tiket', $no_tiket);
        $query = $this->db->get('invoice_rekap_procost');
        $row = $query->row();
        $data['tgl_penyerahan'] = $row->tgl_penyerahan;
        $data['keterangan'] = $row->keterangan;
        
        $this->load->view('invoice_pp/status_pengajuan_konfirmasi', $data); 
	    //echo '<pre>';print_r($sql);print_r($data);echo '</pre>';
	}
	

    public function periode() {
        $sql = "SELECT * FROM periode WHERE lock_data = 0 ORDER BY tahun DESC, bulan DESC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
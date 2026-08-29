<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_update extends CI_Controller {
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
        //echo '<pre>'; print_r($this->input->post()); exit();
        // ambil data dari post
        $tgl = $data['tgl']             = $this->input->post('tgl');
        $bulan = $data['bulan']           = $this->input->post('bulan');
        $tahun = $data['tahun']           = $this->input->post('tahun');
        $data['no_invoice_pp']     = $this->input->post('no_invoice_pp');
        $data['uraian']          = $this->input->post('uraian');
        $data['no_tiket']          = $this->input->post('no_tiket');
        
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
		/*$sql = "SELECT * FROM periode WHERE lock_data = 0 ORDER BY tahun DESC, bulan ";
        $query = $this->db->query($sql);
        $result = $query->result_array();
		foreach ($result as $row){
			$array_tahun_aktif[] = $row['tahun'];
			$array_bulan_aktif[] = $row['bulan'];
		}
		
        $data['array_tahun_aktif'] = array_unique($array_tahun_aktif);
        $data['array_bulan_aktif'] = $array_bulan_aktif;
        */
        $data['tahun_select'] = $this->input->post('tahun');
        $data['bulan_select'] = $this->input->post('bulan');
        //print_r($data['array_tahun_aktif']);print_r($data['array_bulan_aktif']);exit();
        $data['title'] = 'Monitoring Anggaran';
        $data['nama'] = 'test nama';
        
        // ambil kode_status dari input get
        $data['kode_status'] = $this->input->get('kode_status');

        // ambil data status dari tabel status untuk field pum yang bernilai 1
        $sql = "SELECT * FROM status WHERE anggaran = 1";
        $data['status_list'] = $this->db->query($sql)->result_array();

        
        
        $data['title'] = 'Invoice PP';
        $data['nama'] = $this->session->userdata['logged_anggaran']['username'];
        
        $data['data'] = $this->data($bulan, $tahun);
        $data['array_data'] = array('data'=>$this->data($bulan, $tahun));
        
        //$this->load->view('template/header', $data);
        //$this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('anggaran/invoice-tambah-form', $data);        
        //$this->load->view('template/footer');
    }
    
    public function data($bulan, $tahun){
        
        // Tambahkan filter status pada WHERE
        //$where = "WHERE SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan' AND kode_status = 13";
        $where = "WHERE b.no_invoice_pp is null AND SUBSTR(a.nomor_pengajuan, 12, 4) = '$tahun' AND kode_status = 13";
        
        # get records
        $sql = "SELECT a.*, b.no_invoice_pp
				FROM view_pengajuan a
				LEFT JOIN invoice_rekap_procost b
				ON a.id = b.id_pengajuan_pemohon
				$where 
				ORDER BY a.nomor_pengajuan DESC";
        $query = $this->db->query($sql); //$query = $this->db->get();
        
        # get total records
        $sql_count = "SELECT a.id 
				FROM view_pengajuan a
				LEFT JOIN invoice_rekap_procost b
				ON a.id = b.id_pengajuan_pemohon 
				$where";
        $query_count = $this->db->query($sql_count);

        $daftar_pengajuan = array();
        $array_rincian = array();
        $array_monitoring = array();
        $array_monitoring_keterangan = array();
        
        if($query_count->num_rows() > 0){
            $totalRec = count($query_count->result_array());
            
            foreach ($query->result_array() as $rows){
                $array_id_pengajuan_pemohon[] = $rows['id'];                
                $daftar_pengajuan[$rows['id']] = $rows;
            }

            // get id pengajuan pemohon agar bisa digunakan untuk mengambil data rincian sesuai halaman
            $array_value_id = implode(",", $array_id_pengajuan_pemohon);

            
            # get rincian berdasarkan id pengajuan pemohon
            // jika ada id pengajuan pemohon
            if(!empty($array_value_id)) {
                $array_value_id = $array_value_id;
            } else {
                $array_value_id = 0;
            }

            // ambil rincian berdasarkan id pengajuan pemohon
            $sql_rincian = "SELECT * FROM view_pengajuan_rincian_realisasi WHERE id_pengajuan_pemohon IN ($array_value_id)";           
            $query_rincian = $this->db->query($sql_rincian);
            $result_rincian = $query_rincian->result_array();
            if($query_rincian->num_rows() > 0) {
                $array_rincian = array();
                foreach ($result_rincian as $row) {
                    $array_rincian[$row['id_pengajuan_pemohon']][] = $row;
                }
            } else {
                $array_rincian = array();
            }

            // ambil data monitoring -> untuk menampilkan kode_status
            $sql_monitoring = "SELECT kode_status, id_pengajuan_pemohon, anggaran_keterangan_disetujui, nomor_pengajuan, no_pp FROM monitoring WHERE id_pengajuan_pemohon IN ($array_value_id)";
            $query_monitoring = $this->db->query($sql_monitoring);
            if($query_monitoring->num_rows() > 0) {
                $array_monitoring = array();
                foreach ($query_monitoring->result_array() as $row) {
                    $array_monitoring[$row['id_pengajuan_pemohon']] = $row['kode_status'];
                    $array_monitoring_keterangan[$row['id_pengajuan_pemohon']] = $row['anggaran_keterangan_disetujui'] ?? '';
                }
            } else {
                $array_monitoring = array();
            }

        } else {
            $totalRec = 0;
            $array_value_id = 0;
            //$array_petugas = array();
        }

        # pagination
        $data['totalRec'] = $totalRec;
        $data['num_rows'] = $query->num_rows();
		$data['posts'] = $daftar_pengajuan;
        $data['array_rincian'] = $array_rincian;
        $data['sql'] = $sql;
        $data['array_value_id'] = $array_value_id;
        $data['array_monitoring'] = $array_monitoring;
        $data['array_monitoring_keterangan'] = $array_monitoring_keterangan;
        return $data;
        //$this->load->view('anggaran/invoice-ajax-data', $data);

    }
    
    public function konfirmasi()
    {
        //echo '<pre>'; print_r($this->input->post()); echo '</pre>';exit();
        $no_tiket = $this->input->post('no_tiket');
        $no_invoice_pp = $this->input->post('no_invoice_pp');
        $uraian = $this->input->post('uraian');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $id_pengajuan_pemohon = implode(",", $id_pengajuan_pemohon); 
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $tgl = $this->input->post('tgl');
        
        $sql ="SELECT nomor_pengajuan, kode_dpsj, deskripsi_dpsj, kode_kegiatan, kode_akun, kode_dana, uraian as untuk, aktual_report as aktual, pph, netto, form, id_pengajuan_pemohon, form, id
        FROM view_pengajuan_rincian_realisasi 
        WHERE id_pengajuan_pemohon IN ( $id_pengajuan_pemohon )"; 
                
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $data['result'] = $result;        
		$data['no_invoice_pp'] = $no_invoice_pp;
        $data['uraian'] = $uraian;
		$data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;    
        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;
        $data['tgl'] = $tgl;
        $data['no_tiket'] = $no_tiket;
        $data['head_invoice'] = $tgl.'-'.$bulan.'-'.$tahun;
        //echo '<pre>'; print_r($data); echo '</pre>';exit();
        $this->load->view('anggaran/invoice-tambah-form-selected', $data);
        //echo '<pre>'; print_r($data); echo '</pre>';exit();
        
    }
    
    public function simpan_procost()
    {
        //echo '<pre>'; print_r($this->input->post()); echo '</pre>';exit();
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $tgl = $this->input->post('tgl');
        $no_invoice_pp = $this->input->post('no_invoice_pp');
        $uraian = $this->input->post('uraian');
        $no_tiket = $this->input->post('no_tiket');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $tax_data = $this->input->post('tax_data');
        $net_data = $this->input->post('net_data');
        
        $data['no_invoice_pp'] = $no_invoice_pp;
        $data['uraian'] = $uraian;
		$data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;    
        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;
        $data['tgl'] = $tgl;
        $data['no_tiket'] = $no_tiket;
        $data['head_invoice'] = $tgl.'-'.$bulan.'-'.$tahun;
        
        $sql = "SELECT id_pengajuan_pemohon, nomor_pengajuan
                FROM view_search_pengajuan 
                WHERE id_pengajuan_pemohon IN ($id_pengajuan_pemohon) ";
        
        $query = $this->db->query($sql);
        $data_procost = $query->result_array();
        //echo '<pre>'; print_r($data_procost); echo '</pre>';exit();
        foreach ($data_procost as $row) {
            $data = array(
                'no_tiket' => $no_tiket,
                'no_invoice_pp' => $no_invoice_pp,
                'uraian' => $uraian,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'tgl' => $tgl,
                'id_pengajuan_pemohon' => $row['id_pengajuan_pemohon'],
                'nomor_pengajuan' => $row['nomor_pengajuan']
            );
            $this->db->insert('invoice_rekap_procost', $data);
        }
        
        // update tax dan netto di tabel pengajuan_rincian
        $update = [];
        //echo '<pre>'; print_r($data); print_r($tax_data); print_r($net_data);echo '</pre>';exit();
        // looping salah satu array (misal pph) untuk menyusun data batch update
        if($tax_data){
            foreach ($tax_data as $key => $value) {
                $update[] = array(
                    'id' => $key,
                    'pph_d02' => $value,
                    'netto_d02' => $net_data[$key]
                );
            }
            
            //print_r($update);
            // update tabel pengajuan_rincian dengan data pph dan netto yang baru
            if (!empty($update)) {
                $this->db->update_batch('pengajuan_rincian', $update, 'id');
                
                // Cek jumlah baris yang terupdate
                $affected_rows = $this->db->affected_rows();
                echo "Updated rows: " . $affected_rows;
            }
        }
        //echo '<pre>'; print_r($data); echo '</pre>';exit();
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends CI_Controller {
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
        $this->load->view('anggaran/invoice-ajax-index', $data);        
        $this->load->view('template/footer');
	}

    public function form() 
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
        $this->load->view('anggaran/invoice_form', $data);        
        $this->load->view('template/footer');

	}

    public function search_pengajuan() {
		$tahun = $this->input->post('tahun');
		$bulan = $this->input->post('bulan');
        //$sql = "SELECT * FROM view_search_pengajuan WHERE tahun = ? AND bulan = ? ";
		$sql = "SELECT * FROM view_yunior_akuntan WHERE tahun = ? AND bulan = ? AND kode_status = '13'";
        $query = $this->db->query($sql, array($tahun, $bulan));
        $data['result'] = $query->result_array();
        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;
		$data['sql'] = $sql;
		$this->load->view('anggaran/invoice_search_pengajuan', $data);
    }

    public function get_data_procost() {
        $no_invoice_pp = $this->input->post('no_invoice_pp');
        $uraian = $this->input->post('uraian');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $id_pengajuan_pemohon = implode(",", $id_pengajuan_pemohon); 
        /*$sql = "SELECT b.nomor_pengajuan, a.kode_dpsj, a.deskripsi_dpsj, a.kode_kegiatan, a.kode_akun, a.kode_dana, b.untuk, a.aktual, a.pph, a.netto
                FROM view_pengajuan_rincian_realisasi a, pengajuan_pemohon b 
                WHERE a.id_pengajuan_pemohon = b.id AND a.id_pengajuan_pemohon IN ($id_pengajuan_pemohon) ";
		$sql = "SELECT b.nomor_pengajuan, a.kode_dpsj, a.deskripsi_dpsj, a.kode_kegiatan, a.kode_akun, a.kode_dana, b.untuk, a.aktual, a.pph, a.netto
                FROM view_anggaran_mutasi a, pengajuan_pemohon b 
                WHERE a.id_pengajuan_pemohon = b.id AND a.id_pengajuan_pemohon IN ($id_pengajuan_pemohon) ";*/
        $sql ="SELECT nomor_pengajuan, kode_dpsj, deskripsi_dpsj, kode_kegiatan, kode_akun, kode_dana, uraian as untuk, aktual, pph, netto 
        FROM view_pengajuan_rincian_realisasi 
        WHERE id_pengajuan_pemohon IN ($id_pengajuan_pemohon)"; 
                
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $data['result'] = $result;        
		$data['no_invoice_pp'] = $no_invoice_pp;
        $data['uraian'] = $uraian;
		$data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;
		$this->load->view('anggaran/invoice_form_data_procost', $data);
    }

    public function form_data_procost() {
        $no_invoice_pp = $this->input->post('no_invoice_pp');
        $uraian = $this->input->post('uraian');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $id_pengajuan_pemohon = implode(",", $id_pengajuan_pemohon); 
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        
        $sql ="SELECT nomor_pengajuan, kode_dpsj, deskripsi_dpsj, kode_kegiatan, kode_akun, kode_dana, uraian as untuk, aktual_report as aktual, pph, netto 
        FROM view_pengajuan_rincian_realisasi 
        WHERE id_pengajuan_pemohon IN ($id_pengajuan_pemohon)"; 
                
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $data['result'] = $result;        
		$data['no_invoice_pp'] = $no_invoice_pp;
        $data['uraian'] = $uraian;
		$data['id_pengajuan_pemohon'] = $id_pengajuan_pemohon;    
        $data['tahun'] = $tahun;
        $data['bulan'] = $bulan;    
            
        $data['title'] = 'Invoice PP';
        $data['nama'] = $this->session->userdata['logged_anggaran']['username'];
        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => menu()));
        $this->load->view('anggaran/invoice_form_selected', $data);        
        $this->load->view('template/footer');
    }

    public function simpan_rekap_procost() {
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $tgl = $this->input->post('tgl');
        $no_invoice_pp = $this->input->post('no_invoice_pp');
        $uraian = $this->input->post('uraian');
        $no_tiket = $this->input->post('no_tiket');
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $tax_data = $this->input->post('tax_data');
        $net_data = $this->input->post('net_data');

        $sql = "SELECT id_pengajuan_pemohon, nomor_pengajuan
                FROM view_search_pengajuan 
                WHERE id_pengajuan_pemohon IN ($id_pengajuan_pemohon) ";
                
        $query = $this->db->query($sql);
        $data_procost = $query->result_array();

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

			// update monitoring
			$id_pengajuan_pemohon = $row['id_pengajuan_pemohon'];
			$sql = "UPDATE monitoring SET no_invoice_pp = '$no_invoice_pp' WHERE id_pengajuan_pemohon = $id_pengajuan_pemohon ";
			$this->db->query($sql);

            // Update realisasi table with pph and netto values
            if (isset($tax_data[$id_pengajuan_pemohon]) && isset($net_data[$id_pengajuan_pemohon])) {
                $pph = floatval($tax_data[$id_pengajuan_pemohon]);
                $netto = floatval($net_data[$id_pengajuan_pemohon]);
                
                $this->db->where('id_pengajuan_pemohon', $id_pengajuan_pemohon);
                $this->db->update('realisasi', array(
                    'pph' => $pph,
                    'netto' => $netto
                ));
            }
            //print_r($data); exit;
        }
        
        //redirect('invoice');

        /*$data_procost = json_decode($this->input->post('data_procost'), true);

        foreach ($data_procost as $row) {
            $data = array(
                'no_invoice_pp' => $no_invoice_pp,
                'uraian' => $uraian,
                'nomor_pengajuan' => $row['nomor_pengajuan'],
                'kode_dpsj' => $row['kode_dpsj'],
                'deskripsi_dpsj' => $row['deskripsi_dpsj'],
                'kode_kegiatan' => $row['kode_kegiatan'],
                'kode_akun' => $row['kode_akun'],
                'kode_dana' => $row['kode_dana'],
                'untuk' => $row['untuk'],
                'aktual' => $row['aktual'],
                'pph' => $row['pph'],
                'netto' => $row['netto']
            );
            $this->db->insert('invoice_rekap_procost', $data);
        }

        echo json_encode(array('status' => 'success'));*/
    }

    public function data(){
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        $conditions = array();
        $conditions['tahun'] = $tahun;
        $conditions['bulan'] = $bulan;
        $data['title'] = 'Invoice PP';
        $data['nama'] = $this->session->userdata['logged_anggaran']['username'];
        $data['perPage'] = $this->perPage;
        $sql = "SELECT b.nomor_pengajuan, b.uraian, b.no_invoice_pp, b.no_tiket, b.tahun, b.bulan, b.tgl, a.kode_dpsj, a.deskripsi_dpsj, a.kode_kegiatan, a.kode_akun, a.kode_dana, a.aktual, a.pph, a.netto, b.id_pengajuan_pemohon as id_pengajuan_pemohon, invoice_status
                FROM view_pengajuan_rincian_realisasi a, invoice_rekap_procost b 
                WHERE a.id_pengajuan_pemohon = b.id_pengajuan_pemohon AND b.tahun = ? AND b.bulan = ? ";
        $query = $this->db->query($sql, array($tahun, $bulan));
        $data['result'] = $query->result_array();
        $this->load->view('anggaran/invoice-ajax-data', $data);

    }
    public function delete(){
		$no_tiket = $this->input->post('no_tiket');

        // delete dari tabel invoice_rekap_procost
		$sql = "DELETE FROM invoice_rekap_procost WHERE no_tiket = $no_tiket";
		$this->db->query($sql);

        // update monitoring, kosongkan no_invoice_pp
        $sql = "UPDATE monitoring SET no_invoice_pp = NULL WHERE id_pengajuan_pemohon IN (SELECT id_pengajuan_pemohon FROM invoice_rekap_procost WHERE no_tiket = $no_tiket)";
        $this->db->query($sql);
	}

    public function send_to_akuntan() {
        // mengupdate tgl_pemberian_dokumen_ke_junior_akuntan dan kode_status pada tabel monitoring
        $id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
        $data = array(
            'tgl_pemberian_dokumen_ke_junior_akuntan' => date('Y-m-d'),
            'kode_status' => 71
        );
        $this->db->where('id_pengajuan_pemohon', $id_pengajuan_pemohon);
        $this->db->update('monitoring', $data);
        
        // update invoice_rekap_procost, set status_invoice = 1 dan tgl_penyerahan (dikirim ke akuntan)
        $data = array(
            'invoice_status' => 1,
            'tgl_penyerahan' => date('Y-m-d')
        );
        $this->db->where('id_pengajuan_pemohon', $id_pengajuan_pemohon);
        $this->db->update('invoice_rekap_procost', $data);

        //echo json_encode(array('status' => 'success'));
    }

    public function periode() {
        $sql = "SELECT * FROM periode WHERE lock_data = 0 ORDER BY tahun DESC, bulan DESC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    /******************************************************************************************************************** */

    public function search() {
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

        $data['title'] = 'Monitoring Anggaran';
        $data['nama'] = 'test nama';
        
        // ambil kode_status dari input get
        $data['kode_status'] = $this->input->get('kode_status');

        // ambil data status dari tabel status untuk field pum yang bernilai 1
        $sql = "SELECT * FROM status WHERE anggaran = 1";
        $data['status_list'] = $this->db->query($sql)->result_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => $this->menu()) );
        $this->load->view('anggaran/invoice-search-index', $data);        
        $this->load->view('template/footer');

    }

    public function search_data() {

		$tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');
        
        // Tambahkan filter status pada WHERE
        //$where = "WHERE SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND SUBSTR(nomor_pengajuan, 9, 2) = '$bulan' AND kode_status = 13";
        $where = "WHERE SUBSTR(nomor_pengajuan, 12, 4) = '$tahun' AND kode_status = 13";
        
        # get records
        $sql = "SELECT * FROM view_pengajuan $where ORDER BY nomor_pengajuan DESC";
        $query = $this->db->query($sql); //$query = $this->db->get();
        
        # get total records
        $sql_count = "SELECT id FROM view_pengajuan $where";
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
        //echo '<pre>';print_r($data['posts']);echo '</pre>'; exit();
        # load the view
        $this->load->view('anggaran/invoice-search-data', $data, false);
    }

	public function menu()
	{		
		$sql = "SELECT * FROM menu where anggaran = 1 order by parent, sort";
		$query = $this->db->query($sql);
		$menu = $query->result_array();
		return $menu;
	}

}
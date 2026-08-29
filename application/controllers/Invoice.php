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
		$sql = "SELECT * FROM view_yunior_akuntan WHERE tahun = ? AND bulan = ? ";
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
        
        $sql ="SELECT nomor_pengajuan, kode_dpsj, deskripsi_dpsj, kode_kegiatan, kode_akun, kode_dana, uraian as untuk, aktual_report as aktual, pph, netto, form, id_pengajuan_pemohon, form, id
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
            //print_r($data); exit;
        }
        
        // update tax dan netto di tabel pengajuan_rincian
        $update = [];
        //print_r($tax_data); print_r($net_data);
        // looping salah satu array (misal pph) untuk menyusun data batch update
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
        
        $sql = "SELECT b.nomor_pengajuan, b.uraian, b.no_invoice_pp, b.no_tiket, b.tahun, b.bulan, b.tgl, a.kode_dpsj, a.deskripsi_dpsj, a.kode_kegiatan, a.kode_akun, a.kode_dana, a.aktual_report as aktual, a.pph, a.netto, b.id_pengajuan_pemohon as id_pengajuan_pemohon, invoice_status, form, pph_d02, netto_d02, netto_d01_d02
                FROM view_pengajuan_rincian_realisasi a, invoice_rekap_procost b 
                WHERE a.id_pengajuan_pemohon = b.id_pengajuan_pemohon AND b.tahun = ? AND b.bulan = ? $filter_tgl 
                ORDER BY b.tahun DESC, b.bulan DESC, b.tgl DESC, b.no_invoice_pp DESC, b.nomor_pengajuan ASC"; 
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
	
	public function send_to_akuntan_konfirmasi() {
	    
	    $no_tiket = $this->input->post('no_tiket');
	    //$id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
	    
        $sql = "SELECT b.nomor_pengajuan, b.uraian, b.no_invoice_pp, b.no_tiket, b.tahun, b.bulan, b.tgl, a.kode_dpsj, a.deskripsi_dpsj, a.kode_kegiatan, a.kode_akun, a.kode_dana, a.aktual_report as aktual, a.pph, a.netto, b.id_pengajuan_pemohon as id_pengajuan_pemohon, invoice_status, form, pph_d02, netto_d02, netto_d01_d02
                FROM view_pengajuan_rincian_realisasi a, invoice_rekap_procost b 
                WHERE a.id_pengajuan_pemohon = b.id_pengajuan_pemohon AND b.no_tiket = ?
                ORDER BY b.tahun DESC, b.bulan DESC, b.tgl DESC, b.no_invoice_pp DESC, b.nomor_pengajuan ASC"; 
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
        
        $this->load->view('anggaran/invoice_send_to_akuntan_konfirmasi', $data); 
	    //echo '<pre>';print_r($sql);print_r($data);echo '</pre>';
	}

    public function send_to_akuntan() {
        // mengupdate tgl_pemberian_dokumen_ke_junior_akuntan dan kode_status pada tabel monitoring
        // 1. Ambil data ID dari array
        $ids = explode(',', $this->input->post('id_pengajuan_pemohon'));
        
        // 2. Hilangkan duplikasi jika perlu (opsional)
        $unique_ids = array_unique($ids);
        
        // 3. Persiapkan array untuk insert_batch
        $data_batch = [];
        $tgl_sekarang = date('Y-m-d');
        
        foreach ($unique_ids as $id) {
            $data_batch[] = array(
                'id_pengajuan_pemohon' => trim($id), // trim untuk memastikan tidak ada spasi
                'tgl_pemberian_dokumen_ke_junior_akuntan' => $tgl_sekarang,
                'kode_status' => 71
            );
        }

        // 4. Jalankan insert_batch
        if (!empty($data_batch)) {
            //echo 'test';
            $this->db->update_batch('monitoring', $data_batch, 'id_pengajuan_pemohon');
            //echo '<pre>'; print_r($data_batch);echo '</pre>';
        }
        
        // update invoice_rekap_procost, set status_invoice = 1 dan tgl_penyerahan (dikirim ke akuntan)
        $data = array(
            'invoice_status' => 1,
            'tgl_penyerahan' => date('Y-m-d')
        );
        $this->db->where('no_tiket', $this->input->post('no_tiket'));
        $this->db->update('invoice_rekap_procost', $data);
        
        if ($this->db->affected_rows() > 0) {
            echo "Data berhasil diupdate! ";
            $affected_rows = $this->db->affected_rows();
            echo "Updated rows: " . $affected_rows;
        } else {
            echo "Tidak ada data yang diupdate (mungkin no_tiket tidak ditemukan).";
        }
        //echo $this->db->last_query();
    }

    // konfirmasi batal kirim ke akuntan
    public function send_to_akuntan_konfirmasi_batalkan() {
        
	    $no_tiket = $this->input->post('no_tiket');
	    //$id_pengajuan_pemohon = $this->input->post('id_pengajuan_pemohon');
	    
        $sql = "SELECT b.nomor_pengajuan, b.uraian, b.no_invoice_pp, b.no_tiket, b.tahun, b.bulan, b.tgl, a.kode_dpsj, a.deskripsi_dpsj, a.kode_kegiatan, a.kode_akun, a.kode_dana, a.aktual_report as aktual, a.pph, a.netto, b.id_pengajuan_pemohon as id_pengajuan_pemohon, invoice_status, form, pph_d02, netto_d02, netto_d01_d02
                FROM view_pengajuan_rincian_realisasi a, invoice_rekap_procost b 
                WHERE a.id_pengajuan_pemohon = b.id_pengajuan_pemohon AND b.no_tiket = ?
                ORDER BY b.tahun DESC, b.bulan DESC, b.tgl DESC, b.no_invoice_pp DESC, b.nomor_pengajuan ASC"; 
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

        $this->load->view('manajer/invoice_send_to_akuntan_konfirmasi_batalkan', $data);
    }

    // batalkan send_to_akuntan 
    public function send_to_akuntan_batalkan() {
        // update monitoring, set kode_status = 13 dan kosongkan tgl_pemberian_dokumen_ke_junior_akuntan
        $ids = explode(',', $this->input->post('id_pengajuan_pemohon'));
        $unique_ids = array_unique($ids);
        $data_batch = [];
        
        foreach ($unique_ids as $id) {
            $data_batch[] = array(
                'id_pengajuan_pemohon' => trim($id),
                'tgl_pemberian_dokumen_ke_junior_akuntan' => NULL,
                'kode_status' => 13
            );
        }

        if (!empty($data_batch)) {
            $this->db->update_batch('monitoring', $data_batch, 'id_pengajuan_pemohon');
        }
        
        // update invoice_rekap_procost, set status_invoice = 0 dan kosongkan tgl_penyerahan
        $data = array(
            'invoice_status' => 0,
            'tgl_penyerahan' => NULL
        );
        $this->db->where('no_tiket', $this->input->post('no_tiket'));
        $this->db->update('invoice_rekap_procost', $data);
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
        //echo '<pre>';print_r($data['posts']);echo '</pre>'; exit();
        # load the view
        $this->load->view('anggaran/invoice-search-data', $data, false);
    }

    public function periode() {
        $sql = "SELECT * FROM periode WHERE lock_data = 0 ORDER BY tahun DESC, bulan DESC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

	public function menu()
	{		
		$sql = "SELECT * FROM menu where anggaran = 1 order by parent, sort";
		$query = $this->db->query($sql);
		$menu = $query->result_array();
		return $menu;
	}
	
	public function edit_form()
	{		
	    $no_tiket = $this->input->post('no_tiket');
	    
	    $query = $this->db->distinct()
                  ->select('tgl, bulan, tahun, no_invoice_pp, uraian')
                  ->where('no_tiket', $no_tiket)
                  ->get('invoice_rekap_procost');

        foreach ($query->result() as $row) {
            $data['no_tiket'] = $no_tiket;
            $data['tgl'] = $row->tgl;    // Mengakses kolom tgl
            $data['bulan'] = $row->bulan;  // Mengakses kolom bulan
            $data['tahun'] = $row->tahun;  // Mengakses kolom tahun
            $data['no_invoice_pp'] = $row->no_invoice_pp; // Mengakses no_invoice_pp
            $data['uraian'] = $row->uraian; // Mengakses kolom uraian
        }

        
        $this->load->view('anggaran/invoice-edit-index', $data);  
	}
	
	public function edit_simpan(){
	    
        // Ambil data dari POST
        $no_tiket = $this->input->post('no_tiket');
        
        $data = array(
            'tgl'             => $this->input->post('tgl'),
            'bulan'           => $this->input->post('bulan'),
            'tahun'           => $this->input->post('tahun'),
            'no_invoice_pp'   => $this->input->post('no_invoice_pp'),
            'uraian'          => $this->input->post('uraian')
        );
    
        // Update berdasarkan no_tiket
        $this->db->where('no_tiket', $no_tiket);
        $update = $this->db->update('invoice_rekap_procost', $data);
    
        if ($update) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate', 'no_invoice_pp' => $this->input->post('no_invoice_pp'), 'uraian' => $this->input->post('uraian') ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal update data']);
        }
	
	}

    public function tambah_form(){
        
        $no_tiket = $this->input->post('no_tiket');
        
        $sql = "SELECT b.nomor_pengajuan, b.uraian, b.no_invoice_pp, b.no_tiket, b.tahun, b.bulan, b.tgl, a.kode_dpsj, a.deskripsi_dpsj, a.kode_kegiatan, a.kode_akun, a.kode_dana, a.aktual_report as aktual, a.pph, a.netto, b.id_pengajuan_pemohon as id_pengajuan_pemohon, invoice_status, form, pph_d02, netto_d02
                FROM view_pengajuan_rincian_realisasi a, invoice_rekap_procost b 
                WHERE a.id_pengajuan_pemohon = b.id_pengajuan_pemohon AND b.no_tiket = ?
                ORDER BY b.tahun DESC, b.bulan DESC, b.tgl DESC, b.no_invoice_pp ASC, b.nomor_pengajuan ASC"; 
        $query = $this->db->query($sql, array($no_tiket));
        $data['result'] = $query->result_array();
        
        $this->load->view('anggaran/invoice-tambah-index', $data);  
    }
}
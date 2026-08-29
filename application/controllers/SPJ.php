<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SPJ extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Anggaran_model');
        $this->load->model('Rka_model');
        $this->load->library('form_validation');
        $this->load->helper('tanggal_helper');
        $this->load->helper('status_helper');
		$this->load->helper('url');
        $this->load->helper('menu_helper');
		$this->load->library('session');
		$this->load->library('Ajax_pagination_realisasi');
		$this->perPage = 2;
    }
    

    public function edit_view()
    {
        
		// get id
        $id = $this->input->post('id');
        
        if (!$id) {
            // If no ID is provided, redirect to the index page
            //redirect('realisasi');
        }

        // get nomor_pengajuan
        $nomor_pengajuan = $this->input->post('nomor_pengajuan');
        if (!$nomor_pengajuan) {
            // If no nomor_pengajuan is provided, redirect to the index page
            //redirect('realisasi');
        }

        $data['id'] = $id; 
        $data['nomor_pengajuan'] = $nomor_pengajuan;  
        $sql = "SELECT * FROM pengajuan_rincian WHERE id = ?";
        $query = $this->db->query($sql, array($id));
        $result = $query->result_array();

        // retrieve realisasi data
        $sql_realisasi = "SELECT * FROM realisasi WHERE id_pengajuan_rincian = ?";
        $query_realisasi = $this->db->query($sql_realisasi, array($id));
        $result_realisasi = $query_realisasi->result_array();

        $data['sql'] = $sql;
        $data['result'] = $result;        
        $data['sql_realisasi'] = $sql_realisasi;
        $data['result_realisasi'] = $result_realisasi;
        $data['nominal_pengajuan'] = $this->input->post('nominal_pengajuan');
        //$data['keterangan'] = $this->input->post('keterangan');
        
        // Load the form for creating a new realisasi
		$this->load->view('unit_kerja/spj_view', $data);
		//echo '<pre>';print_r($data); echo '</pre>';
    }

    /**
     * Update single field in rincian table
     */
    public function update_rincian() {
        // Set header untuk JSON response
        $this->output->set_content_type('application/json');
        
        // Get POST data
        $id = $this->input->post('id');
        $field = $this->input->post('field');
        $value = $this->input->post('value');
        $id_pengajuan_rincian = $this->input->post('id_pengajuan_rincian');
        
        // Validasi
        if (empty($id) || empty($field)) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Invalid parameters'
            ]));
            return;
        }
        
        // Mapping field yang diizinkan
        $allowed_fields = ['tanggal', 'keterangan', 'volume', 'ket_volume', 'harga', 
                          'bruto', 'persen_pajak', 'pph', 'netto'];
        
        if (!in_array($field, $allowed_fields)) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Invalid field'
            ]));
            return;
        }
        
        // Escape value untuk keamanan
        $value = $this->db->escape_str($value);
        
        // Update data
        $this->db->trans_start();
        
        $this->db->where('id', $id);
        $this->db->where('id_pengajuan_rincian', $id_pengajuan_rincian);
        $this->db->update('realisasi', [$field => $value]);
        
        // Jika update berhasil
        if ($this->db->affected_rows() > 0) {
            // Jika field yang diupdate mempengaruhi perhitungan, update field terkait
            if (in_array($field, ['volume', 'harga', 'persen_pajak'])) {
                $this->recalculate_rincian($id);
            }
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Failed to update data',
                'sql_query' => $this->db->last_query()
            ]));
        } else {
            $this->output->set_output(json_encode([
                'status' => 'success',
                'message' => 'Data updated successfully',
                'sql_query' => $this->db->last_query()
            ]));
        }
    }
    
    /**
     * Recalculate bruto, pph, and netto for a row
     */
    private function recalculate_rincian($id) {
        // Get current row data
        $row = $this->db->get_where('realisasi', ['id' => $id])->row_array();
        
        if (!$row) {
            return;
        }
        
        $volume = floatval($row['volume']);
        $harga = floatval($row['harga']);
        $persen_pajak = floatval($row['persen_pajak']);
        
        // Calculate
        $bruto = $volume * $harga;
        $pph = $bruto * ($persen_pajak / 100);
        $netto = $bruto - $pph;
        
        // Update
        $this->db->where('id', $id);
        $this->db->update('realisasi', [
            'bruto' => $bruto,
            'pph' => $pph,
            'netto' => $netto
        ]);
    }
    
    /**
     * Save new rincian data
     */
    public function simpan_rincian() {
        $this->output->set_content_type('application/json');
        
        // Get POST data
        $id_pengajuan_rincian = $this->input->post('id_pengajuan_rincian');
        $tanggal = $this->input->post('tanggal');
        $keterangan = $this->input->post('keterangan');
        $volume = $this->input->post('volume');
        $ket_volume = $this->input->post('ket_volume');
        $harga = $this->input->post('harga');
        $bruto = $this->input->post('bruto');
        $persen_pajak = $this->input->post('persen_pajak');
        $pph = $this->input->post('pph');
        $netto = $this->input->post('netto');
        $kegiatan = $this->input->post('kegiatan');
        $jadwal = $this->input->post('jadwal');
        $komitmen = $this->input->post('komitmen');
        
        // Validasi
        if (empty($tanggal) || empty($keterangan)) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Tanggal dan Keterangan harus diisi'
            ]));
            return;
        }
        
        // Get data from pengajuan_rincian
        $pengajuan = $this->db->get_where('pengajuan_rincian', ['id' => $id_pengajuan_rincian])->row_array();
        
        if (!$pengajuan) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Data pengajuan tidak ditemukan'
            ]));
            return;
        }
        
        // Prepare data
        $data = [
            'tahun_anggaran' => $pengajuan['tahun_anggaran'] ?? date('Y'),
            'tahun' => date('Y', strtotime($tanggal)),
            'bulan' => date('m', strtotime($tanggal)),
            'nomor_pengajuan' => $pengajuan['nomor_pengajuan'],
            'kode_dpsj' => $pengajuan['kode_dpsj'] ?? null,
            'deskripsi_dpsj' => $pengajuan['deskripsi_dpsj'] ?? null,
            'kode_kegiatan' => $pengajuan['kode_kegiatan'],
            'nama_kegiatan' => $pengajuan['nama_kegiatan'],
            'kode_akun' => $pengajuan['kode_akun'],
            'deskripsi_akun' => $pengajuan['deskripsi_akun'],
            'kode_dana' => $pengajuan['kode_dana'],
            'tanggal' => $this->tanggalToDb($tanggal),
            'keterangan' => $keterangan,
            'volume' => $volume,
            'ket_volume' => $ket_volume,
            'harga' => $harga,
            'bruto' => $bruto,
            'persen_pajak' => $persen_pajak,
            'pph' => $pph,
            'netto' => $netto,
            'tgl_sistem' => date('Y-m-d H:i:s'),
            'username' => $this->session->userdata('username'),
            'id_pengajuan_rincian' => $id_pengajuan_rincian,
            'flag_cek' => 0,
            'id_pengajuan_pemohon' => $pengajuan['id_pengajuan_pemohon'],
            'flag_disetujui' => 0
        ];
        
        $this->db->trans_start();
        
        $this->db->insert('realisasi', $data);
        $insert_id = $this->db->insert_id();
        
        // Update komitmen di pengajuan_rincian jika ada
        if (!empty($komitmen)) {
            $this->db->where('id', $id_pengajuan_rincian);
            $this->db->update('pengajuan_rincian', ['komitmen' => $komitmen]);
        }
        
        // Update kegiatan dan jadwal jika ada
        if (!empty($kegiatan)) {
            $this->db->where('id', $id_pengajuan_rincian);
            $this->db->update('pengajuan_rincian', ['kegiatan' => $kegiatan]);
        }
        
        if (!empty($jadwal)) {
            $this->db->where('id', $id_pengajuan_rincian);
            $this->db->update('pengajuan_rincian', ['jadwal' => $jadwal]);
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Failed to save data'
            ]));
        } else {
            $this->output->set_output(json_encode([
                'status' => 'success',
                'message' => 'Data saved successfully',
                'id' => $insert_id
            ]));
        }
    }
    
    /**
     * Delete rincian data
     */
    public function hapus_rincian() {
        $this->output->set_content_type('application/json');
        
        $id = $this->input->post('id');
        $id_pengajuan_rincian = $this->input->post('id_pengajuan_rincian');
        
        if (empty($id)) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Invalid ID'
            ]));
            return;
        }
        
        $this->db->trans_start();
        
        $this->db->where('id', $id);
        $this->db->where('id_pengajuan_rincian', $id_pengajuan_rincian);
        $this->db->delete('realisasi');
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Failed to delete data'
            ]));
        } else {
            $this->output->set_output(json_encode([
                'status' => 'success',
                'message' => 'Data deleted successfully'
            ]));
        }
    }
    
    /**
     * Update pengajuan rincian field
     */
    public function update_pengajuan_rincian() {
        $this->output->set_content_type('application/json');
        
        $id = $this->input->post('id');
        $field = $this->input->post('field');
        $value = $this->input->post('value');
        
        $allowed_fields = ['kegiatan', 'jadwal'];
        
        if (!in_array($field, $allowed_fields)) {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Invalid field'
            ]));
            return;
        }
        
        $this->db->where('id', $id);
        $this->db->update('pengajuan_rincian', [$field => $value]);
        
        if ($this->db->affected_rows() > 0) {
            $this->output->set_output(json_encode([
                'status' => 'success',
                'message' => 'Data updated successfully'
            ]));
        } else {
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => 'Failed to update data'
            ]));
        }
    }
    
    /**
     * Get total bruto and netto
     */
    public function get_totals() {
        $this->output->set_content_type('application/json');
        
        $id_pengajuan_rincian = $this->input->post('id_pengajuan_rincian');
        
        $this->db->select('SUM(bruto) as total_bruto, SUM(netto) as total_netto');
        $this->db->where('id_pengajuan_rincian', $id_pengajuan_rincian);
        $result = $this->db->get('realisasi')->row_array();
        
        $this->output->set_output(json_encode([
            'status' => 'success',
            'data' => $result
        ]));
    }

    public function spj_rincian_tbody() {
       
		// get id
        $id = $this->input->post('id');
        
        if (!$id) {
            // If no ID is provided, redirect to the index page
            //redirect('realisasi');
        }

        // retrieve realisasi data
        $sql_realisasi = "SELECT * FROM realisasi WHERE id_pengajuan_rincian = ?";
        $query_realisasi = $this->db->query($sql_realisasi, array($id));
        $result_realisasi = $query_realisasi->result_array();
        $data['result_realisasi'] = $result_realisasi;
        
        // buat tbody rincian
		$html = $this->load->view('unit_kerja/spj_rincian_tbody', $data, true);

        $this->output->set_output($html);
    }

    function tanggalToDb($tgl_kegiatan)
	{
		$bulan = array('Januari','Februari','Maret','April','Mei', 'Juni','Juli','Agustus','September','Oktober','November','Desember');
		$tgl_array = explode(" ", $tgl_kegiatan);
		$d = $tgl_array[0];
		$month = array_search($tgl_array[1], $bulan)+1;
		$m = (strlen($month)==2) ? $month : '0'.$month; 
		$y = $tgl_array[2];
		$tgl = $y."-".$m."-".$d;
		$tgl_kegiatan = $tgl;
		return $tgl;
	}

    function dbToTanggal($tanggal)
	{
		if ($tanggal=='0000-00-00') {
			$tanggal = '';
		} else {
			$array = explode('-', $tanggal);
			//set tanggal
	        $d = $array[2];
	        $m = $array[1];
	        $y = $array[0];
			//set hari
			$nama_hari = array( 0 => 'Minggu', '1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu' );
			$kd_hari = date("w", mktime(0, 0, 0, $m, $d, $y));
			$hari = $nama_hari[$kd_hari];
			//set bulan
			$nama_bulan = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
			$bulan = $nama_bulan[$m];
	        $tanggal_hari = $hari.', '.$d.' '.$bulan.' '.$y;
	        $tanggal = $d.' '.$bulan.' '.$y;
		}

        return $tanggal;
	}	
}
?>
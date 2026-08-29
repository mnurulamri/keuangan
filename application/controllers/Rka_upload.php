<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rka_upload extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Rka_model');
        $this->load->library('upload');
        $this->load->library('PhpExcel'); // Load library Excel
        $this->load->helper(array('form', 'url'));     
        $this->load->helper('status_helper');
		$this->load->library('session');
        
        // Cek login
        if (!$this->session->userdata('logged_anggaran')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['title'] = 'Data RKA';
        $data['rka'] = $this->Rka_model->get_all();
        $data['content'] = 'rka/upload_index';
        $data['nama'] = 'test nama';

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => $this->menu()) );
        $this->load->view('template/adminlte', $data);      
        $this->load->view('template/footer');
    }

	public function menu()
	{		
		$sql = "SELECT * FROM menu where anggaran = 1 order by parent, sort";
		$query = $this->db->query($sql);
		$menu = $query->result_array();
		return $menu;
	}

    public function upload() {
        $data['title'] = 'Upload RKA Excel';
        $data['content'] = 'rka/upload';
        $data['nama'] = 'test nama';

        $this->load->view('template/header', $data);
        $this->load->view('template/tes_menu', array('menu' => $this->menu()) );
        $this->load->view('template/adminlte', $data);      
        $this->load->view('template/footer');
    }

    public function do_upload() {
        $config['upload_path'] = './uploads/rka/';
        $config['allowed_types'] = 'xlsx|xls';
        $config['max_size'] = 10240; // 10MB
        $config['file_name'] = 'rka_'.date('Ymd_His');

        $this->upload->initialize($config);

        if (!is_dir('./uploads/rka/')) {
            mkdir('./uploads/rka/', 0777, TRUE);
        }

        if (!$this->upload->do_upload('file_excel')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', $error);
            redirect('rka_upload/upload');
        } else {
            $upload_data = $this->upload->data();
            $file_path = './uploads/rka/' . $upload_data['file_name'];
            
            // Proses import menggunakan PHPExcel
            $result = $this->import_excel($file_path);
            
            if ($result['status']) {
                $this->session->set_flashdata('success', 'Data berhasil diupload. Jumlah data: '.$result['total']);
            } else {
                $this->session->set_flashdata('error', $result['message']);
            }
            
            // Hapus file setelah diupload
            @unlink($file_path);
            redirect('rka_upload');
        }
    }

    private function import_excel($file_path) {
        try {
            // Baca file Excel
            $data = $this->excel->read($file_path);
            
            // Hapus header (baris pertama)
            array_shift($data);
            
            $total_insert = 0;
            $errors = [];
            
            // Mapping kolom Excel ke database
            // Index dimulai dari 0
            $column_mapping = [
                0 => 'id',                    // A - ID (tidak dipakai)
                1 => 'tahun_anggaran',        // B - Tahun Anggaran
                2 => 'kode_dpsj',             // C - Kode DPSJ
                3 => 'deskripsi_dpsj',        // D - Deskripsi DPSJ
                4 => 'kode_kegiatan',         // E - Kode Kegiatan
                5 => 'nama_kegiatan_pendek',  // F - Nama Kegiatan Pendek
                6 => 'nama_kegiatan',         // G - Nama Kegiatan
                7 => 'kode_dana',             // H - Kode Dana
                8 => 'deskripsi_dana',        // I - Deskripsi Dana
                9 => 'kategori_kegiatan',     // J - Kategori Kegiatan
                10 => 'kode_akun',            // K - Kode Akun
                11 => 'deskripsi_akun',       // L - Deskripsi Akun
                12 => 'rup',                  // M - RUP
                13 => 'anggaran',             // N - Anggaran
                14 => 'komitmen',             // O - Komitmen
                15 => 'aktual',               // P - Aktual
                16 => 'mutasi',               // Q - Mutasi
                17 => 'sisa_anggaran',        // R - Sisa Anggaran
                18 => 'tgl_update_realisasi', // S - Tgl Update Realisasi
                19 => 'id_kegiatan',          // T - ID Kegiatan
                20 => 'flag_payroll'          // U - Flag Payroll
            ];
            
            foreach ($data as $row_index => $row) {
                // Skip empty row
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Mapping data
                $insert_data = [];
                foreach ($column_mapping as $col_index => $db_field) {
                    $value = isset($row[$col_index]) ? $row[$col_index] : null;
                    
                    // Clean number fields
                    if (in_array($db_field, ['tahun_anggaran', 'anggaran', 'komitmen', 'aktual', 'mutasi', 'sisa_anggaran', 'id_kegiatan'])) {
                        $value = $this->clean_number($value);
                    }
                    
                    // Parse date
                    if ($db_field == 'tgl_update_realisasi') {
                        $value = $this->parse_date($value);
                    }
                    
                    $insert_data[$db_field] = $value;
                }
                
                // Unset ID karena auto increment
                unset($insert_data['id']);
                
                // Validasi data wajib
                if (empty($insert_data['tahun_anggaran']) || empty($insert_data['kode_kegiatan']) || empty($insert_data['kode_akun'])) {
                    $errors[] = "Baris " . ($row_index + 2) . ": Data tidak lengkap - Tahun Anggaran, Kode Kegiatan, atau Kode Akun kosong";
                    continue;
                }
                
                // Insert data
                if ($this->db->insert('rka', $insert_data)) {
                    $total_insert++;
                } else {
                    $errors[] = "Baris " . ($row_index + 2) . ": Gagal insert data";
                }
            }
            
            if ($total_insert > 0) {
                $message = "Berhasil mengimport {$total_insert} data";
                if (!empty($errors)) {
                    $message .= " dengan " . count($errors) . " error";
                }
                return [
                    'status' => true,
                    'total' => $total_insert,
                    'errors' => $errors,
                    'message' => $message
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Tidak ada data yang berhasil diimport. ' . implode('; ', $errors)
                ];
            }
            
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    private function clean_number($value) {
        if (is_null($value) || $value === '') {
            return 0;
        }
        
        if (is_string($value)) {
            // Hapus spasi, Rp, dan karakter lainnya
            $value = str_replace(['Rp', 'Rp.', ' '], '', $value);
            // Hapus titik sebagai pemisah ribuan
            $value = str_replace('.', '', $value);
            // Ganti koma dengan titik untuk desimal
            $value = str_replace(',', '.', $value);
        }
        
        return floatval($value);
    }

    private function parse_date($date_value) {
        if (empty($date_value) || $date_value === '0000-00-00 00:00:00') {
            return null;
        }
        
        // Jika berupa angka (Excel date)
        if (is_numeric($date_value)) {
            try {
                $timestamp = PHPExcel_Shared_Date::ExcelToPHP($date_value);
                return date('Y-m-d H:i:s', $timestamp);
            } catch (Exception $e) {
                return null;
            }
        }
        
        return $date_value;
    }

    public function delete_all() {
        $this->Rka_model->delete_all();
        $this->session->set_flashdata('success', 'Semua data berhasil dihapus');
        redirect('rka_upload');
    }

    public function download_template() {
        // Data template
        $headers = ['ID', 'Tahun Anggaran', 'Kode DPSJ', 'Deskripsi DPSJ', 
                    'Kode Kegiatan', 'Nama Kegiatan Pendek', 'Nama Kegiatan',
                    'Kode Dana', 'Deskripsi Dana', 'Kategori Kegiatan',
                    'Kode Akun', 'Deskripsi Akun', 'RUP',
                    'Anggaran', 'Komitmen', 'Aktual', 'Mutasi',
                    'Sisa Anggaran', 'Tgl Update Realisasi', 'ID Kegiatan', 'Flag Payroll'];
        
        // Contoh data
        $data = [
            ['', '2026', '09000100', 'Pimpinan',
             'F0086.13.01.6.003', 'Koordinasi Rutin Pimpinan Eksekutif', 
             '09000100-F0086.13.01.6.003:Koordinasi Rutin Pimpinan Eksekutif',
             '51', 'Dana Masyarakat (BP) - Tidak Terikat', 'Operasional',
             '722111', 'Beban Uang Harian - Perjadin Luar Kota', '',
             '0', '0', '0', '0',
             '0', '0000-00-00 00:00:00', '0', 'Procost Unit'],
            ['', '2026', '09000100', 'Pimpinan',
             'F0086.13.01.6.003', 'Koordinasi Rutin Pimpinan Eksekutif', 
             '09000100-F0086.13.01.6.003:Koordinasi Rutin Pimpinan Eksekutif',
             '51', 'Dana Masyarakat (BP) - Tidak Terikat', 'Operasional',
             '723207', 'Beban Konsumsi', '',
             '48000000', '36753500', '0', '3000000',
             '8246500', '0000-00-00 00:00:00', '18975', 'Procost Unit']
        ];
        
        // Buat Excel
        $excel = $this->excel->create($data, $headers);
        
        // Download
        $this->excel->download($excel, 'template_rka', 'Excel2007');
    }
}
?>
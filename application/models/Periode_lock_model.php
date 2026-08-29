<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periode_lock_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
		$this->load->database();
		$this->load->helper('periode_helper');
		$this->load->model('periode_lock_model');
    }
    
    // Mendapatkan semua periode
    public function get_all_periode() {
        $this->db->order_by('tahun DESC, bulan DESC');
        return $this->db->get('periode')->result();
    }
    
    // Mendapatkan status lock periode tertentu
    public function get_lock_status($tahun, $bulan) {
        $this->db->where('tahun', $tahun);
        $this->db->where('bulan', $bulan);
        $result = $this->db->get('periode')->row();
        return $result ? $result->lock_data : null;
    }
    
    // Mendapatkan periode aktif (lock_data = 0)
    public function get_active_periode() {
        $this->db->where('lock_data', 0);
        $this->db->order_by('tahun DESC, bulan DESC');
        $this->db->limit(1);
        return $this->db->get('periode')->row();
    }
    
    // Update status lock
    public function update_lock($id, $lock_data) {
        $this->db->where('id', $id);
        
        $data = array(
            'lock_data' => $lock_data,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        if ($this->db->update('periode', $data)) {
            // Jika dikunci (lock_data = 1), buat periode berikutnya
            if ($lock_data == 1) {
                //$this->create_next_periode($id);
            }
            return true;
        }
        return false;
    }
    
    // Membuat periode berikutnya
    private function create_next_periode($current_id) {
        // Dapatkan data periode saat ini
        $this->db->where('id', $current_id);
        $current = $this->db->get('periode')->row();
        
        if ($current) {
            $next_month = $current->bulan + 1;
            $next_year = $current->tahun;
            
            // Jika bulan > 12, naikkan tahun
            if ($next_month > 12) {
                $next_month = 1;
                $next_year++;
            }
            
            // Cek apakah periode berikutnya sudah ada
            $this->db->where('tahun', $next_year);
            $this->db->where('bulan', $next_month);
            $existing = $this->db->get('periode')->row();
            
            // Jika belum ada, buat baru
            if (!$existing) {
                $data = array(
                    'tahun_anggaran' => $next_year,
                    'tahun' => $next_year,
                    'bulan' => $next_month,
                    'lock_data' => 0 // Buka akses untuk input
                );
                
                $this->db->insert('periode', $data);
            }
        }
    }
    
    // Generate nomor transaksi otomatis
    public function generate_transaction_number($tahun, $bulan) {
        // Format: TRX/YYYY/MM/XXXX
        $prefix = "TRX";
        $month_formatted = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        
        // Hitung nomor urut terakhir untuk periode ini
        $this->db->select('MAX(no_transaksi) as last_number');
        $this->db->like('no_transaksi', $prefix . '/' . $tahun . '/' . $month_formatted, 'after');
        $result = $this->db->get('transaksi')->row();
        
        $last_number = 0;
        if ($result && $result->last_number) {
            $parts = explode('/', $result->last_number);
            $last_number = (int) end($parts);
        }
        
        // Increment nomor
        $new_number = $last_number + 1;
        $sequence = str_pad($new_number, 4, '0', STR_PAD_LEFT);
        
        return $prefix . '/' . $tahun . '/' . $month_formatted . '/' . $sequence;
    }
    
    // Validasi apakah periode bisa diinput
    public function is_periode_open($tahun, $bulan) {
        $this->db->where('tahun', $tahun);
        $this->db->where('bulan', $bulan);
        $result = $this->db->get('periode')->row();
        
        if ($result && $result->lock_data == 0) {
            return true;
        }
        return false;
    }
    
    // Buat periode awal jika belum ada
    public function initialize_periode($tahun, $bulan) {
        // Cek apakah sudah ada data
        $this->db->where('tahun', $tahun);
        $this->db->where('bulan', $bulan);
        $existing = $this->db->get('periode')->row();
        
        if (!$existing) {
            $data = array(
                'tahun_anggaran' => $tahun,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'lock_data' => 0
            );
            
            return $this->db->insert('periode', $data);
        }
        
        return false;
    }
}
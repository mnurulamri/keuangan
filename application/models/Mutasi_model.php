<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    // Get all mutations with pagination
    public function get_mutasi($limit = null, $offset = null, $search = null) {
        $this->db->select('*');
        $this->db->from('view_mutasi_pengajuan');
        //$this->db->from('mutasi');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('kode_kegiatan', $search);
            $this->db->or_like('kode_akun', $search);
            $this->db->or_like('deskripsi_akun', $search);
            $this->db->or_like('no_bukti', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('tanggal', 'DESC');
        $this->db->order_by('created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }
    
    // Get mutation by ID
    public function get_mutasi_by_id($id) {
        return $this->db->get_where('mutasi', ['id' => $id])->row();
    }
    
    // Insert new mutation
    public function insert_mutasi($data) {
        $this->db->trans_start();
        
        // Insert mutation record
        $this->db->insert('mutasi', $data);
        $mutasi_id = $this->db->insert_id();
        
        // Update total mutation in anggaran table
        $this->_update_total_mutasi($data['kode_kegiatan'], $data['kode_akun'], $data['kode_dana']);
        
        // Update saldo in anggaran table
        $this->_update_saldo_anggaran($data['kode_kegiatan'], $data['kode_akun'], $data['kode_dana']);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }
    
    // Update mutation
    public function update_mutasi($id, $data) {
        $this->db->trans_start();
        
        // Get old data for rollback calculation
        $old_mutasi = $this->get_mutasi_by_id($id);
        
        // Update mutation record
        $this->db->where('id', $id);
        $this->db->update('mutasi', $data);
        
        // Update total mutation in anggaran table for old combination
        $this->_update_total_mutasi($old_mutasi->kode_kegiatan, $old_mutasi->kode_akun, $old_mutasi->kode_dana);
        
        // Update total mutation in anggaran table for new combination
        $this->_update_total_mutasi($data['kode_kegiatan'], $data['kode_akun'], $data['kode_dana']);
        
        // Update saldo for both combinations
        $this->_update_saldo_anggaran($old_mutasi->kode_kegiatan, $old_mutasi->kode_akun, $old_mutasi->kode_dana);
        $this->_update_saldo_anggaran($data['kode_kegiatan'], $data['kode_akun'], $data['kode_dana']);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }
    
    // Delete mutation
    public function delete_mutasi($id) {
        $this->db->trans_start();
        
        // Get mutation data before delete
        $mutasi = $this->get_mutasi_by_id($id);
        
        // Delete mutation record
        $this->db->where('id', $id);
        $this->db->delete('mutasi');
        
        // Update total mutation in anggaran table
        $this->_update_total_mutasi($mutasi->kode_kegiatan, $mutasi->kode_akun, $mutasi->kode_dana);
        
        // Update saldo in anggaran table
        $this->_update_saldo_anggaran($mutasi->kode_kegiatan, $mutasi->kode_akun, $mutasi->kode_dana);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }
    
    // Get total count for pagination
    public function count_mutasi($search = null) {
        if ($search) {
            $this->db->group_start();
            $this->db->like('kode_kegiatan', $search);
            $this->db->or_like('kode_akun', $search);
            $this->db->or_like('deskripsi_akun', $search);
            $this->db->or_like('no_bukti', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results('mutasi');
    }
    
    // Get akun options
    public function get_akun_options() {
        $this->db->select('kode_kegiatan, nama_kegiatan, kode_akun, deskripsi_akun, kode_dana');
        $this->db->distinct();
        $this->db->from('rka');
        $this->db->order_by('kode_kegiatan', 'ASC');
        $this->db->order_by('kode_akun', 'ASC');
        return $this->db->get()->result();
    }
    
    // Get akun detail
    public function get_akun_detail($kode_kegiatan, $kode_akun, $kode_dana) {
        return $this->db->get_where('rka', [
            'kode_kegiatan' => $kode_kegiatan,
            'kode_akun' => $kode_akun,
            'kode_dana' => $kode_dana
        ])->row();
    }
    
    // Private function to update total mutation in anggaran
    private function _update_total_mutasi($kode_kegiatan, $kode_akun, $kode_dana) {
        // Calculate sum of mutations
        $this->db->select('SUM(mutasi) as total_mutasi');
        $this->db->from('mutasi');
        $this->db->where('kode_kegiatan', $kode_kegiatan);
        $this->db->where('kode_akun', $kode_akun);
        $this->db->where('kode_dana', $kode_dana);
        $result = $this->db->get()->row();
        
        $total_mutasi = $result->total_mutasi ?: 0;
        
        // Update anggaran table
        $this->db->where('kode_kegiatan', $kode_kegiatan);
        $this->db->where('kode_akun', $kode_akun);
        $this->db->where('kode_dana', $kode_dana);
        $this->db->update('rka', ['mutasi' => $total_mutasi]);
    }
    
    // Private function to update saldo anggaran
    private function _update_saldo_anggaran($kode_kegiatan, $kode_akun, $kode_dana) {
        $anggaran = $this->get_akun_detail($kode_kegiatan, $kode_akun, $kode_dana);
        
        if ($anggaran) {
            // Calculate based on rules
            if (empty($anggaran->aktual) || $anggaran->aktual == 0) {
                // Jika aktual kosong: anggaran - komitmen + mutasi
                $sisa_saldo = $anggaran->anggaran - $anggaran->komitmen + $anggaran->mutasi;
            } else {
                // Jika aktual terisi: anggaran - aktual + mutasi
                $sisa_saldo = $anggaran->anggaran - $anggaran->aktual + $anggaran->mutasi;
            }
            
            // Update saldo
            $this->db->where('kode_kegiatan', $kode_kegiatan);
            $this->db->where('kode_akun', $kode_akun);
            $this->db->where('kode_dana', $kode_dana);
            $this->db->update('rka', ['sisa_saldo' => $sisa_saldo]);
        }
    }

	// Tambahkan method baru untuk generate nomor pengajuan
    public function generate_nomor_pengajuan($kode_unit, $tahun, $bulan) {
        $current_month = $bulan;
        $current_year = $tahun;
        
        // Cari nomor terakhir bulan ini, tahun ini dan kode unit kerja
        $this->db->select_max('nomor_urut');
        $this->db->where('bulan', $current_month);
        $this->db->where('tahun', $current_year);
        $this->db->where('kode_unit', $kode_unit);
        $query = $this->db->get('view_mutasi_pengajuan');
        $result = $query->row();
        
        $next_number = 1;
        if ($result && $result->nomor_urut) {
            $next_number = $result->nomor_urut + 1;
        }
        
        $nomor_urut = str_pad($next_number, 3, '0', STR_PAD_LEFT);    

        return $nomor_urut . '/MUTASI/' . $bulan . '/' . $tahun . '-' . $kode_unit;
    }

    public function generate_nomor_urut($kode_unit, $tahun, $bulan) {
        $current_month = $bulan;
        $current_year = $tahun;
        
        // Cari nomor terakhir bulan ini, tahun ini dan kode unit kerja
        $this->db->select_max('nomor_urut');
        $this->db->where('bulan', $current_month);
        $this->db->where('tahun', $current_year);
        $this->db->where('kode_unit', $kode_unit);
        $query = $this->db->get('view_mutasi_pengajuan');
        $result = $query->row();
        
        $next_number = 1;
        if ($result && $result->nomor_urut) {
            $next_number = $result->nomor_urut + 1;
        }

        return $next_number;

    }
}
?>
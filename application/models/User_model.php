<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $table = 'users';
    private $table_unit = 'unit_kerja';

    public function __construct() {
        parent::__construct();
    }

    public function get_all_users() {
        $this->db->select('u.*, uk.nama_unit, uk.kode_dpsj as dpsj, uk.deskripsi_dpsj');
        $this->db->from($this->table . ' u');
        $this->db->join($this->table_unit . ' uk', 'u.kode_bidang = uk.kode_bidang AND u.kode_dpsj = uk.kode_dpsj', 'left');
        $this->db->order_by('u.nama', 'asc');
        return $this->db->get()->result();
    }

    public function get_user_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_all_units() {
        $this->db->select('kode_bidang, kode_dpsj, nama_unit');
        $this->db->order_by('nama_unit', 'asc');
        return $this->db->get($this->table_unit)->result();
    }

    public function get_units_by_bidang($kode_bidang) {
        $this->db->select('kode_dpsj, nama_unit, deskripsi_dpsj');
        $this->db->where('kode_bidang', $kode_bidang);
        $this->db->order_by('nama_unit', 'asc');
        return $this->db->get($this->table_unit)->result();
    }

    public function create_user($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete_user($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function get_roles() {
        // Get enum values from table
        $query = $this->db->query("SHOW COLUMNS FROM {$this->table} LIKE 'role'");
        $row = $query->row();
        
        if ($row) {
            $enum = $row->Type;
            preg_match("/^enum\(\'(.*)\'\)$/", $enum, $matches);
            $enum_values = explode("','", $matches[1]);
            return array_combine($enum_values, $enum_values);
        }
        
        return [
            'admin' => 'Admin',
            'anggaran' => 'Anggaran',
            'korpum' => 'Korpum',
            'manajer' => 'Manajer',
            'kasir' => 'Kasir',
            'verifikator' => 'Verifikator',
            'pum' => 'PUM',
            'yunior_akuntan' => 'Yunior Akuntan'
        ];
    }

    public function check_username_exists($username, $id = null) {
        $this->db->where('username', $username);
        if ($id) {
            $this->db->where('id !=', $id);
        }
        return $this->db->get($this->table)->num_rows() > 0;
    }
}
?>
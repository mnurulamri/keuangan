<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rka_upload_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('rka');
        return $query->result();
    }

    public function delete_all() {
        $this->db->truncate('rka');
    }

    public function get_total() {
        return $this->db->count_all('rka');
    }
    
    public function get_summary() {
        $this->db->select('
            COUNT(*) as total_data,
            SUM(anggaran) as total_anggaran,
            SUM(komitmen) as total_komitmen,
            SUM(aktual) as total_aktual,
            SUM(sisa_anggaran) as total_sisa
        ');
        $query = $this->db->get('rka');
        return $query->row();
    }
}
?>
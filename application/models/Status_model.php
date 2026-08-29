<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all status
     */
    public function get_all_status() {
        return $this->db->get('status')->result_array();
    }
    
    /**
     * Get status by ID
     */
    public function get_status_by_id($id) {
        return $this->db->get_where('status', array('id' => $id))->row_array();
    }
    
    /**
     * Get status by role
     */
    public function get_status_by_role($role_column, $value = 1) {
        $this->db->where($role_column, $value);
        return $this->db->get('status')->result_array();
    }
    
    /**
     * Add new status
     */
    public function add_status($data) {
        $this->db->insert('status', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Update status
     */
    public function update_status($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('status', $data);
    }
    
    /**
     * Delete status
     */
    public function delete_status($id) {
        return $this->db->delete('status', array('id' => $id));
    }
    
    /**
     * Get all roles from status table structure
     */
    public function get_roles() {
        $roles = array(
            'pum' => 'PUM',
            'anggaran' => 'Anggaran',
            'korpum' => 'KORPUM',
            'manajer' => 'Manajer Keuangan',
            'kasir' => 'Kasir',
            'verifikator' => 'Verifikator',
            'yunior_akuntan' => 'Junior Akuntan'
        );
        return $roles;
    }
}
?>
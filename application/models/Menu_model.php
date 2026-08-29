<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

    private $table = 'menu'; // Sesuaikan dengan nama tabel Anda

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_menus() {
        $this->db->order_by('sort', 'ASC');
        $this->db->order_by('label', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    public function get_menu_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    public function get_parent_menus() {
        $this->db->where('parent', 0);
        $this->db->order_by('sort', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    public function update_menu($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function update_role_access($menu_id, $role, $value) {
        $this->db->where('id', $menu_id);
        $data = [$role => $value];
        return $this->db->update($this->table, $data);
    }

    public function get_menus_by_role($role) {
        $this->db->where($role, 1);
        $this->db->order_by('sort', 'ASC');
        return $this->db->get($this->table)->result_array();
    }
}
?>
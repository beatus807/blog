<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_categories() {
        $query = $this->db->get('categories');
        return $query->result();
    }

    public function get_category($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('categories');
        return $query->row();
    }

    public function create($data) {
        $result = $this->db->insert('categories', $data);
        
        // Debug: Check if insert worked
        if (!$result) {
            log_message('error', 'Category insert failed: ' . $this->db->error()['message']);
        }
        
        return $result;
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        $result = $this->db->update('categories', $data);
        
        if (!$result) {
            log_message('error', 'Category update failed: ' . $this->db->error()['message']);
        }
        
        return $result;
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $result = $this->db->delete('categories');
        
        if (!$result) {
            log_message('error', 'Category delete failed: ' . $this->db->error()['message']);
        }
        
        return $result;
    }

    public function toggle_status($id, $status) {
        $this->db->where('id', $id);
        $result = $this->db->update('categories', array('status' => $status));
        
        if (!$result) {
            log_message('error', 'Category status toggle failed: ' . $this->db->error()['message']);
        }
        
        return $result;
    }
}
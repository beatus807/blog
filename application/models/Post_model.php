<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all_posts() {
        $this->db->select('posts.*, categories.name as category_name');
        $this->db->from('posts');
        $this->db->join('categories', 'posts.category_id = categories.id', 'left');
        $this->db->order_by('posts.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_post($id) {
        $this->db->select('posts.*, categories.name as category_name');
        $this->db->from('posts');
        $this->db->join('categories', 'posts.category_id = categories.id', 'left');
        $this->db->where('posts.id', $id);
        return $this->db->get()->row();
    }

    public function create($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['slug'] = $this->generate_slug($data['title']);
        return $this->db->insert('posts', $data);
    }

    public function update($id, $data) {
        // METHOD 1: Using manual SQL to ensure WHERE clause is included
        if (empty($id)) {
            log_message('error', 'Post update called without ID');
            return false;
        }
        
        // Generate slug if title is provided
        if (isset($data['title'])) {
            $data['slug'] = $this->generate_slug($data['title'], $id);
        }
        
        // Build SET clause manually
        $set_clause = [];
        foreach ($data as $key => $value) {
            $set_clause[] = "`$key` = " . $this->db->escape($value);
        }
        $set_clause = implode(', ', $set_clause);
        
        // Manual SQL query to ensure WHERE clause is included
        $sql = "UPDATE `posts` SET $set_clause WHERE `id` = ?";
        $result = $this->db->query($sql, [$id]);
        
        // Debug
        log_message('debug', 'MANUAL UPDATE QUERY: ' . $sql);
        log_message('debug', 'With ID: ' . $id);
        
        return $result;
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('posts');
    }

    public function toggle_status($id, $status) {
        // Use manual SQL for consistency
        $sql = "UPDATE `posts` SET `status` = ? WHERE `id` = ?";
        return $this->db->query($sql, [$status, $id]);
    }

    private function generate_slug($title, $exclude_id = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        
        // Check if slug exists, excluding current post
        $this->db->where('slug', $slug);
        if (!empty($exclude_id)) {
            $this->db->where('id !=', $exclude_id);
        }
        $count = $this->db->count_all_results('posts');
        
        if ($count > 0) {
            $slug = $slug . '-' . time(); // Use timestamp to ensure uniqueness
        }
        
        return $slug;
    }

    public function get_active_categories() {
        $this->db->where('status', 1);
        return $this->db->get('categories')->result();
    }
}
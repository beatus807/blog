<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $this->load->model('Post_model');
        $this->load->model('Category_model');
    }

    public function index() {
        $data['title'] = 'Post Management';
        $data['posts'] = $this->Post_model->get_all_posts();
        $this->load->view('templates/header', $data);
        $this->load->view('posts/index', $data);
        $this->load->view('templates/footer');
    }

    public function form($id = null) {
        $data['title'] = $id ? 'Edit Post' : 'Add New Post';
        $data['categories'] = $this->Post_model->get_active_categories();
        
        if ($id) {
            $data['post'] = $this->Post_model->get_post($id);
            // DEBUG: Check if we're getting the correct post
            log_message('debug', 'Editing post ID: ' . $id);
            log_message('debug', 'Post data: ' . print_r($data['post'], true));
        }

        $this->form_validation->set_rules('category_id', 'Category', 'required');
        $this->form_validation->set_rules('title', 'Title', 'required|min_length[5]|max_length[150]');
        $this->form_validation->set_rules('description', 'Description', 'required');

        if ($this->form_validation->run() == TRUE) {
            $post_data = array(
                'category_id' => $this->input->post('category_id'),
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'status' => $this->input->post('status') ? 1 : 0
            );

            // Handle file upload
            if (!empty($_FILES['cover_image']['name'])) {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('cover_image')) {
                    $upload_data = $this->upload->data();
                    $post_data['cover_image'] = $upload_data['file_name'];
                    
                    // Delete old image if exists and we're updating
                    if ($id && !empty($data['post']->cover_image)) {
                        $old_image_path = './uploads/' . $data['post']->cover_image;
                        if (file_exists($old_image_path)) {
                            unlink($old_image_path);
                        }
                    }
                }
            }

            // DEBUG: Log update data
            log_message('debug', 'Post data to save: ' . print_r($post_data, true));
            
            if ($id) {
                // Update existing post
                log_message('debug', 'Updating post with ID: ' . $id);
                if ($this->Post_model->update($id, $post_data)) {
                    $this->session->set_flashdata('success', 'Post updated successfully');
                } else {
                    $this->session->set_flashdata('error', 'Failed to update post');
                }
            } else {
                // Create new post
                if ($this->Post_model->create($post_data)) {
                    $this->session->set_flashdata('success', 'Post created successfully');
                } else {
                    $this->session->set_flashdata('error', 'Failed to create post');
                }
            }

            redirect('posts');
        }

        $this->load->view('templates/header', $data);
        $this->load->view('posts/form', $data);
        $this->load->view('templates/footer');
    }

    public function delete($id) {
        // Get post info before deleting to remove associated image
        $post = $this->Post_model->get_post($id);
        
        if ($post && !empty($post->cover_image)) {
            $image_path = './uploads/' . $post->cover_image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        if ($this->Post_model->delete($id)) {
            $this->session->set_flashdata('success', 'Post deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete post');
        }
        redirect('posts');
    }

    public function toggle_status($id) {
        $post = $this->Post_model->get_post($id);
        $new_status = $post->status ? 0 : 1;
        
        if ($this->Post_model->toggle_status($id, $new_status)) {
            $this->session->set_flashdata('success', 'Status updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update status');
        }
        redirect('posts');
    }
}
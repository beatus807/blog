<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $this->load->model('Category_model');
    }

    public function index() {
        $data['title'] = 'Category Management';
        $data['categories'] = $this->Category_model->get_all_categories();
        
        $this->load->view('templates/header', $data);
        $this->load->view('categories/index', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
        // Check if it's an AJAX request
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $this->form_validation->set_rules('name', 'Category Name', 'required|min_length[2]|max_length[100]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 'error', 'message' => validation_errors()));
            return;
        }

        $data = array(
            'name' => $this->input->post('name'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );

        $result = $this->Category_model->create($data);
        
        if ($result) {
            echo json_encode(array('status' => 'success', 'message' => 'Category created successfully'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to create category'));
        }
    }

    public function edit($id) {
        // Check if it's an AJAX request
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        if ($this->input->method() == 'post') {
            $this->form_validation->set_rules('name', 'Category Name', 'required|min_length[2]|max_length[100]');

            if ($this->form_validation->run() == FALSE) {
                echo json_encode(array('status' => 'error', 'message' => validation_errors()));
                return;
            }

            $data = array(
                'name' => $this->input->post('name')
            );

            if ($this->Category_model->update($id, $data)) {
                echo json_encode(array('status' => 'success', 'message' => 'Category updated successfully'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Failed to update category'));
            }
        } else {
            $category = $this->Category_model->get_category($id);
            if ($category) {
                echo json_encode($category);
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Category not found'));
            }
        }
    }

    public function delete($id) {
        // Check if it's an AJAX request
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        if ($this->Category_model->delete($id)) {
            echo json_encode(array('status' => 'success', 'message' => 'Category deleted successfully'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to delete category'));
        }
    }

    public function toggle_status($id) {
        // Check if it's an AJAX request
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $category = $this->Category_model->get_category($id);
        if (!$category) {
            echo json_encode(array('status' => 'error', 'message' => 'Category not found'));
            return;
        }

        $new_status = $category->status ? 0 : 1;
        
        if ($this->Category_model->toggle_status($id, $new_status)) {
            echo json_encode(array('status' => 'success', 'message' => 'Status updated successfully'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to update status'));
        }
    }
}
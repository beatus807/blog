<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    public function index() {
        redirect('auth/login');
    }

    public function login() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == TRUE) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->User_model->login($email, $password);

            if ($user) {
                $user_data = array(
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'logged_in' => TRUE
                );

                $this->session->set_userdata($user_data);
                $this->session->set_flashdata('success', 'Welcome back, ' . $user->name . '!');
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid email or password. Please try again.');
                redirect('auth/login');
            }
        }

        $data['title'] = 'Login';
        $this->load->view('auth/login', $data);
    }

 public function register() {
    if ($this->session->userdata('logged_in')) {
        redirect('dashboard');
    }

    // Set validation rules with custom error messages
    $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]|max_length[100]|trim',
        array(
            'required' => 'Please enter your full name',
            'min_length' => 'Name must be at least 3 characters long',
            'max_length' => 'Name cannot exceed 100 characters'
        )
    );
    
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]|trim',
        array(
            'required' => 'Please enter your email address',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'This email is already registered. Please use a different email.'
        )
    );
    
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|callback_validate_password',
        array(
            'required' => 'Please create a password',
            'min_length' => 'Password must be at least 6 characters long'
        )
    );
    
    $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]',
        array(
            'required' => 'Please confirm your password',
            'matches' => 'Passwords do not match'
        )
    );

    if ($this->form_validation->run() == TRUE) {
        $data = array(
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password')
        );

        if ($this->User_model->register($data)) {
            $this->session->set_flashdata('success', 
                '🎉 Registration successful! You can now login with your credentials.');
            redirect('auth/login');
        } else {
            $this->session->set_flashdata('error', 
                '❌ Registration failed. Please try again or contact support.');
        }
    } else {
        // If form validation fails, show error message
        if ($this->input->post()) {
            $this->session->set_flashdata('error', 
                'Please correct the errors below and try again.');
        }
    }

    $data['title'] = 'Register';
    $this->load->view('auth/register', $data);
}

// Custom password validation callback
public function validate_password($password) {
    if (empty($password)) {
        return TRUE; // Let required rule handle empty passwords
    }
    
    // Check if password contains at least one letter and one number
    if (!preg_match('/[a-zA-Z]/', $password)) {
        $this->form_validation->set_message('validate_password', 'Password must contain at least one letter.');
        return FALSE;
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $this->form_validation->set_message('validate_password', 'Password must contain at least one number.');
        return FALSE;
    }
    
    return TRUE;
}
    // Custom password validation callback
 
    public function logout() {
        $user_name = $this->session->userdata('name');
        $this->session->unset_userdata(array('user_id', 'name', 'email', 'logged_in'));
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'You have been successfully logged out. See you again soon' . ($user_name ? ', ' . $user_name : '') . '!');
        redirect('auth/login');
    }
}
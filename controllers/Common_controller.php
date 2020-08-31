<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Common_controller extends Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Admin Login.
     */
    public function admin_login()
    {
        if ($this->auth_check) {
            redirect(lang_base_url());
        }
        $data['title'] = trans('login');
        $data['description'] = trans('login') . ' - ' . $this->settings->site_title;
        $data['keywords'] = trans('login') . ', ' . $this->general_settings->application_name;
        $this->load->view('admin/login', $data);
    }

    /**
     * Admin Login Post.
     */
    public function admin_login_post()
    {
        //validate inputs
        $this->form_validation->set_rules('email', trans('form_email'), 'required|xss_clean|max_length[200]');
        $this->form_validation->set_rules('password', trans('form_password'), 'required|xss_clean|max_length[30]');

        if (false == $this->form_validation->run()) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->auth_model->input_values());
            redirect($this->agent->referrer());
        } else {
            if ($this->auth_model->login()) {
                redirect(admin_url());
            } else {
                //error
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', trans('login_error'));
                redirect($this->agent->referrer());
            }
        }
    }

    /**
     * Logout.
     */
    public function logout()
    {
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        $this->auth_model->logout();
        redirect($this->agent->referrer());
    }
}

<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sitemap_controller extends Admin_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        //check user
        if (!is_admin()) {
            redirect(admin_url() . 'login');
        }
        $this->load->model('sitemap_model');
    }

    /**
     * Generate Sitemap.
     */
    public function generate_sitemap()
    {
        $data['title'] = trans('generate_sitemap');

        $this->load->view('admin/_header', $data);
        $this->load->view('admin/generate_sitemap', $data);
        $this->load->view('admin/_footer');
    }

    /**
     * Generate Sitemap Post.
     */
    public function generate_sitemap_post()
    {
        //update sitemap settings
        $this->sitemap_model->update_sitemap_settings();
        $process = $this->input->post('process', true);
        if ('generate' == $process):
            $this->sitemap_model->download_sitemap(); elseif ('update' == $process):
            $this->sitemap_model->update_sitemap();
        $this->session->set_flashdata('success_form', trans('msg_sitemap_updated'));
        redirect($this->agent->referrer());
        endif;
    }
}

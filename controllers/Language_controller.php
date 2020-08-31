<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Language_controller extends Admin_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        //check user
        if (!is_admin()) {
            redirect(admin_url() . 'login');
        }
    }

    /**
     * Languages.
     */
    public function languages()
    {
        $data['title'] = trans('language_settings');
        $data['languages'] = $this->language_model->get_languages();

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/language/languages', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Set Language Post.
     */
    public function set_language_post()
    {
        if ($this->language_model->set_language()) {
            $this->session->set_flashdata('success_form', trans('msg_updated'));
            $this->session->set_flashdata('mes_set_language', 1);
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('form_data', $this->language_model->input_values());
            $this->session->set_flashdata('error_form', trans('msg_error'));
            $this->session->set_flashdata('mes_set_language', 1);
            redirect($this->agent->referrer());
        }
    }

    /**
     * Add Language Post.
     */
    public function add_language_post()
    {
        //validate inputs
        $this->form_validation->set_rules('name', trans('language_name'), 'required|xss_clean|max_length[200]');

        if (false === $this->form_validation->run()) {
            $this->session->set_flashdata('errors_form', validation_errors());
            $this->session->set_flashdata('form_data', $this->language_model->input_values());
            redirect($this->agent->referrer());
        } else {
            $language_id = $this->language_model->add_language();
            if (!empty($language_id)) {
                $this->language_model->add_language_settings($language_id);
                $this->language_model->add_language_pages($language_id);

                $this->session->set_flashdata('success_form', trans('msg_language_added'));
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('form_data', $this->language_model->input_values());
                $this->session->set_flashdata('error_form', trans('msg_error'));
                redirect($this->agent->referrer());
            }
        }
    }

    /**
     * Update Language.
     */
    public function update_language($id)
    {
        $data['title'] = trans('update_language');
        //get language
        $data['language'] = $this->language_model->get_language($id);

        if (empty($data['language'])) {
            redirect($this->agent->referrer());
        }

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/language/update_language', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Update Language Post.
     */
    public function update_language_post()
    {
        //validate inputs
        $this->form_validation->set_rules('name', trans('language_name'), 'required|xss_clean|max_length[200]');

        if (false === $this->form_validation->run()) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->language_model->input_values());
            redirect($this->agent->referrer());
        } else {
            $id = $this->input->post('id', true);

            if ($this->language_model->update_language($id)) {
                $this->session->set_flashdata('success', trans('msg_updated'));
                redirect(admin_url() . 'languages');
            } else {
                $this->session->set_flashdata('form_data', $this->language_model->input_values());
                $this->session->set_flashdata('error', trans('msg_error'));
                redirect($this->agent->referrer());
            }
        }
    }

    /**
     * Delete Language Post.
     */
    public function delete_language_post()
    {
        $id = $this->input->post('id', true);

        $language = $this->language_model->get_language($id);
        if (1 == $language->id) {
            $this->session->set_flashdata('error', trans('msg_default_language_delete'));
            exit();
        }
        if ($this->language_model->delete_language($id)) {
            $this->session->set_flashdata('success', trans('msg_language_deleted'));
        } else {
            $this->session->set_flashdata('error', trans('msg_error'));
        }
    }

    /**
     * Update Translations.
     */
    public function update_translations($id)
    {
        $data['title'] = trans('edit_translations');

        //get language
        $data['language'] = $this->language_model->get_language($id);
        if (empty($data['language'])) {
            redirect($this->agent->referrer());
        }

        //get paginated translations
        $pagination = $this->paginate(admin_url() . 'translations/' . $data['language']->id, $this->language_model->get_translation_count($data['language']->id));
        $data['translations'] = $this->language_model->get_paginated_translations($data['language']->id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/language/translations', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Update Translations Post.
     */
    public function update_translations_post()
    {
        $lang_id = $this->input->post('lang_id');
        $ids = $this->input->post();
        foreach ($ids as $key => $value) {
            if ('lang_id' != $key) {
                $this->language_model->update_translation($lang_id, $key, $value);
            }
        }
        $this->session->set_flashdata('success', trans('msg_updated'));
        redirect($this->agent->referrer());
    }

    /**
     * Update Translation Post.
     */
    public function update_translation_post()
    {
        $lang_id = $this->input->post('lang_id');
        $id = $this->input->post('label');
        $translation = $this->input->post('translation');
        $this->language_model->update_translation($lang_id, $id, $translation);
    }
}

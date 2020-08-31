<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ad_model extends CI_Model
{
    public function input_values()
    {
        return [
            'ad_code_728' => $this->input->post('ad_code_728', false),
            'ad_code_468' => $this->input->post('ad_code_468', false),
            'ad_code_250' => $this->input->post('ad_code_250', false),
        ];
    }

    public function input_url_values()
    {
        return [
            'url_ad_code_728' => $this->input->post('url_ad_code_728', false),
            'url_ad_code_468' => $this->input->post('url_ad_code_468', false),
            'url_ad_code_250' => $this->input->post('url_ad_code_250', false),
        ];
    }

    public function update_ad_spaces($ad_space)
    {
        $data = $this->input_values();
        $data_url = $this->input_url_values();

        if ('product_sidebar' == $ad_space || 'products_sidebar' == $ad_space || 'blog_post_details_sidebar' == $ad_space || 'profile_sidebar' == $ad_space) {
            $data['ad_code_300'] = $this->input->post('ad_code_300', false);
            $url_ad_code_300 = $this->input->post('url_ad_code_300', false);

            $this->load->model('upload_model');
            $file_path = $this->upload_model->ad_upload('file_ad_code_300');
            if (!empty($file_path)) {
                $data['ad_code_300'] = $this->create_ad_code($url_ad_code_300, $file_path);
            }
        } else {
            $this->load->model('upload_model');
            $file_path = $this->upload_model->ad_upload('file_ad_code_728');
            if (!empty($file_path)) {
                $data['ad_code_728'] = $this->create_ad_code($data_url['url_ad_code_728'], $file_path);
            }
            $file_path = $this->upload_model->ad_upload('file_ad_code_468');
            if (!empty($file_path)) {
                $data['ad_code_468'] = $this->create_ad_code($data_url['url_ad_code_468'], $file_path);
            }
        }

        $this->load->model('upload_model');
        $file_path = $this->upload_model->ad_upload('file_ad_code_250');
        if (!empty($file_path)) {
            $data['ad_code_250'] = $this->create_ad_code($data_url['url_ad_code_250'], $file_path);
        }

        $this->db->where('ad_space', $ad_space);

        return $this->db->update('ad_spaces', $data);
    }

    //get ads
    public function get_ads()
    {
        $query = $this->db->get('ad_spaces');

        return $query->result();
    }

    //get ad codes
    public function get_ad_codes($ad_space)
    {
        $this->db->where('ad_space', $ad_space);
        $query = $this->db->get('ad_spaces');

        return $query->row();
    }

    //create ad code
    public function create_ad_code($url, $image_path)
    {
        return '<a href="' . $url . '"><img src="' . base_url() . $image_path . '" alt=""></a>';
    }
}

<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Gumlet\ImageResize;
use Gumlet\ImageResizeException;

class Upload_model extends CI_Model
{
    //upload temp image
    public function upload_temp_image($file_name)
    {
        if (isset($_FILES[$file_name])) {
            if (empty($_FILES[$file_name]['name'])) {
                return;
            }
        }
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_name'] = 'img_temp_' . generate_unique_id();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = ['upload_data' => $this->upload->data()];
            if (isset($data['upload_data']['full_path'])) {
                return $data['upload_data']['full_path'];
            }

            return;
        }
    }

    //product default image upload
    public function product_default_image_upload($path, $folder)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->resizeToHeight(500);
            $new_name = 'img_x500_' . generate_unique_id() . '.jpg';
            $new_path = 'uploads/' . $folder . '/' . $new_name;
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);

            return $new_name;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //product big image upload
    public function product_big_image_upload($path, $folder)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->resizeToWidth(1920);
            $new_name = 'img_1920x_' . generate_unique_id() . '.jpg';
            $new_path = 'uploads/' . $folder . '/' . $new_name;
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);

            return $new_name;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //product small image upload
    public function product_small_image_upload($path, $folder)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->resizeToHeight(300);
            $new_name = 'img_x300_' . generate_unique_id() . '.jpg';
            $new_path = 'uploads/' . $folder . '/' . $new_name;
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);

            return $new_name;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //file manager image upload
    public function file_manager_image_upload($path)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->resizeToWidth(750);
            $new_name = 'img_' . generate_unique_id() . '.jpg';
            $new_path = 'uploads/images-file-manager/' . $new_name;
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);

            return $new_name;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //blog image default upload
    public function blog_image_default_upload($path)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->resizeToWidth(880);
            $new_path = 'uploads/blog/img_' . generate_unique_id() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);

            return $new_path;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //blog image default upload
    public function blog_image_small_upload($path)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->crop(500, 332, true);
            $new_path = 'uploads/blog/img_thumb_' . generate_unique_id() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);

            return $new_path;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //category image upload
    public function category_image_upload($path, $width, $height)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->crop($width, $height, true);
            $new_path = 'uploads/category/category_' . $width . '-' . $height . '_' . generate_unique_id() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);

            return $new_path;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //slider image upload
    public function slider_image_upload($path)
    {
        try {
            $image = new ImageResize($path);
            $image->crop(1170, 356, true);
            $new_path = 'uploads/slider/slider_' . generate_unique_id() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);

            return $new_path;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //slider small image upload
    public function slider_small_image_upload($path)
    {
        try {
            $image = new ImageResize($path);
            $image->crop(768, 380, true);
            $new_path = 'uploads/slider/slider_' . generate_unique_id() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);

            return $new_path;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //avatar image upload
    public function avatar_upload($path)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = 85;
            $image->crop(240, 240, true);
            $new_path = 'uploads/profile/avatar_' . generate_unique_id() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);

            return $new_path;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //logo image upload
    public function logo_upload($file_name)
    {
        $config['upload_path'] = './uploads/logo/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|svg';
        $config['file_name'] = 'logo_' . uniqid();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = ['upload_data' => $this->upload->data()];
            if (isset($data['upload_data']['full_path'])) {
                return 'uploads/logo/' . $data['upload_data']['file_name'];
            }
        }
    }

    //favicon image upload
    public function favicon_upload($file_name)
    {
        $config['upload_path'] = './uploads/logo/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_name'] = 'favicon_' . uniqid();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = ['upload_data' => $this->upload->data()];
            if (isset($data['upload_data']['full_path'])) {
                return 'uploads/logo/' . $data['upload_data']['file_name'];
            }
        }
    }

    //ad upload
    public function ad_upload($file_name)
    {
        $config['upload_path'] = './uploads/blocks/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_name'] = 'block_' . generate_unique_id();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = ['upload_data' => $this->upload->data()];
            if (isset($data['upload_data']['full_path'])) {
                return 'uploads/blocks/' . $data['upload_data']['file_name'];
            }
        }
    }

    //receipt upload
    public function receipt_upload($file_name)
    {
        $config['upload_path'] = './uploads/receipts/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_name'] = 'receipt_' . generate_unique_id();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = ['upload_data' => $this->upload->data()];
            if (isset($data['upload_data']['full_path'])) {
                return 'uploads/receipts/' . $data['upload_data']['file_name'];
            }
        }
    }

    //digital file upload
    public function digital_file_upload($input_name, $file_name)
    {
        $allowed_types = ['zip', 'ZIP'];
        if (!$this->check_file_mime_type($input_name, $allowed_types)) {
            return false;
        }
        $config['upload_path'] = './uploads/digital-files/';
        $config['allowed_types'] = '*';
        $config['file_name'] = $file_name;
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($input_name)) {
            $data = ['upload_data' => $this->upload->data()];
            if (isset($data['upload_data']['full_path'])) {
                return true;
            }

            return;
        }
    }

    //audio upload
    public function audio_upload($file_name)
    {
        $allowed_types = ['mp3', 'MP3', 'wav', 'WAV'];
        if (!$this->check_file_mime_type($file_name, $allowed_types)) {
            return false;
        }
        $config['upload_path'] = './uploads/audios/';
        $config['allowed_types'] = '*';
        $config['file_name'] = 'audio_' . generate_unique_id();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = ['upload_data' => $this->upload->data()];
            if (isset($data['upload_data']['full_path'])) {
                return $data['upload_data']['file_name'];
            }

            return;
        }
    }

    //video upload
    public function video_upload($file_name)
    {
        $allowed_types = ['mp4', 'MP4', 'webm', 'WEBM'];
        if (!$this->check_file_mime_type($file_name, $allowed_types)) {
            return false;
        }
        $config['upload_path'] = './uploads/videos/';
        $config['allowed_types'] = '*';
        $config['file_name'] = 'video_' . generate_unique_id();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = ['upload_data' => $this->upload->data()];
            if (isset($data['upload_data']['full_path'])) {
                return $data['upload_data']['file_name'];
            }

            return;
        }
    }

    //check file mime type
    public function check_file_mime_type($file_name, $allowed_types)
    {
        if (!isset($_FILES[$file_name])) {
            return false;
        }
        if (empty($_FILES[$file_name]['name'])) {
            return false;
        }
        $ext = pathinfo($_FILES[$file_name]['name'], PATHINFO_EXTENSION);
        if (in_array($ext, $allowed_types)) {
            return true;
        }

        return false;
    }

    //delete temp image
    public function delete_temp_image($path)
    {
        if (file_exists($path)) {
            @unlink($path);
        }
    }
}

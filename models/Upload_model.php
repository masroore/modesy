<?php

defined('BASEPATH') or exit('No direct script access allowed');
//include image resize library
include APPPATH . 'third_party/image-resize/ImageResize.php';
include APPPATH . 'third_party/image-resize/ImageResizeException.php';

use Gumlet\ImageResize;
use Gumlet\ImageResizeException;

class Upload_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->img_quality = 85;
    }

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
            $image->quality_jpg = $this->img_quality;
            $image->resizeToHeight(500);
            $new_name = 'img_x500_' . generate_unique_id() . '.jpg';
            $new_path = 'uploads/' . $folder . '/' . $new_name;
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            //add watermark
            if (1 == $this->general_settings->watermark_product_images) {
                $this->add_watermark(FCPATH . $new_path, 'mid');
            }

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
            $image->quality_jpg = $this->img_quality;
            $image->resizeToWidth(1920);
            $new_name = 'img_1920x_' . generate_unique_id() . '.jpg';
            $new_path = 'uploads/' . $folder . '/' . $new_name;
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            //add watermark
            if (1 == $this->general_settings->watermark_product_images) {
                $this->add_watermark(FCPATH . $new_path, 'large');
            }

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
            $image->quality_jpg = $this->img_quality;
            $image->resizeToHeight(300);
            $new_name = 'img_x300_' . generate_unique_id() . '.jpg';
            $new_path = 'uploads/' . $folder . '/' . $new_name;
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            //add watermark
            if (1 == $this->general_settings->watermark_product_images && 1 == $this->general_settings->watermark_thumbnail_images) {
                $this->add_watermark(FCPATH . $new_path, 'small');
            }

            return $new_name;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //product variation small image upload
    public function product_variation_small_image_upload($path, $folder)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = $this->img_quality;
            $image->crop(200, 200, true);
            $new_name = 'img_x200_' . generate_unique_id() . '.jpg';
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
            $image->quality_jpg = $this->img_quality;
            $image->resizeToWidth(1280);
            $new_name = 'img_' . generate_unique_id() . '.jpg';
            $new_path = 'uploads/images-file-manager/' . $new_name;
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            //add watermark
            if (1 == $this->general_settings->watermark_product_images) {
                $this->add_watermark(FCPATH . $new_path, 'mid');
            }

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
            $image->quality_jpg = $this->img_quality;
            $image->resizeToWidth(880);
            $new_path = 'uploads/blog/img_' . generate_unique_id() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            //add watermark
            if (1 == $this->general_settings->watermark_blog_images) {
                $this->add_watermark(FCPATH . $new_path, 'mid');
            }

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
            $image->quality_jpg = $this->img_quality;
            $image->crop(500, 332, true);
            $new_path = 'uploads/blog/img_thumb_' . generate_unique_id() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);
            //add watermark
            if (1 == $this->general_settings->watermark_product_images && 1 == $this->general_settings->watermark_thumbnail_images) {
                $this->add_watermark(FCPATH . $new_path, 'mid');
            }

            return $new_path;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //category image upload
    public function category_image_upload($path)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = $this->img_quality;
            $image->crop(420, 420, true);
            $new_path = 'uploads/category/category_' . generate_unique_id() . '.jpg';
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
            $image->quality_jpg = $this->img_quality;
            $image->crop(1170, 356, true);
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
            $image->quality_jpg = $this->img_quality;
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

    //flag upload
    public function flag_upload($path)
    {
        try {
            $image = new ImageResize($path);
            $image->quality_jpg = $this->img_quality;
            $image->resizeToHeight(100);
            $new_path = 'uploads/blocks/flag_' . generate_unique_id() . '.jpg';
            $image->save(FCPATH . $new_path, IMAGETYPE_JPEG);

            return $new_path;
        } catch (ImageResizeException $e) {
            return;
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

    //watermark upload
    public function watermark_upload($file_name)
    {
        $config['upload_path'] = './uploads/logo/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['file_name'] = 'watermark_' . generate_unique_id();
        $this->load->library('upload', $config);

        if ($this->upload->do_upload($file_name)) {
            $data = ['upload_data' => $this->upload->data()];
            if (isset($data['upload_data']['full_path'])) {
                return 'uploads/logo/' . $data['upload_data']['file_name'];
            }
        }
    }

    //resize watermark
    public function resize_watermark($path, $width, $height)
    {
        try {
            $image = new ImageResize($path);
            $image->resizeToWidth($width);
            $new_name = 'watermark_' . generate_unique_id() . '.png';
            $new_path = 'uploads/logo/' . $new_name;
            $image->save(FCPATH . $new_path, IMAGETYPE_PNG);

            return 'uploads/logo/' . $new_name;
        } catch (ImageResizeException $e) {
            return;
        }
    }

    //digital file upload
    public function digital_file_upload($input_name, $file_name)
    {
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

    //add watermark
    public function add_watermark($image_path, $watermark_size)
    {
        $watermark = $this->general_settings->watermark_image_large;
        if ('mid' == $watermark_size) {
            $watermark = $this->general_settings->watermark_image_mid;
        }
        if ('small' == $watermark_size) {
            $watermark = $this->general_settings->watermark_image_small;
        }
        if (file_exists($image_path) && file_exists($watermark)) {
            $this->load->library('image_lib');
            $config['source_image'] = $image_path;
            $config['wm_overlay_path'] = FCPATH . $watermark;
            $config['wm_type'] = 'overlay';
            $config['wm_vrt_alignment'] = $this->general_settings->watermark_vrt_alignment;
            $config['wm_hor_alignment'] = $this->general_settings->watermark_hor_alignment;
            $this->image_lib->initialize($config);
            $this->image_lib->watermark();
        }
    }

    //delete temp image
    public function delete_temp_image($path)
    {
        if (file_exists($path)) {
            @unlink($path);
        }
    }
}

<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sitemap_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->urls = [];
    }

    //input values
    public function input_values()
    {
        return [
            'frequency' => $this->form_settings->sitemap_frequency,
            'last_modification' => $this->form_settings->sitemap_last_modification,
            'priority' => $this->form_settings->sitemap_priority,
            'lastmod_time' => null,
        ];
    }

    //update sitemap settings
    public function update_sitemap_settings()
    {
        $db_data = [
            'sitemap_frequency' => $this->input->post('frequency', true),
            'sitemap_last_modification' => $this->input->post('last_modification', true),
            'sitemap_priority' => $this->input->post('priority', true),
        ];

        $this->db->where('id', 1);
        $this->db->update('form_settings', $db_data);
    }

    public function add($loc, $changefreq = null, $lastmod = null, $priority = null, $priority_value = null, $lastmod_time = null)
    {
        $item = new stdClass();
        $item->loc = $loc;
        $item->lastmod = $lastmod;
        $item->lastmod_time = $lastmod_time;
        $item->changefreq = $changefreq;
        $item->priority = $priority;
        $item->priority_value = $priority_value;
        $this->urls[] = $item;

        return true;
    }

    /**
     * Generate the sitemap file and replace any output with the valid XML of the sitemap.
     *
     * @param string $type Type of sitemap to be generated. Use 'urlset' for a normal sitemap. Use 'sitemapindex' for a sitemap index file.
     *
     * @return void
     */
    public function output($type = 'urlset')
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><urlset/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        if (!empty($this->urls)) {
            foreach ($this->urls as $url) {
                $child = $xml->addChild('url');
                $child->addChild('loc', strtolower($url->loc));

                if (isset($url->lastmod) && 'none' != $url->lastmod) {
                    if ('server_response' == $url->lastmod) {
                        $child->addChild('lastmod', date('Y-m-d'));
                    } else {
                        $child->addChild('lastmod', $url->lastmod_time);
                    }
                }

                if (isset($url->changefreq) && 'none' != $url->changefreq) {
                    $child->addChild('changefreq', $url->changefreq);
                }

                if (isset($url->priority) && 'none' != $url->priority) {
                    $child->addChild('priority', $url->priority_value);
                }
            }
        }
        header('Content-Disposition: attachment; filename="sitemap.xml"');
        $this->output->set_content_type('application/xml')->set_output($xml->saveXML());
    }

    /**
     * Clear all items in the sitemap to be generated.
     *
     * @return bool
     */
    public function clear()
    {
        $this->urls = [];

        return true;
    }

    /**
     * Base Url.
     */
    public function get_base_url($lang_id)
    {
        if ($lang_id == $this->general_settings->site_lang) {
            return base_url();
        }
        $lang = get_language($lang_id);
        if (!empty($lang)) {
            return base_url() . $lang->short_form . '/';
        }
    }

    /**
     * Static Page Urls.
     */
    public function add_static_urls($frequency, $last_modification, $priority, $lastmod_time)
    {
        $priority_value = 0.8;

        $this->sitemap_model->add(base_url(), $frequency, $last_modification, '1', '1', $lastmod_time);
        $this->sitemap_model->add(base_url() . 'blog', $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
        $this->sitemap_model->add(base_url() . 'contact', $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
        $this->sitemap_model->add(base_url() . 'search', $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
        $this->sitemap_model->add(base_url() . 'products', $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
        $this->sitemap_model->add(base_url() . 'register', $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
    }

    /**
     * Page Urls.
     */
    public function add_page_urls($frequency, $last_modification, $priority, $lastmod_time)
    {
        $pages = $this->page_model->get_pages_all();
        if (!empty($pages)) {
            foreach ($pages as $page) {
                if (empty($page->link)) {
                    $base_url = $this->get_base_url($page->lang_id);
                    $priority_value = 0.8;
                    $this->sitemap_model->add($base_url . $page->slug, $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
                } else {
                    $priority_value = 0.8;
                    $this->sitemap_model->add($page->link, $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
                }
            }
        }
    }

    /**
     * Product Category Urls.
     */
    public function add_product_category_urls($frequency, $last_modification, $priority, $lastmod_time)
    {
        $priority_value = 0.8;

        $categories = $this->category_model->get_sitemap_categories();
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $this->sitemap_model->add(generate_category_url($category), $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
            }
        }
    }

    /**
     * Product Urls.
     */
    public function add_product_urls($frequency, $last_modification, $priority, $lastmod_time)
    {
        $priority_value = 0.8;

        $products = $this->product_model->get_products();
        if (!empty($products)) {
            foreach ($products as $product) {
                $this->sitemap_model->add(generate_product_url($product), $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
            }
        }
    }

    /**
     * Blog Category Urls.
     */
    public function add_blog_category_urls($frequency, $last_modification, $priority, $lastmod_time)
    {
        $priority_value = 0.8;

        $categories = $this->blog_category_model->get_categories_all();
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $base_url = $this->get_base_url($category->lang_id);
                $this->sitemap_model->add($base_url . 'blog/' . $category->slug, $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
            }
        }
    }

    /**
     * Post Urls.
     */
    public function add_blog_post_urls($frequency, $last_modification, $priority, $lastmod_time)
    {
        $posts = $this->blog_model->get_posts_all_joined();
        $priority_value = 0.8;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $base_url = $this->get_base_url($post->lang_id);
                $this->sitemap_model->add($base_url . 'blog/' . $post->category_slug . '/' . $post->slug, $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
            }
        }
    }

    /**
     * Blog Tag Urls.
     */
    public function add_blog_tag_urls($frequency, $last_modification, $priority, $lastmod_time)
    {
        $tags = $this->tag_model->get_tags_all();
        $priority_value = 0.8;
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $tag = $this->tag_model->get_post_tag($tag->tag_slug);
                $base_url = $this->get_base_url($tag->tag_lang_id);
                $this->sitemap_model->add($base_url . 'blog/tag/' . $tag->tag_slug, $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
            }
        }
    }

    public function download_sitemap()
    {
        $data = $this->input_values();
        $this->add_static_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->add_page_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->add_product_category_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->add_product_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->add_blog_category_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->add_blog_post_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->add_blog_tag_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->sitemap_model->output('sitemapindex');
    }

    public function update_sitemap()
    {
        $full_path = FCPATH . 'sitemap.xml';
        if (file_exists($full_path)) {
            unlink($full_path);
        }
        $data = $this->input_values();
        $this->add_static_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->add_page_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->add_product_category_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->add_product_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->add_blog_category_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->add_blog_post_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);
        $this->add_blog_tag_urls($data['frequency'], $data['last_modification'], $data['priority'], $data['lastmod_time']);

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><urlset/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        if (!empty($this->urls)) {
            foreach ($this->urls as $url) {
                $child = $xml->addChild('url');
                $child->addChild('loc', strtolower($url->loc));

                if (isset($url->lastmod) && 'none' != $url->lastmod) {
                    if ('server_response' == $url->lastmod) {
                        $child->addChild('lastmod', date('Y-m-d'));
                    } else {
                        $child->addChild('lastmod', $url->lastmod_time);
                    }
                }

                if (isset($url->changefreq) && 'none' != $url->changefreq) {
                    $child->addChild('changefreq', $url->changefreq);
                }

                if (isset($url->priority) && 'none' != $url->priority) {
                    $child->addChild('priority', $url->priority_value);
                }
            }
        }
        $xml->saveXML($full_path);
    }

    //generate blog url
    public function generate_post_url($post, $base_url)
    {
        if (!empty($post)) {
            return $base_url . 'blog' . '/' . $post->category_slug . '/' . $post->slug;
        }
    }
}

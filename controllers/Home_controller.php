<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Home_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->comment_limit = 6;
        $this->blog_paginate_per_page = 12;
        $this->promoted_products_limit = $this->general_settings->index_promoted_products_count;
    }

    /**
     * Index.
     */
    public function index()
    {
        get_method();
        $data['title'] = $this->settings->homepage_title;
        $data['description'] = $this->settings->site_description;
        $data['keywords'] = $this->settings->keywords;

        //products
        $data['latest_products'] = get_latest_products($this->general_settings->index_latest_products_count);
        $data['promoted_products'] = get_promoted_products(0, $this->promoted_products_limit);
        $data['promoted_products_count'] = get_promoted_products_count();

        $data['slider_items'] = $this->slider_model->get_slider_items();
        $data['featured_categories'] = $this->category_model->get_featured_categories();
        $data['featured_categories'] = $this->category_model->get_featured_categories();

        //blog slider posts
        $key = 'blog_slider_posts_lang_' . $this->selected_lang->id;
        $data['blog_slider_posts'] = get_cached_data($key);
        if (empty($data['blog_slider_posts'])) {
            $data['blog_slider_posts'] = $this->blog_model->get_latest_posts(8);
            set_cache_data($key, $data['blog_slider_posts']);
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('index', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Contact.
     */
    public function contact()
    {
        get_method();
        $data['title'] = trans('contact');
        $data['description'] = trans('contact') . ' - ' . $this->app_name;
        $data['keywords'] = trans('contact') . ',' . $this->app_name;

        $this->load->view('partials/_header', $data);
        $this->load->view('contact', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Contact Page Post.
     */
    public function contact_post()
    {
        post_method();
        //validate inputs
        $this->form_validation->set_rules('name', trans('name'), 'required|xss_clean|max_length[200]');
        $this->form_validation->set_rules('email', trans('email_address'), 'required|xss_clean|max_length[200]');
        $this->form_validation->set_rules('message', trans('message'), 'required|xss_clean|max_length[5000]');

        if (false === $this->form_validation->run()) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->contact_model->input_values());
            redirect($this->agent->referrer());
        } else {
            if (!$this->recaptcha_verify_request()) {
                $this->session->set_flashdata('form_data', $this->contact_model->input_values());
                $this->session->set_flashdata('error', trans('msg_recaptcha'));
                redirect($this->agent->referrer());
            } else {
                if ($this->contact_model->add_contact_message()) {
                    $this->session->set_flashdata('success', trans('msg_contact_success'));
                    redirect($this->agent->referrer());
                } else {
                    $this->session->set_flashdata('form_data', $this->contact_model->input_values());
                    $this->session->set_flashdata('error', trans('msg_contact_error'));
                    redirect($this->agent->referrer());
                }
            }
        }
    }

    /**
     * Dynamic Page by Name Slug.
     */
    public function any($slug)
    {
        get_method();
        $slug = clean_slug($slug);
        //index page
        if (empty($slug)) {
            redirect(lang_base_url());
        }

        $page = $this->page_model->get_page($slug);
        //if exists
        if (!empty($page)) {
            $this->page($page);
        } else {
            //check category
            $category = $this->category_model->get_parent_category_by_slug($slug);
            if (!empty($category)) {
                $this->category($category);
            } else {
                $this->product($slug);
            }
        }
    }

    /**
     * Products.
     */
    public function products()
    {
        get_method();
        $data['title'] = trans('products');
        $data['description'] = trans('products') . ' - ' . $this->app_name;
        $data['keywords'] = trans('products') . ',' . $this->app_name;
        //get paginated posts
        $link = generate_url('products');
        $pagination = $this->paginate($link, $this->product_model->get_paginated_filtered_products_count(null), $this->product_paginate_per_page);
        $data['products'] = $this->product_model->get_paginated_filtered_products(null, $pagination['offset'], $pagination['per_page']);
        $data['categories'] = get_parent_categories($this->categories);

        $data['show_location_filter'] = false;
        if (!empty($data['products'])) {
            foreach ($data['products'] as $item) {
                if ('physical' == $item->product_type) {
                    $data['show_location_filter'] = true;
                    break;
                }
            }
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('product/products', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Category.
     */
    public function category($category)
    {
        if (empty($category)) {
            redirect($this->agent->referrer());
        }
        $data['category'] = $category;
        $data['title'] = !empty($data['category']->title_meta_tag) ? $data['category']->title_meta_tag : $data['category']->name;
        $data['description'] = $data['category']->description;
        $data['keywords'] = $data['category']->keywords;

        $data['categories'] = $this->category_model->get_subcategories_by_parent_id($data['category']->id);
        if (empty($data['categories'])) {
            $data['categories'] = $this->category_model->get_subcategories_by_parent_id($data['category']->parent_id);
        }

        //get paginated posts
        $link = generate_category_url($data['category']);
        $pagination = $this->paginate($link, $this->product_model->get_paginated_filtered_products_count($data['category']->id), $this->product_paginate_per_page);
        $data['products'] = $this->product_model->get_paginated_filtered_products($data['category']->id, $pagination['offset'], $pagination['per_page']);

        $data['show_location_filter'] = false;
        if (!empty($data['products'])) {
            foreach ($data['products'] as $item) {
                if ('physical' == $item->product_type) {
                    $data['show_location_filter'] = true;
                    break;
                }
            }
        }

        if (0 == $data['category']->parent_id) {
            $data['parent_category'] = null;
        } else {
            $data['parent_category'] = $this->category_model->get_category($data['category']->parent_id);
        }
        $this->load->view('partials/_header', $data);
        $this->load->view('product/products', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * SubCategory.
     */
    public function subcategory($parent_slug, $slug)
    {
        get_method();
        $slug = clean_slug($slug);
        $category = $this->category_model->get_category_by_slug($slug);
        if (!empty($category)) {
            $this->category($category);
        } else {
            $this->error_404();
        }
    }

    /**
     * Product.
     */
    public function product($slug)
    {
        get_method();
        $slug = clean_slug($slug);
        $this->review_limit = 5;
        $this->comment_limit = 5;

        $data['product'] = $this->product_model->get_product_by_slug($slug);
        if (empty($data['product'])) {
            $this->error_404();
        } else {
            if (0 == $data['product']->status || 0 == $data['product']->visibility) {
                if (!$this->auth_check) {
                    redirect(lang_base_url());
                }
                if ($data['product']->user_id != $this->auth_user->id && 'admin' != $this->auth_user->role) {
                    redirect(lang_base_url());
                }
            }

            $data['category'] = $this->category_model->get_category($data['product']->category_id);

            //images
            $data['product_images'] = $this->file_model->get_product_images($data['product']->id);

            //related products
            $key = 'related_products_' . $data['product']->id;
            $data['related_products'] = get_cached_data($key);
            if (empty($data['related_products'])) {
                $data['related_products'] = $this->product_model->get_related_products($data['product']);
                set_cache_data($key, $data['related_products']);
            }

            $data['user'] = $this->auth_model->get_user($data['product']->user_id);

            //user products
            $key = 'more_products_by_user_' . $data['user']->id . 'cache';
            $data['user_products'] = get_cached_data($key);
            if (empty($data['user_products'])) {
                $data['user_products'] = $this->product_model->get_user_products($data['user']->id, $data['product']->id);
                set_cache_data($key, $data['user_products']);
            }

            $data['review_count'] = $this->review_model->get_review_count($data['product']->id);
            $data['reviews'] = $this->review_model->get_limited_reviews($data['product']->id, $this->review_limit);
            $data['review_limit'] = $this->review_limit;

            $data['comment_count'] = $this->comment_model->get_product_comment_count($data['product']->id);
            $data['comments'] = $this->comment_model->get_comments($data['product']->id, $this->comment_limit);
            $data['comment_limit'] = $this->comment_limit;
            $data['custom_fields'] = $this->field_model->generate_custom_fields_array($data['product']->category_id, $data['product']->id);
            $data['half_width_product_variations'] = $this->variation_model->get_half_width_product_variations($data['product']->id);
            $data['full_width_product_variations'] = $this->variation_model->get_full_width_product_variations($data['product']->id);

            $data['video'] = $this->file_model->get_product_video($data['product']->id);
            $data['audio'] = $this->file_model->get_product_audio($data['product']->id);

            $data['digital_sale'] = null;
            if ('digital' == $data['product']->product_type && $this->auth_check) {
                $data['digital_sale'] = get_digital_sale_by_buyer_id($this->auth_user->id, $data['product']->id);
            }
            //og tags
            $data['show_og_tags'] = true;
            $data['og_title'] = $data['product']->title;
            $description_text = trim(html_escape(strip_tags($data['product']->description)));
            $data['og_description'] = character_limiter($description_text, 200, '');
            $data['og_type'] = 'article';
            $data['og_url'] = generate_product_url($data['product']);
            $data['og_image'] = get_product_image($data['product']->id, 'image_default');
            $data['og_width'] = '750';
            $data['og_height'] = '500';
            if (!empty($data['user'])) {
                $data['og_creator'] = $data['user']->username;
                $data['og_author'] = $data['user']->username;
            } else {
                $data['og_creator'] = '';
                $data['og_author'] = '';
            }
            $data['og_published_time'] = $data['product']->created_at;
            $data['og_modified_time'] = $data['product']->created_at;

            $data['title'] = $data['product']->title;
            $data['description'] = character_limiter($description_text, 200, '');
            $data['keywords'] = generate_product_keywords($data['product']->title);

            $this->load->view('partials/_header', $data);
            $this->load->view('product/details/product', $data);
            $this->load->view('partials/_footer');
            //increase hit
            $this->product_model->increase_product_hit($data['product']);
        }
    }

    /**
     * Load More Promoted Products.
     */
    public function load_more_promoted_products()
    {
        post_method();
        $offset = clean_number($this->input->post('offset', true));
        $promoted_products = get_promoted_products($offset, $this->promoted_products_limit);

        $data_json = [
            'result' => 0,
            'html_content' => '',
            'offset' => $offset + $this->promoted_products_limit,
            'hide_button' => 0,
        ];
        $html_content = '';
        if (!empty($promoted_products)) {
            foreach ($promoted_products as $product) {
                $vars = ['product' => $product, 'promoted_badge' => false];
                $html_content .= '<div class="col-6 col-sm-6 col-md-4 col-lg-3 col-product">' . $this->load->view('product/_product_item', $vars, true) . '</div>';
            }
            $data_json['result'] = 1;
            $data_json['html_content'] = $html_content;
            if ($offset + $this->promoted_products_limit >= get_promoted_products_count()) {
                $data_json['hide_button'] = 1;
            }
        }
        echo json_encode($data_json);
    }

    /**
     * Search.
     */
    public function search()
    {
        get_method();
        $search = trim($this->input->get('search', true));
        $search_type = $this->input->get('search_type', true);
        $search = remove_special_characters($search);

        if (empty($search)) {
            redirect(lang_base_url());
        }

        if ('product' == $search_type) {
            redirect(generate_url('products') . '?search=' . $search);
        } else {
            redirect(generate_url('members') . '?search=' . $search);
        }
    }

    /**
     * Members.
     */
    public function members()
    {
        get_method();
        $search = trim($this->input->get('search', true));
        $search = remove_special_characters($search);

        if (empty($search)) {
            redirect(lang_base_url());
        }

        $data['members'] = $this->profile_model->search_members($search);

        $data['title'] = $search . ' - ' . trans('members');
        $data['description'] = $search . ' - ' . trans('members') . ' - ' . $this->app_name;
        $data['keywords'] = $search . ', ' . trans('members') . ',' . $this->app_name;

        $data['filter_search'] = $this->input->get('search');
        $data['search_type'] = 'member';

        $this->load->view('partials/_header', $data);
        $this->load->view('members', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Blog Category.
     */
    public function blog_category($slug)
    {
        get_method();
        $slug = clean_slug($slug);
        $data['category'] = $this->blog_category_model->get_category_by_slug($slug);

        if (empty($data['category'])) {
            redirect(generate_url('blog'));
        }

        $data['title'] = $data['category']->name;
        $data['description'] = $data['category']->description;
        $data['keywords'] = $data['category']->keywords;
        $data['active_category'] = $data['category']->slug;
        $key = 'blog_category_' . $data['category']->id . '_posts_count_lang_' . $this->selected_lang->id;
        $blog_posts_count = get_cached_data($key);
        if (empty($blog_posts_count)) {
            $blog_posts_count = count($this->blog_model->get_posts_by_category($data['category']->id));
            set_cache_data($key, $blog_posts_count);
        }

        //set pagination
        $pagination = $this->paginate(generate_url('blog') . '/' . $data['category']->slug, $blog_posts_count, $this->blog_paginate_per_page);
        $key = 'blog_category_' . $data['category']->id . 'posts_lang_' . $this->selected_lang->id . '_page_' . $pagination['current_page'];
        $data['posts'] = get_cached_data($key);
        if (empty($data['posts'])) {
            $data['posts'] = $this->blog_model->get_paginated_category_posts($pagination['offset'], $pagination['per_page'], $data['category']->id);
            set_cache_data($key, $data['posts']);
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('blog/index', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Tag.
     */
    public function tag($slug)
    {
        get_method();
        $slug = clean_slug($slug);
        $data['tag'] = $this->tag_model->get_post_tag($slug);

        if (empty($data['tag'])) {
            redirect(generate_url('blog'));
        }

        $data['title'] = $data['tag']->tag;
        $data['description'] = trans('tag') . ': ' . $data['tag']->tag . ' - ' . $this->app_name;
        $data['keywords'] = trans('tag') . ',' . $data['tag']->tag . ',' . $this->app_name;
        //get paginated posts
        $pagination = $this->paginate(generate_url('blog', 'tag') . ' / ' . $data['tag']->tag_slug, $this->blog_model->get_paginated_tag_posts_count($data['tag']->tag_slug), $this->blog_paginate_per_page);
        $data['posts'] = $this->blog_model->get_paginated_tag_posts($pagination['offset'], $pagination['per_page'], $data['tag']->tag_slug);

        $this->load->view('partials/_header', $data);
        $this->load->view('blog/tag', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Post.
     */
    public function post($category_slug, $slug)
    {
        get_method();
        $slug = clean_slug($slug);
        $data['post'] = $this->blog_model->get_post_by_slug($slug);

        if (empty($data['post'])) {
            redirect(generate_url('blog'));
        }

        $data['title'] = $data['post']->title;
        $data['description'] = $data['post']->summary;
        $data['keywords'] = $data['post']->keywords;

        $data['related_posts'] = $this->blog_model->get_related_posts($data['post']->category_id, $data['post']->id);
        $data['latest_posts'] = $this->blog_model->get_latest_posts(3);
        $data['random_tags'] = $this->tag_model->get_random_post_tags();
        $data['post_tags'] = $this->tag_model->get_post_tags($data['post']->id);
        $data['comments'] = $this->comment_model->get_blog_comments($data['post']->id, $this->comment_limit);
        $data['comments_count'] = $this->comment_model->get_blog_comment_count($data['post']->id);
        $data['comment_limit'] = $this->comment_limit;
        $data['post_user'] = $this->auth_model->get_user($data['post']->user_id);
        $data['category'] = $this->blog_category_model->get_category($data['post']->category_id);

        //og tags
        $data['show_og_tags'] = true;
        $data['og_title'] = $data['post']->title;
        $data['og_description'] = $data['post']->summary;
        $data['og_type'] = 'article';
        $data['og_url'] = generate_url('blog') . ' / ' . $data['post']->category_slug . ' / ' . $data['post']->slug;
        $data['og_image'] = get_blog_image_url($data['post'], 'image_default');
        $data['og_width'] = '750';
        $data['og_height'] = '500';
        if (!empty($data['post_user'])) {
            $data['og_creator'] = $data['post_user']->username;
            $data['og_author'] = $data['post_user']->username;
        } else {
            $data['og_creator'] = '';
            $data['og_author'] = '';
        }
        $data['og_published_time'] = $data['post']->created_at;
        $data['og_modified_time'] = $data['post']->created_at;
        $data['og_tags'] = $data['post_tags'];

        $this->load->view('partials/_header', $data);
        $this->load->view('blog/post', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Guest Wishlist.
     */
    public function guest_wishlist()
    {
        $data['title'] = trans('wishlist');
        $data['description'] = trans('wishlist') . ' - ' . $this->app_name;
        $data['keywords'] = trans('wishlist') . ',' . $this->app_name;
        $data['wishlist'] = $this->session->userdata('mds_guest_wishlist');

        $this->load->view('partials/_header', $data);
        $this->load->view('guest_wishlist', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Unsubscribe.
     */
    public function unsubscribe()
    {
        $data['title'] = trans('unsubscribe');
        $data['description'] = trans('unsubscribe');
        $data['keywords'] = trans('unsubscribe');

        $token = $this->input->get('token');
        $token = remove_special_characters($token);
        $subscriber = $this->newsletter_model->get_subscriber_by_token($token);

        if (empty($subscriber)) {
            redirect(lang_base_url());
        }
        $this->newsletter_model->unsubscribe_email($subscriber->email);

        $this->load->view('partials/_header', $data);
        $this->load->view('unsubscribe');
        $this->load->view('partials/_footer');
    }

    /**
     * Add to Subscribers.
     */
    public function add_to_subscribers()
    {
        //input values
        $email = $this->input->post('email', true);

        if ($email) {
            //check if email exists
            if (empty($this->newsletter_model->get_subscriber($email))) {
                //addd
                if ($this->newsletter_model->add_to_subscribers($email)) {
                    $this->session->set_flashdata('news_success', trans('msg_newsletter_success'));
                }
            } else {
                $this->session->set_flashdata('news_error', trans('msg_newsletter_error'));
            }
        }
        redirect($this->agent->referrer() . '#newsletter');
    }

    public function cookies_warning()
    {
        setcookie('modesy_cookies_warning', '1', time() + (86400 * 10), '/'); //10 days
    }

    public function set_default_location()
    {
        $location_id = $this->input->post('location_id', true);
        if (!empty($location_id)) {
            $this->session->set_userdata('mds_default_location_id', $location_id);
        } else {
            @$this->session->unset_userdata('mds_default_location_id');
        }
        redirect($this->agent->referrer());
    }

    public function error_404()
    {
        get_method();
        header('HTTP/1.0 404 Not Found');
        $data['title'] = 'Error 404';
        $data['description'] = 'Error 404';
        $data['keywords'] = 'error,404';

        $this->load->view('partials/_header', $data);
        $this->load->view('errors/error_404');
        $this->load->view('partials/_footer');
    }

    /**
     * Page.
     */
    private function page($page)
    {
        if (empty($page)) {
            redirect(lang_base_url());
        }
        if (0 == $page->visibility) {
            $this->error_404();
        } else {
            $data['title'] = $page->title;
            $data['description'] = $page->description;
            $data['keywords'] = $page->keywords;
            $data['page'] = $page;
            if ('blog' == $page->page_default_name) {
                $this->blog($data);
            } elseif ('contact' == $page->page_default_name) {
                $this->load->view('partials/_header', $data);
                $this->load->view('contact', $data);
                $this->load->view('partials/_footer');
            } else {
                $this->load->view('partials/_header', $data);
                $this->load->view('page', $data);
                $this->load->view('partials/_footer');
            }
        }
    }

    /*
    *-------------------------------------------------------------------------------------------------
    * BLOG PAGES
    *-------------------------------------------------------------------------------------------------
    */

    /**
     * Blog.
     */
    private function blog($data)
    {
        get_method();
        $data['active_category'] = 'all';
        $key = 'blog_posts_count_lang_' . $this->selected_lang->id;
        $blog_posts_count = get_cached_data($key);
        if (empty($blog_posts_count)) {
            $blog_posts_count = $this->blog_model->get_posts_count();
            set_cache_data($key, $blog_posts_count);
        }

        //set pagination
        $pagination = $this->paginate(generate_url('blog'), $blog_posts_count, $this->blog_paginate_per_page);
        $key = 'blog_posts_lang_' . $this->selected_lang->id . '_page_' . $pagination['current_page'];
        $data['posts'] = get_cached_data($key);
        if (empty($data['posts'])) {
            $data['posts'] = $this->blog_model->get_paginated_posts($pagination['offset'], $pagination['per_page']);
            set_cache_data($key, $data['posts']);
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('blog/index', $data);
        $this->load->view('partials/_footer');
    }
}

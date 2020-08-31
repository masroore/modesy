<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();

        //default location
        $this->default_location_id = 0;
        if (!empty($this->session->userdata('modesy_default_location'))) {
            $this->default_location_id = $this->session->userdata('modesy_default_location');
        }
    }

    //add product
    public function add_product()
    {
        $data = [
            'title' => $this->input->post('title', true),
            'product_type' => $this->input->post('product_type', true),
            'listing_type' => $this->input->post('listing_type', true),
            'category_id' => $this->input->post('category_id', true),
            'subcategory_id' => $this->input->post('subcategory_id', true),
            'third_category_id' => $this->input->post('third_category_id', true),
            'price' => 0,
            'currency' => '',
            'description' => $this->input->post('description', false),
            'product_condition' => '',
            'country_id' => 0,
            'state_id' => 0,
            'city_id' => 0,
            'address' => '',
            'zip_code' => '',
            'user_id' => user()->id,
            'status' => 0,
            'is_promoted' => 0,
            'promote_start_date' => date('Y-m-d H:i:s'),
            'promote_end_date' => date('Y-m-d H:i:s'),
            'promote_plan' => 'none',
            'promote_day' => 0,
            'visibility' => 1,
            'rating' => 0,
            'hit' => 0,
            'demo_url' => '',
            'external_link' => '',
            'files_included' => '',
            'quantity' => 1,
            'shipping_time' => '',
            'shipping_cost_type' => '',
            'shipping_cost' => 0,
            'is_sold' => 0,
            'is_deleted' => 0,
            'is_draft' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $data['slug'] = str_slug($data['title']);
        if (empty($data['subcategory_id'])) {
            $data['subcategory_id'] = 0;
        }
        if (empty($data['third_category_id'])) {
            $data['third_category_id'] = 0;
        }
        if (empty($data['country_id'])) {
            $data['country_id'] = 0;
        }
        if (0 == $this->general_settings->approve_before_publishing) {
            $data['status'] = 1;
        }

        return $this->db->insert('products', $data);
    }

    //edit draft
    public function edit_draft($id)
    {
        $id = clean_number($id);
        $data = [
            'title' => $this->input->post('title', true),
            'product_type' => $this->input->post('product_type', true),
            'listing_type' => $this->input->post('listing_type', true),
            'category_id' => $this->input->post('category_id', true),
            'subcategory_id' => $this->input->post('subcategory_id', true),
            'third_category_id' => $this->input->post('third_category_id', true),
            'description' => $this->input->post('description', false),
        ];
        $data['slug'] = str_slug($data['title']);
        if (empty($data['subcategory_id'])) {
            $data['subcategory_id'] = 0;
        }
        if (empty($data['third_category_id'])) {
            $data['third_category_id'] = 0;
        }
        $this->db->where('id', $id);

        return $this->db->update('products', $data);
    }

    //edit product details
    public function edit_product_details($id)
    {
        $id = clean_number($id);
        $product = $this->get_product_by_id($id);
        $data = [
            'price' => $this->input->post('price', true),
            'currency' => $this->input->post('currency', true),
            'product_condition' => $this->input->post('product_condition', true),
            'country_id' => $this->input->post('country_id', true),
            'state_id' => $this->input->post('state_id', true),
            'city_id' => $this->input->post('city_id', true),
            'address' => $this->input->post('address', true),
            'zip_code' => $this->input->post('zip_code', true),
            'demo_url' => trim($this->input->post('demo_url', true)),
            'external_link' => trim($this->input->post('external_link', true)),
            'files_included' => trim($this->input->post('files_included', true)),
            'quantity' => $this->input->post('quantity', true),
            'shipping_time' => $this->input->post('shipping_time', true),
            'shipping_cost_type' => $this->input->post('shipping_cost_type', true),
            'is_draft' => 0,
        ];

        $data['price'] = price_database_format($data['price']);
        if (empty($data['price'])) {
            $data['price'] = 0;
        }
        if (empty($data['product_condition'])) {
            $data['product_condition'] = '';
        }
        if (empty($data['country_id'])) {
            $data['country_id'] = 0;
        }
        if (empty($data['state_id'])) {
            $data['state_id'] = 0;
        }
        if (empty($data['city_id'])) {
            $data['city_id'] = 0;
        }
        if (empty($data['address'])) {
            $data['address'] = '';
        }
        if (empty($data['zip_code'])) {
            $data['zip_code'] = '';
        }
        if (empty($data['external_link'])) {
            $data['external_link'] = '';
        }
        if (empty($data['quantity'])) {
            $data['quantity'] = 1;
        }

        if (1 == $this->settings_model->is_shipping_option_require_cost($data['shipping_cost_type'])) {
            $data['shipping_cost'] = $this->input->post('shipping_cost', true);
            $data['shipping_cost'] = price_database_format($data['shipping_cost']);
        } else {
            $data['shipping_cost'] = 0;
        }

        if ('save_as_draft' == $this->input->post('submit', true)) {
            $data['is_draft'] = 1;
        }

        $this->db->where('id', $id);

        return $this->db->update('products', $data);
    }

    //edit product
    public function edit_product($id)
    {
        $id = clean_number($id);
        $data = [
            'title' => $this->input->post('title', true),
            'product_type' => $this->input->post('product_type', true),
            'listing_type' => $this->input->post('listing_type', true),
            'category_id' => $this->input->post('category_id', true),
            'subcategory_id' => $this->input->post('subcategory_id', true),
            'third_category_id' => $this->input->post('third_category_id', true),
            'description' => $this->input->post('description', false),
        ];
        $data['slug'] = str_slug($data['title']);
        if (empty($data['subcategory_id'])) {
            $data['subcategory_id'] = 0;
        }
        if (empty($data['third_category_id'])) {
            $data['third_category_id'] = 0;
        }
        $is_sold = $this->input->post('status_sold', true);
        if ('active' == $is_sold) {
            $data['is_sold'] = 0;
        } elseif ('sold' == $is_sold) {
            $data['is_sold'] = 1;
        }
        if (is_admin()) {
            $data['visibility'] = $this->input->post('visibility', true);
        }
        $this->db->where('id', $id);

        return $this->db->update('products', $data);
    }

    //update custom fields
    public function update_product_custom_fields($product_id)
    {
        $product_id = clean_number($product_id);
        $product = $this->get_product_by_id($product_id);
        if (!empty($product)) {
            $custom_fields = $this->field_model->generate_custom_fields_array($product->category_id, $product->subcategory_id, $product->third_category_id, null);
            if (!empty($custom_fields)) {
                foreach ($custom_fields as $custom_field) {
                    //check field values
                    $field_values = $this->field_model->get_product_custom_field_values($custom_field->id, $product_id);
                    $input_value = $this->input->post('field_' . $custom_field->id, true);

                    //update custom field values
                    if (!empty($field_values)) {
                        if ('checkbox' == $custom_field->field_type) {
                            $this->update_checkbox_selected_values($custom_field, $field_values, $input_value, $product_id);
                        } else {
                            $field_value_id = 0;
                            if (isset($field_values[0]->id)) {
                                $field_value_id = $field_values[0]->id;
                            }
                            if (!empty($field_value_id)) {
                                if ('radio_button' == $custom_field->field_type || 'dropdown' == $custom_field->field_type) {
                                    $data = [
                                        'selected_option_common_id' => $input_value,
                                    ];
                                } else {
                                    $data = [
                                        'field_value' => $input_value,
                                    ];
                                }
                                $this->db->where('id', $field_value_id);
                                $this->db->update('custom_fields_product', $data);
                            }
                        }
                    } else {
                        //add custom field values
                        if (!empty($input_value)) {
                            if ('checkbox' == $custom_field->field_type) {
                                foreach ($input_value as $key => $value) {
                                    $data = [
                                        'field_id' => $custom_field->id,
                                        'product_id' => $product_id,
                                        'product_filter_key' => $custom_field->product_filter_key,
                                    ];
                                    $data['field_value'] = '';
                                    $data['selected_option_common_id'] = $value;
                                    $this->db->insert('custom_fields_product', $data);
                                }
                            } else {
                                $data = [
                                    'field_id' => $custom_field->id,
                                    'product_id' => $product_id,
                                    'product_filter_key' => $custom_field->product_filter_key,
                                ];
                                if ('radio_button' == $custom_field->field_type || 'dropdown' == $custom_field->field_type) {
                                    $data['field_value'] = '';
                                    $data['selected_option_common_id'] = $input_value;
                                } else {
                                    $data['field_value'] = $input_value;
                                    $data['selected_option_common_id'] = '';
                                }
                                $this->db->insert('custom_fields_product', $data);
                            }
                        }
                    }
                }
            }
        }
    }

    //update selected checkbox values
    public function update_checkbox_selected_values($custom_field, $field_values, $input_value, $product_id)
    {
        $product_id = clean_number($product_id);
        if (count($field_values) == count($input_value)) {
            if (!empty($field_values)) {
                $i = 0;
                foreach ($field_values as $item) {
                    $data = [
                        'field_id' => $custom_field->id,
                        'product_id' => $product_id,
                        'product_filter_key' => $custom_field->product_filter_key,
                        'field_value' => '',
                        'selected_option_common_id' => @$input_value[$i],
                    ];
                    $this->db->where('id', $item->id);
                    $this->db->update('custom_fields_product', $data);
                    $i++;
                }
            }
        } else {
            //delete old values
            $this->field_model->delete_field_product_values($custom_field->id);
            //add new values
            if (!empty($input_value)) {
                foreach ($input_value as $key => $value) {
                    $data = [
                        'field_id' => $custom_field->id,
                        'product_id' => $product_id,
                        'product_filter_key' => $custom_field->product_filter_key,
                        'field_value' => '',
                        'selected_option_common_id' => $value,
                    ];
                    $this->db->insert('custom_fields_product', $data);
                }
            }
        }
    }

    //update slug
    public function update_slug($id)
    {
        $id = clean_number($id);
        $product = $this->get_product_by_id($id);

        if (empty($product->slug) || '-' == $product->slug) {
            $data = [
                'slug' => $product->id,
            ];
        } else {
            if ('id-slug' == $this->general_settings->product_link_structure) {
                $data = [
                    'slug' => $product->id . '-' . $product->slug,
                ];
            } else {
                $data = [
                    'slug' => $product->slug . '-' . $product->id,
                ];
            }
        }

        if (!empty($this->page_model->check_page_slug_for_product($data['slug']))) {
            $data['slug'] .= uniqid();
        }

        $this->db->where('id', $id);

        return $this->db->update('products', $data);
    }

    //build query
    public function build_query()
    {
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug');
        $this->db->where('users.banned', 0);
        $this->db->where('users.role !=', 'member');
        $this->db->where('products.status', 1);
        $this->db->where('products.visibility', 1);
        $this->db->where('products.is_draft', 0);
        $this->db->where('products.is_deleted', 0);

        //default location
        if (0 != $this->default_location_id) {
            $this->db->where('products.country_id', $this->default_location_id);
        }
    }

    //build query unlocated
    public function build_query_unlocated()
    {
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug');
        $this->db->where('users.banned', 0);
        $this->db->where('users.role !=', 'member');
        $this->db->where('products.status', 1);
        $this->db->where('products.visibility', 1);
        $this->db->where('products.is_draft', 0);
        $this->db->where('products.is_deleted', 0);
    }

    //filter products
    public function filter_products($category_id, $subcategory_id, $third_category_id)
    {
        $category_id = clean_number($category_id);
        $subcategory_id = clean_number($subcategory_id);
        $third_category_id = clean_number($third_category_id);

        $country = clean_number($this->input->get('country', true));
        $state = clean_number($this->input->get('state', true));
        $city = clean_number($this->input->get('city', true));
        $condition = remove_special_characters($this->input->get('condition', true));
        $p_min = remove_special_characters($this->input->get('p_min', true));
        $p_max = remove_special_characters($this->input->get('p_max', true));
        $sort = remove_special_characters($this->input->get('sort', true));
        $search = remove_special_characters(trim($this->input->get('search', true)));

        //check if custom filters selected
        $custom_filters = [];
        $session_custom_filters = get_sess_product_filters();
        $query_string_filters = get_filters_query_string_array();
        $array_queries = [];
        if (!empty($session_custom_filters)) {
            foreach ($session_custom_filters as $filter) {
                if (isset($query_string_filters[$filter->product_filter_key])) {
                    $item = new stdClass();
                    $item->product_filter_key = $filter->product_filter_key;
                    $item->product_filter_value = @$query_string_filters[$filter->product_filter_key];
                    array_push($custom_filters, $item);
                }
            }
        }
        if (!empty($custom_filters)) {
            foreach ($custom_filters as $filter) {
                if (!empty($filter)) {
                    $filter->product_filter_key = remove_special_characters($filter->product_filter_key);
                    $filter->product_filter_value = remove_special_characters($filter->product_filter_value);
                    $this->db->join('custom_fields_options', 'custom_fields_options.common_id = custom_fields_product.selected_option_common_id');
                    $this->db->select('product_id');
                    $this->db->where('custom_fields_product.product_filter_key', $filter->product_filter_key);
                    $this->db->where('custom_fields_options.field_option', $filter->product_filter_value);
                    $this->db->from('custom_fields_product');
                    $array_queries[] = $this->db->get_compiled_select();
                    $this->db->reset_query();
                }
            }
            $this->build_query();
            foreach ($array_queries as $query) {
                $this->db->where("products.id IN ($query)", null, false);
            }
        } else {
            $this->build_query();
        }

        //add protuct filter options
        if (!empty($category_id)) {
            $this->db->where('products.category_id', $category_id);
            $this->db->order_by('products.is_promoted', 'DESC');
        }
        if (!empty($subcategory_id)) {
            $this->db->where('products.subcategory_id', $subcategory_id);
            $this->db->order_by('products.is_promoted', 'DESC');
        }
        if (!empty($third_category_id)) {
            $this->db->where('products.third_category_id', $third_category_id);
            $this->db->order_by('products.is_promoted', 'DESC');
        }
        if (!empty($country)) {
            $this->db->where('products.country_id', $country);
        }
        if (!empty($state)) {
            $this->db->where('products.state_id', $state);
        }
        if (!empty($city)) {
            $this->db->where('products.city_id', $city);
        }
        if (!empty($condition)) {
            $this->db->where('products.product_condition', $condition);
        }
        if ('' != $p_min) {
            $this->db->where('products.price >=', (int) ($p_min * 100));
        }
        if ('' != $p_max) {
            $this->db->where('products.price <=', (int) ($p_max * 100));
        }
        if ('' != $search) {
            $this->db->group_start();
            $this->db->like('products.title', $search);
            $this->db->or_like('products.description', $search);
            $this->db->group_end();
            $this->db->order_by('products.is_promoted', 'DESC');
        }
        //sort products
        if (!empty($sort) && 'lowest_price' == $sort) {
            $this->db->order_by('products.price');
        } elseif (!empty($sort) && 'highest_price' == $sort) {
            $this->db->order_by('products.price', 'DESC');
        } else {
            $this->db->order_by('products.created_at', 'DESC');
        }
    }

    //search products (AJAX search)
    public function search_products($search)
    {
        $search = remove_special_characters($search);
        $this->build_query();
        $this->db->like('products.title', $search);
        $this->db->order_by('products.is_promoted', 'DESC');
        $this->db->limit(8);
        $query = $this->db->get('products');

        return $query->result();
    }

    //get products
    public function get_products()
    {
        $this->build_query();
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->result();
    }

    //get limited products
    public function get_products_limited($limit)
    {
        $limit = clean_number($limit);
        $this->build_query();
        $this->db->order_by('products.created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('products');

        return $query->result();
    }

    //get promoted products
    public function get_promoted_products()
    {
        $key = 'promoted_products';
        if (0 != $this->default_location_id) {
            $key = 'promoted_products_location_' . $this->default_location_id;
        }
        $promoted_products = get_cached_data($key);
        if (empty($promoted_products)) {
            $this->build_query();
            $this->db->where('products.is_promoted', 1);
            $this->db->order_by('products.created_at', 'DESC');
            $query = $this->db->get('products');
            $promoted_products = $query->result();
            set_cache_data($key, $promoted_products);
        }

        return $promoted_products;
    }

    //get promoted products limited
    public function get_promoted_products_limited($limit)
    {
        $limit = clean_number($limit);
        $this->build_query();
        $this->db->where('products.is_promoted', 1);
        $this->db->limit($limit);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->result();
    }

    //get promoted products count
    public function get_promoted_products_count()
    {
        $products = $this->get_promoted_products();
        if (!empty($products)) {
            return count($products);
        }

        return 0;
    }

    //check promoted products
    public function check_promoted_products()
    {
        $products = $this->get_promoted_products();
        if (!empty($products)) {
            foreach ($products as $item) {
                if (date_difference($item->promote_end_date, date('Y-m-d H:i:s')) < 1) {
                    $data = [
                        'is_promoted' => 0,
                    ];
                    $this->db->where('id', $item->id);
                    $this->db->update('products', $data);
                }
            }
        }
    }

    //get paginated filtered products
    public function get_paginated_filtered_products($category_id, $subcategory_id, $third_category_id, $per_page, $offset)
    {
        $this->filter_products($category_id, $subcategory_id, $third_category_id);
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('products');

        return $query->result();
    }

    //get paginated filtered products count
    public function get_paginated_filtered_products_count($category_id, $subcategory_id, $third_category_id)
    {
        $this->filter_products($category_id, $subcategory_id, $third_category_id);
        $query = $this->db->get('products');

        return $query->num_rows();
    }

    //get products count by category
    public function get_products_count_by_category($category_id)
    {
        $category_id = clean_number($category_id);
        $this->build_query();
        $this->db->where('products.category_id', $category_id);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->num_rows();
    }

    //get products count by subcategory
    public function get_products_count_by_subcategory($category_id)
    {
        $category_id = clean_number($category_id);
        $this->build_query();
        $this->db->where('products.subcategory_id', $category_id);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->num_rows();
    }

    //get products count by third category
    public function get_products_count_by_third_category($category_id)
    {
        $category_id = clean_number($category_id);
        $this->build_query();
        $this->db->where('products.third_category_id', $category_id);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->num_rows();
    }

    //get related products
    public function get_related_products($product)
    {
        $this->build_query();
        if (0 != $product->third_category_id) {
            $this->db->where('products.third_category_id', $product->third_category_id);
        } elseif (0 != $product->subcategory_id) {
            $this->db->where('products.subcategory_id', $product->subcategory_id);
        } else {
            $this->db->where('products.category_id', $product->category_id);
        }
        $this->db->where('products.id !=', $product->id);
        $this->db->limit(4);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->result();
    }

    //get user products
    public function get_user_products($user_slug, $limit, $product_id)
    {
        $user_slug = clean_slug($user_slug);
        $limit = clean_number($limit);
        $product_id = clean_number($product_id);
        $this->build_query_unlocated();
        $this->db->where('users.slug', $user_slug);
        $this->db->where('products.id !=', $product_id);
        $this->db->limit($limit);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->result();
    }

    //get paginated user products
    public function get_paginated_user_products($user_slug, $per_page, $offset)
    {
        $user_slug = clean_slug($user_slug);
        $this->build_query_unlocated();
        $this->db->where('users.slug', $user_slug);
        $this->db->limit($per_page, $offset);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->result();
    }

    //get user products count
    public function get_user_products_count($user_slug)
    {
        $user_slug = clean_slug($user_slug);
        $user = $this->auth_model->get_user_by_slug($user_slug);
        if (empty($user)) {
            return 0;
        }
        $this->build_query_unlocated();
        $this->db->where('users.slug', $user_slug);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->num_rows();
    }

    //get paginated user pending products
    public function get_paginated_user_pending_products($user_id, $per_page, $offset)
    {
        $user_id = clean_number($user_id);
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug');
        $this->db->where('products.status', 0);
        $this->db->where('users.id', $user_id);
        $this->db->where('users.role !=', 'member');
        $this->db->where('products.is_draft', 0);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('products');

        return $query->result();
    }

    //get user pending products count
    public function get_user_pending_products_count($user_id)
    {
        $user_id = clean_number($user_id);
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug');
        $this->db->where('products.status', 0);
        $this->db->where('users.id', $user_id);
        $this->db->where('users.role !=', 'member');
        $this->db->where('products.is_draft', 0);
        $this->db->where('products.is_deleted', 0);
        $query = $this->db->get('products');

        return $query->num_rows();
    }

    //get user drafts count
    public function get_user_drafts_count($user_id)
    {
        $user_id = clean_number($user_id);
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug');
        $this->db->where('users.id', $user_id);
        $this->db->where('users.role !=', 'member');
        $this->db->where('products.is_draft', 1);
        $this->db->where('products.is_deleted', 0);
        $query = $this->db->get('products');

        return $query->num_rows();
    }

    //get paginated drafts
    public function get_paginated_user_drafts($user_id, $per_page, $offset)
    {
        $user_id = clean_number($user_id);
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug');
        $this->db->where('users.id', $user_id);
        $this->db->where('users.role !=', 'member');
        $this->db->where('products.is_draft', 1);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('products');

        return $query->result();
    }

    //get user hidden products count
    public function get_user_hidden_products_count($user_id)
    {
        $user_id = clean_number($user_id);
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug');
        $this->db->where('products.visibility', 0);
        $this->db->where('users.id', $user_id);
        $this->db->where('users.role !=', 'member');
        $this->db->where('products.is_draft', 0);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->num_rows();
    }

    //get paginated user hidden products
    public function get_paginated_user_hidden_products($user_id, $per_page, $offset)
    {
        $user_id = clean_number($user_id);
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug');
        $this->db->where('products.visibility', 0);
        $this->db->where('users.id', $user_id);
        $this->db->where('users.role !=', 'member');
        $this->db->where('products.is_draft', 0);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('products');

        return $query->result();
    }

    //get user favorited products
    public function get_user_favorited_products($user_id)
    {
        $user_id = clean_number($user_id);
        $this->build_query_unlocated();
        $this->db->join('favorites', 'products.id = favorites.product_id');
        $this->db->select('products.*');
        $this->db->where('favorites.user_id', $user_id);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->result();
    }

    //get user favorited products count
    public function get_user_favorited_products_count($user_id)
    {
        $user_id = clean_number($user_id);
        $this->build_query_unlocated();
        $this->db->join('favorites', 'products.id = favorites.product_id');
        $this->db->select('products.*');
        $this->db->where('favorites.user_id', $user_id);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->num_rows();
    }

    //get user downloads count
    public function get_user_downloads_count($user_id)
    {
        $user_id = clean_number($user_id);
        $this->db->where('buyer_id', $user_id);
        $query = $this->db->get('digital_sales');

        return $query->num_rows();
    }

    //get paginated downloads
    public function get_paginated_user_downloads($user_id, $per_page, $offset)
    {
        $user_id = clean_number($user_id);
        $this->db->where('buyer_id', $user_id);
        $this->db->order_by('purchase_date', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('digital_sales');

        return $query->result();
    }

    //get digital sale
    public function get_digital_sale($sale_id)
    {
        $sale_id = clean_number($sale_id);
        $this->db->where('id', $sale_id);
        $query = $this->db->get('digital_sales');

        return $query->row();
    }

    //get digital sale by buyer id
    public function get_digital_sale_by_buyer_id($buyer_id, $product_id)
    {
        $buyer_id = clean_number($buyer_id);
        $product_id = clean_number($product_id);
        $this->db->where('buyer_id', $buyer_id);
        $this->db->where('product_id', $product_id);
        $query = $this->db->get('digital_sales');

        return $query->row();
    }

    //get digital sale by order id
    public function get_digital_sale_by_order_id($buyer_id, $product_id, $order_id)
    {
        $buyer_id = clean_number($buyer_id);
        $product_id = clean_number($product_id);
        $order_id = clean_number($order_id);
        $this->db->where('buyer_id', $buyer_id);
        $this->db->where('product_id', $product_id);
        $this->db->where('order_id', $order_id);
        $query = $this->db->get('digital_sales');

        return $query->row();
    }

    //get product by id
    public function get_product_by_id($id)
    {
        $id = clean_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('products');

        return $query->row();
    }

    //get available product
    public function get_available_product($id)
    {
        $id = clean_number($id);
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug');
        $this->db->where('users.banned', 0);
        $this->db->where('users.role !=', 'member');
        $this->db->where('products.status', 1);
        $this->db->where('products.visibility', 1);
        $this->db->where('products.is_draft', 0);
        $this->db->where('products.is_deleted', 0);
        $this->db->where('products.id', $id);
        $query = $this->db->get('products');

        return $query->row();
    }

    //get product by slug
    public function get_product_by_slug($slug)
    {
        $slug = clean_slug($slug);
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug');
        $this->db->where('users.banned', 0);
        $this->db->where('users.role !=', 'member');
        $this->db->where('products.slug', $slug);
        $this->db->where('products.is_draft', 0);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->row();
    }

    //is product favorited
    public function is_product_in_favorites($product_id)
    {
        $product_id = clean_number($product_id);
        if ($this->auth_check) {
            $this->db->where('user_id', $this->auth_user->id);
            $this->db->where('product_id', $product_id);
            $query = $this->db->get('favorites');
            if (!empty($query->row())) {
                return true;
            }
        } else {
            $favorites = $this->session->userdata('mds_guest_favorites');
            if (!empty($favorites)) {
                foreach ($favorites as $favorite) {
                    if ($favorite == $product_id) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    //get product favorited count
    public function get_product_favorited_count($product_id)
    {
        $product_id = clean_number($product_id);
        $this->db->where('product_id', $product_id);
        $query = $this->db->get('favorites');

        return $query->num_rows();
    }

    //add remove favorites
    public function add_remove_favorites($product_id)
    {
        $product_id = clean_number($product_id);
        if ($this->auth_check) {
            if ($this->is_product_in_favorites($product_id)) {
                $this->db->where('user_id', $this->auth_user->id);
                $this->db->where('product_id', $product_id);
                $this->db->delete('favorites');
            } else {
                $data = [
                    'user_id' => $this->auth_user->id,
                    'product_id' => $product_id,
                ];
                $this->db->insert('favorites', $data);
            }
        } else {
            if ($this->is_product_in_favorites($product_id)) {
                $favorites = [];
                if (!empty($this->session->userdata('mds_guest_favorites'))) {
                    $favorites = $this->session->userdata('mds_guest_favorites');
                }
                $new = [];
                if (!empty($favorites)) {
                    foreach ($favorites as $favorite) {
                        if ($favorite != $product_id) {
                            array_push($new, $favorite);
                        }
                    }
                }
                $this->session->set_userdata('mds_guest_favorites', $new);
            } else {
                $favorites = [];
                if (!empty($this->session->userdata('mds_guest_favorites'))) {
                    $favorites = $this->session->userdata('mds_guest_favorites');
                }
                array_push($favorites, $product_id);
                $this->session->set_userdata('mds_guest_favorites', $favorites);
            }
        }
    }

    //increase product hit
    public function increase_product_hit($product)
    {
        if (!empty($product)):
            if (!isset($_COOKIE['modesy_product_' . $product->id])) :
                //increase hit
                setcookie('modesy_product_' . $product->id, '1', time() + (86400 * 300), '/');
        $data = [
                    'hit' => $product->hit + 1,
                ];

        $this->db->where('id', $product->id);
        $this->db->update('products', $data);

        endif;
        endif;
    }

    //get rss products by category
    public function get_rss_products_by_category($category_id)
    {
        $category_id = clean_number($category_id);
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug');
        $this->db->where('users.banned', 0);
        $this->db->where('users.role !=', 'member');
        $this->db->where('products.status', 1);
        $this->db->where('products.visibility', 1);
        $this->db->where('products.category_id', $category_id);
        $this->db->where('products.is_draft', 0);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->result();
    }

    //get rss products by user
    public function get_rss_products_by_user($user_id)
    {
        $user_id = clean_number($user_id);
        $this->db->join('users', 'products.user_id = users.id');
        $this->db->select('products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug');
        $this->db->where('users.banned', 0);
        $this->db->where('users.role !=', 'member');
        $this->db->where('products.status', 1);
        $this->db->where('products.visibility', 1);
        $this->db->where('users.id', $user_id);
        $this->db->where('products.is_draft', 0);
        $this->db->where('products.is_deleted', 0);
        $this->db->order_by('products.created_at', 'DESC');
        $query = $this->db->get('products');

        return $query->result();
    }

    //set product as sold
    public function set_product_as_sold($product_id)
    {
        $product_id = clean_number($product_id);
        $product = $this->get_product_by_id($product_id);
        if (!empty($product)) {
            if (user()->id == $product->user_id) {
                if (1 == $product->is_sold) {
                    $data = [
                        'is_sold' => 0,
                    ];
                } else {
                    $data = [
                        'is_sold' => 1,
                    ];
                }
                $this->db->where('id', $product_id);

                return $this->db->update('products', $data);
            }
        }

        return false;
    }

    //delete product
    public function delete_product($product_id)
    {
        $product_id = clean_number($product_id);
        $product = $this->get_product_by_id($product_id);
        if (!empty($product)) {
            $data = [
                'is_deleted' => 1,
            ];
            $this->db->where('id', $product_id);

            return $this->db->update('products', $data);
        }

        return false;
    }
}

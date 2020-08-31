<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Location_model extends CI_Model
{
    //add country
    public function add_country()
    {
        $data = [
            'name' => $this->input->post('name', true),
            'status' => $this->input->post('status', true),
        ];

        return $this->db->insert('location_countries', $data);
    }

    //update country
    public function update_country($id)
    {
        $data = [
            'name' => $this->input->post('name', true),
            'status' => $this->input->post('status', true),
        ];

        $this->db->where('id', $id);

        return $this->db->update('location_countries', $data);
    }

    //add state
    public function add_state()
    {
        $data = [
            'name' => $this->input->post('name', true),
            'country_id' => $this->input->post('country_id', true),
        ];

        return $this->db->insert('location_states', $data);
    }

    //update state
    public function update_state($id)
    {
        $data = [
            'name' => $this->input->post('name', true),
            'country_id' => $this->input->post('country_id', true),
        ];

        $this->db->where('id', $id);

        return $this->db->update('location_states', $data);
    }

    //get active countries
    public function get_active_countries()
    {
        $this->db->where('status', 1);
        $this->db->order_by('name');
        $query = $this->db->get('location_countries');

        return $query->result();
    }

    //get countries
    public function get_countries()
    {
        $this->db->order_by('name');
        $query = $this->db->get('location_countries');

        return $query->result();
    }

    //get paginated countries
    public function get_paginated_countries($per_page, $offset)
    {
        $q = trim($this->input->get('q', true));
        if (!empty($q)) {
            $this->db->like('name', $q);
        }
        $this->db->order_by('id');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('location_countries');

        return $query->result();
    }

    //get paginated countries count
    public function get_paginated_countries_count()
    {
        $q = trim($this->input->get('q', true));
        if (!empty($q)) {
            $this->db->like('name', $q);
        }
        $query = $this->db->get('location_countries');

        return $query->num_rows();
    }

    //get country
    public function get_country($id)
    {
        $id = clean_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('location_countries');

        return $query->row();
    }

    //activate inactivate countries
    public function activate_inactivate_countries($action)
    {
        $status = 1;
        if ('inactivate' == $action) {
            $status = 0;
        }
        $data = [
            'status' => $status,
        ];
        $this->db->update('location_countries', $data);
    }

    //delete country
    public function delete_country($id)
    {
        $id = clean_number($id);
        $country = $this->get_country($id);
        if (!empty($country)) {
            $this->db->where('id', $id);

            return $this->db->delete('location_countries');
        }

        return false;
    }

    //get states
    public function get_states()
    {
        $this->db->order_by('name');
        $query = $this->db->get('location_states');

        return $query->result();
    }

    //get paginated states
    public function get_paginated_states($per_page, $offset)
    {
        $country = $this->input->get('country', true);
        $q = trim($this->input->get('q', true));
        $this->db->join('location_countries', 'location_states.country_id = location_countries.id');
        $this->db->select('location_states.*, location_countries.name as country_name, location_countries.status as country_status');
        if (!empty($country)) {
            $this->db->where('location_states.country_id', $country);
        }
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('location_countries.name', $q);
            $this->db->or_like('location_states.name', $q);
            $this->db->group_end();
        }
        $this->db->order_by('location_states.id');
        $this->db->order_by('location_states.name');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('location_states');

        return $query->result();
    }

    //get paginated states count
    public function get_paginated_states_count()
    {
        $country = $this->input->get('country', true);
        $q = trim($this->input->get('q', true));
        $this->db->join('location_countries', 'location_states.country_id = location_countries.id');
        $this->db->select('location_states.*, location_countries.name as country_name, location_countries.status as country_status');
        if (!empty($country)) {
            $this->db->where('location_states.country_id', $country);
        }
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('location_countries.name', $q);
            $this->db->or_like('location_states.name', $q);
            $this->db->group_end();
        }
        $query = $this->db->get('location_states');

        return $query->num_rows();
    }

    //get state
    public function get_state($id)
    {
        $id = clean_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('location_states');

        return $query->row();
    }

    //get states by country
    public function get_states_by_country($country_id)
    {
        $this->db->where('country_id', clean_number($country_id));
        $this->db->order_by('name');
        $query = $this->db->get('location_states');

        return $query->result();
    }

    //delete state
    public function delete_state($id)
    {
        $id = clean_number($id);
        $state = $this->get_state($id);
        if (!empty($state)) {
            $this->db->where('id', $id);

            return $this->db->delete('location_states');
        }

        return false;
    }

    //add city
    public function add_city()
    {
        $data = [
            'name' => $this->input->post('name', true),
            'country_id' => $this->input->post('country_id', true),
            'state_id' => $this->input->post('state_id', true),
        ];

        return $this->db->insert('location_cities', $data);
    }

    //update city
    public function update_city($id)
    {
        $data = [
            'name' => $this->input->post('name', true),
            'country_id' => $this->input->post('country_id', true),
            'state_id' => $this->input->post('state_id', true),
        ];

        $this->db->where('id', $id);

        return $this->db->update('location_cities', $data);
    }

    //get cities
    public function get_cities()
    {
        $this->db->order_by('name');
        $query = $this->db->get('location_cities');

        return $query->result();
    }

    //get paginated cities
    public function get_paginated_cities($per_page, $offset)
    {
        $country = $this->input->get('country', true);
        $state = $this->input->get('state', true);
        $q = trim($this->input->get('q', true));
        $this->db->join('location_countries', 'location_cities.country_id = location_countries.id');
        $this->db->join('location_states', 'location_cities.state_id = location_states.id');
        $this->db->select('location_cities.*, location_countries.name as country_name, location_states.name as state_name');
        if (!empty($country)) {
            $this->db->where('location_cities.country_id', $country);
        }
        if (!empty($state)) {
            $this->db->where('location_cities.state_id', $state);
        }
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('location_countries.name', $q);
            $this->db->or_like('location_cities.name', $q);
            $this->db->group_end();
        }
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('location_cities');

        return $query->result();
    }

    //get paginated cities count
    public function get_paginated_cities_count()
    {
        $country = $this->input->get('country', true);
        $state = $this->input->get('state', true);
        $q = trim($this->input->get('q', true));
        $this->db->join('location_countries', 'location_cities.country_id = location_countries.id');
        $this->db->join('location_states', 'location_cities.state_id = location_states.id');
        $this->db->select('location_cities.*');
        if (!empty($country)) {
            $this->db->where('location_cities.country_id', $country);
        }
        if (!empty($state)) {
            $this->db->where('location_cities.state_id', $state);
        }
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('location_countries.name', $q);
            $this->db->or_like('location_cities.name', $q);
            $this->db->group_end();
        }
        $query = $this->db->get('location_cities');

        return $query->num_rows();
    }

    //get city
    public function get_city($id)
    {
        $id = clean_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('location_cities');

        return $query->row();
    }

    //get cities by country
    public function get_cities_by_country($country_id)
    {
        $country_id = clean_number($country_id);
        $this->db->where('location_cities.country_id', $country_id);
        $this->db->order_by('location_cities.name');
        $query = $this->db->get('location_cities');

        return $query->result();
    }

    //get cities by state
    public function get_cities_by_state($state_id)
    {
        $this->db->where('location_cities.state_id', clean_number($state_id));
        $this->db->order_by('location_cities.name');
        $query = $this->db->get('location_cities');

        return $query->result();
    }

    //delete city
    public function delete_city($id)
    {
        $id = clean_number($id);
        $city = $this->get_city($id);
        if (!empty($city)) {
            $this->db->where('id', $id);

            return $this->db->delete('location_cities');
        }

        return false;
    }

    //search countries
    public function search_countries($val)
    {
        $val = remove_special_characters($val);
        $this->db->like('name', $val);
        $this->db->where('status', 1);
        $query = $this->db->get('location_countries');

        return $query->result();
    }

    //search states
    public function search_states($val)
    {
        $val = remove_special_characters($val);
        $this->db->join('location_countries', 'location_states.country_id = location_countries.id AND location_countries.status = 1');
        $this->db->select('location_states.*, location_countries.name as country_name, location_countries.id as country_id');
        $this->db->like('location_countries.name', $val);
        $this->db->or_like('location_states.name', $val);
        $this->db->or_like('CONCAT(location_states.name, " ", location_countries.name)', $val);
        $this->db->limit(100);
        $query = $this->db->get('location_states');

        return $query->result();
    }

    //search cities
    public function search_cities($val)
    {
        $val = remove_special_characters($val);
        $this->db->join('location_countries', 'location_cities.country_id = location_countries.id AND location_countries.status = 1');
        $this->db->join('location_states', 'location_cities.state_id = location_states.id');
        $this->db->select('location_cities.*, location_countries.id as country_id, location_countries.name as country_name, location_states.id as state_id, location_states.name as state_name');
        $this->db->like('location_countries.name', $val);
        $this->db->or_like('location_states.name', $val);
        $this->db->or_like('location_cities.name', $val);
        $this->db->or_like('CONCAT(location_cities.name, " ",location_states.name, " ", location_countries.name)', $val);
        $this->db->limit(200);
        $query = $this->db->get('location_cities');

        return $query->result();
    }

    //get location input
    public function get_location_input($country_id, $state_id, $city_id)
    {
        $country_id = clean_number($country_id);
        $state_id = clean_number($state_id);
        $city_id = clean_number($city_id);
        $str = '';
        if (!empty($country_id)) {
            $country = $this->get_country($country_id);
            if (!empty($country)) {
                $str = $country->name;
            }
        }
        if (!empty($state_id)) {
            $state = $this->get_state($state_id);
            if (!empty($state)) {
                $str = $state->name . ', ' . $str;
            }
        }
        if (!empty($city_id)) {
            $city = $this->get_city($city_id);
            if (!empty($city)) {
                $str = $city->name . ', ' . $str;
            }
        }

        return $str;
    }

    //get default location
    public function get_default_location()
    {
        if (!empty($this->session->userdata('mds_default_location_id'))) {
            return $this->session->userdata('mds_default_location_id');
        }

        return 0;
    }
}

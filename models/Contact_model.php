<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contact_model extends CI_Model
{
    //input values
    public function input_values()
    {
        return [
            'name' => $this->input->post('name', true),
            'email' => $this->input->post('email', true),
            'message' => $this->input->post('message', true),
        ];
    }

    //add contact message
    public function add_contact_message()
    {
        $data = $this->input_values();
        //send email
        if (1 == $this->general_settings->send_email_contact_messages) {
            $email_data = [
                'email_type' => 'contact',
                'message_name' => $data['name'],
                'message_email' => $data['email'],
                'message_text' => $data['message'],
            ];
            $this->session->set_userdata('mds_send_email_data', json_encode($email_data));
        }

        $data['created_at'] = date('Y-m-d H:i:s');

        return $this->db->insert('contacts', $data);
    }

    //get contact messages
    public function get_contact_messages()
    {
        $query = $this->db->get('contacts');

        return $query->result();
    }

    //get contact message
    public function get_contact_message($id)
    {
        $id = clean_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('contacts');

        return $query->result();
    }

    //get last contact messages
    public function get_last_contact_messages()
    {
        $this->db->limit(5);
        $query = $this->db->get('contacts');

        return $query->result();
    }

    //delete contact message
    public function delete_contact_message($id)
    {
        $id = clean_number($id);
        $contact = $this->get_contact_message($id);

        if (!empty($contact)) {
            $this->db->where('id', $id);

            return $this->db->delete('contacts');
        }

        return false;
    }
}

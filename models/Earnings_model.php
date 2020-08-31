<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Earnings_model extends CI_Model
{
    //get earnings count
    public function get_earnings_count($user_id)
    {
        $user_id = clean_number($user_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('earnings');

        return $query->num_rows();
    }

    //get paginated earnings
    public function get_paginated_earnings($user_id, $per_page, $offset)
    {
        $user_id = clean_number($user_id);
        $this->db->where('user_id', $user_id);
        $this->db->order_by('earnings.created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('earnings');

        return $query->result();
    }

    //add seller earnings
    public function add_seller_earnings($order_product)
    {
        if (!empty($order_product)) {
            $order = $this->order_model->get_order($order_product->order_id);
            if (!empty($order)) {
                $earned_amount = $this->calculate_earned_amount($order_product);

                //add earning
                $data = [
                    'order_number' => $order->order_number,
                    'user_id' => $order_product->seller_id,
                    'price' => $order_product->product_unit_price * $order_product->product_quantity,
                    'commission_rate' => $order_product->commission_rate,
                    'shipping_cost' => $order_product->product_shipping_cost,
                    'earned_amount' => $earned_amount,
                    'currency' => $order_product->product_currency,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $this->db->insert('earnings', $data);

                //update seller balance and number of sales
                $user = get_user($order_product->seller_id);
                if (!empty($user)) {
                    $balance = $user->balance;
                    $new_balance = $balance + $earned_amount;
                    $sales = $user->number_of_sales;
                    $sales = $sales + 1;
                    $data = [
                        'balance' => $new_balance,
                        'number_of_sales' => $sales,
                    ];
                    $this->db->where('id', $user->id);
                    $this->db->update('users', $data);
                }
            }
        }
    }

    //calculate earned amount
    public function calculate_earned_amount($order_product)
    {
        if (!empty($order_product)) {
            $price = $order_product->product_unit_price * $order_product->product_quantity;
            $earned = $price - (($price * $order_product->commission_rate) / 100);

            return $earned + $order_product->product_shipping_cost;
        }

        return 0;
    }

    //get user payout account
    public function get_user_payout_account($user_id)
    {
        $user_id = clean_number($user_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_payout_accounts');
        $row = $query->row();

        if (!empty($row)) {
            return $row;
        }
        $data = [
                'user_id' => $user_id,
                'payout_paypal_email' => '',
                'iban_full_name' => '',
                'iban_country_id' => '',
                'iban_bank_name' => '',
                'iban_number' => '',
                'swift_full_name' => '',
                'swift_address' => '',
                'swift_state' => '',
                'swift_city' => '',
                'swift_postcode' => '',
                'swift_country_id' => '',
                'swift_bank_account_holder_name' => '',
                'swift_iban' => '',
                'swift_code' => '',
                'swift_bank_name' => '',
                'swift_bank_branch_city' => '',
                'swift_bank_branch_country_id' => '',
            ];
        $this->db->insert('user_payout_accounts', $data);

        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_payout_accounts');

        return $query->row();
    }

    //set paypal payout account
    public function set_paypal_payout_account($user_id)
    {
        $user_id = clean_number($user_id);
        $data = [
            'payout_paypal_email' => $this->input->post('payout_paypal_email', true),
        ];
        $this->db->where('user_id', $user_id);

        return $this->db->update('user_payout_accounts', $data);
    }

    //set iban payout account
    public function set_iban_payout_account($user_id)
    {
        $user_id = clean_number($user_id);
        $data = [
            'iban_full_name' => $this->input->post('iban_full_name', true),
            'iban_country_id' => $this->input->post('iban_country_id', true),
            'iban_bank_name' => $this->input->post('iban_bank_name', true),
            'iban_number' => $this->input->post('iban_number', true),
        ];
        $this->db->where('user_id', $user_id);

        return $this->db->update('user_payout_accounts', $data);
    }

    //set swift payout account
    public function set_swift_payout_account($user_id)
    {
        $user_id = clean_number($user_id);
        $data = [
            'swift_full_name' => $this->input->post('swift_full_name', true),
            'swift_address' => $this->input->post('swift_address', true),
            'swift_state' => $this->input->post('swift_state', true),
            'swift_city' => $this->input->post('swift_city', true),
            'swift_postcode' => $this->input->post('swift_postcode', true),
            'swift_country_id' => $this->input->post('swift_country_id', true),
            'swift_bank_account_holder_name' => $this->input->post('swift_bank_account_holder_name', true),
            'swift_iban' => $this->input->post('swift_iban', true),
            'swift_code' => $this->input->post('swift_code', true),
            'swift_bank_name' => $this->input->post('swift_bank_name', true),
            'swift_bank_branch_city' => $this->input->post('swift_bank_branch_city', true),
            'swift_bank_branch_country_id' => $this->input->post('swift_bank_branch_country_id', true),
        ];
        $this->db->where('user_id', $user_id);

        return $this->db->update('user_payout_accounts', $data);
    }

    //get payouts count
    public function get_payouts_count($user_id)
    {
        $user_id = clean_number($user_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('payouts');

        return $query->num_rows();
    }

    //get paginated payouts
    public function get_paginated_payouts($user_id, $per_page, $offset)
    {
        $user_id = clean_number($user_id);
        $this->db->where('user_id', $user_id);
        $this->db->order_by('payouts.created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('payouts');

        return $query->result();
    }

    //get active payouts
    public function get_active_payouts($user_id)
    {
        $user_id = clean_number($user_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 0);
        $this->db->order_by('payouts.created_at', 'DESC');
        $query = $this->db->get('payouts');

        return $query->result();
    }

    //withdraw money
    public function withdraw_money($data)
    {
        return $this->db->insert('payouts', $data);
    }
}

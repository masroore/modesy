<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Comment_model extends CI_Model
{
    /*
    *-------------------------------------------------------------------------------------------------
    * PRODUCT COMMENTS
    *-------------------------------------------------------------------------------------------------
    */

    //add comment
    public function add_comment()
    {
        $data = [
            'parent_id' => $this->input->post('parent_id', true),
            'product_id' => $this->input->post('product_id', true),
            'user_id' => 0,
            'name' => trim($this->input->post('name', true)),
            'email' => trim($this->input->post('email', true)),
            'comment' => trim($this->input->post('comment', true)),
            'status' => 0,
            'ip_address' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if (1 != $this->general_settings->comment_approval_system) {
            $data['status'] = 1;
        }
        if (empty($data['parent_id'])) {
            $data['parent_id'] = 0;
        }
        if ($this->auth_check) {
            $data['user_id'] = $this->auth_user->id;
            $data['name'] = $this->auth_user->username;
            $data['email'] = $this->auth_user->email;
        } else {
            if (empty($data['name']) || empty($data['email'])) {
                return false;
            }
        }
        if (empty($data['name'])) {
            $data['name'] = '';
        }
        if (empty($data['email'])) {
            $data['email'] = '';
        }
        $ip = $this->input->ip_address();
        if (!empty($ip)) {
            $data['ip_address'] = $ip;
        }
        $data['parent_id'] = clean_number($data['parent_id']);
        $data['product_id'] = clean_number($data['product_id']);
        if (!empty($data['product_id']) && !empty($data['comment'])) {
            $this->db->insert('comments', $data);
        }
    }

    //pending product comments
    public function get_pending_comments()
    {
        $query = $this->db->query('SELECT * FROM comments WHERE status = 0 ORDER BY created_at DESC');

        return $query->result();
    }

    //approved product comments
    public function get_approved_comments()
    {
        $query = $this->db->query('SELECT * FROM comments WHERE status = 1 ORDER BY created_at DESC');

        return $query->result();
    }

    //latest comments
    public function get_latest_comments($limit)
    {
        $sql = 'SELECT * FROM comments ORDER BY created_at DESC LIMIT ?';
        $query = $this->db->query($sql, [clean_number($limit)]);

        return $query->result();
    }

    //comments
    public function get_comments($product_id, $limit)
    {
        $sql = 'SELECT comments.*, users.shop_name AS user_shop_name, users.slug AS user_slug, users.avatar AS user_avatar, users.user_type AS user_type
                FROM comments LEFT JOIN users ON comments.user_id = users.id 
                WHERE product_id = ? AND parent_id = 0 AND status = 1 ORDER BY created_at DESC LIMIT ?';
        $query = $this->db->query($sql, [clean_number($product_id), clean_number($limit)]);

        return $query->result();
    }

    //subomments
    public function get_subcomments($parent_id)
    {
        $sql = 'SELECT comments.*, users.shop_name AS user_shop_name, users.slug AS user_slug, users.avatar AS user_avatar, users.user_type AS user_type
                FROM comments LEFT JOIN users ON comments.user_id = users.id 
                WHERE parent_id = ? AND status = 1 ORDER BY created_at DESC';
        $query = $this->db->query($sql, [clean_number($parent_id)]);

        return $query->result();
    }

    //comment
    public function get_comment($comment_id)
    {
        $sql = 'SELECT * FROM comments WHERE id = ?';
        $query = $this->db->query($sql, [clean_number($comment_id)]);

        return $query->row();
    }

    //product comment count
    public function get_product_comment_count($product_id)
    {
        $sql = 'SELECT COUNT(comments.id) AS count FROM comments WHERE product_id = ? AND parent_id = 0  AND status = 1';
        $query = $this->db->query($sql, [clean_number($product_id)]);

        return $query->row()->count;
    }

    //approve comment
    public function approve_comment($id)
    {
        $comment = $this->get_comment($id);
        if (!empty($comment)) {
            $data = [
                'status' => 1,
            ];
            $this->db->where('id', $comment->id);

            return $this->db->update('comments', $data);
        }

        return false;
    }

    //approve multi comments
    public function approve_multi_comments($comment_ids)
    {
        if (!empty($comment_ids)) {
            foreach ($comment_ids as $id) {
                $this->approve_comment($id);
            }
        }
    }

    //delete comment
    public function delete_comment($id)
    {
        $comment = $this->get_comment($id);
        if (!empty($comment)) {
            //delete subcomments
            $this->delete_subcomments($id);

            $this->db->where('id', $comment->id);

            return $this->db->delete('comments');
        }

        return false;
    }

    //delete multi comments
    public function delete_multi_comments($comment_ids)
    {
        if (!empty($comment_ids)) {
            foreach ($comment_ids as $id) {
                //delete subcomments
                $this->delete_subcomments($id);

                $this->db->where('id', clean_number($id));
                $this->db->delete('comments');
            }
        }
    }

    //delete sub comments
    public function delete_subcomments($id)
    {
        $subcomments = $this->get_subcomments($id);
        if (!empty($subcomments)) {
            foreach ($subcomments as $comment) {
                $this->db->where('id', $comment->id);
                $this->db->delete('comments');
            }
        }
    }

    /*
    *-------------------------------------------------------------------------------------------------
    * BLOG COMMENTS
    *-------------------------------------------------------------------------------------------------
    */

    //add comment
    public function add_blog_comment()
    {
        $data = [
            'post_id' => $this->input->post('post_id', true),
            'user_id' => 0,
            'name' => trim($this->input->post('name', true)),
            'email' => trim($this->input->post('email', true)),
            'comment' => trim($this->input->post('comment', true)),
            'status' => 0,
            'ip_address' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if (1 != $this->general_settings->comment_approval_system) {
            $data['status'] = 1;
        }
        if ($this->auth_check) {
            $data['user_id'] = $this->auth_user->id;
            $data['name'] = $this->auth_user->username;
            $data['email'] = $this->auth_user->email;
        } else {
            if (empty($data['name']) || empty($data['email'])) {
                return false;
            }
        }
        if (empty($data['name'])) {
            $data['name'] = '';
        }
        if (empty($data['email'])) {
            $data['email'] = '';
        }
        $ip = $this->input->ip_address();
        if (!empty($ip)) {
            $data['ip_address'] = $ip;
        }
        $data['post_id'] = clean_number($data['post_id']);
        if (!empty($data['post_id']) && !empty($data['comment'])) {
            $this->db->insert('blog_comments', $data);
        }
    }

    //pending comments
    public function get_pending_blog_comments()
    {
        $query = $this->db->query('SELECT * FROM blog_comments WHERE status = 0 ORDER BY created_at DESC');

        return $query->result();
    }

    //approved comments
    public function get_approved_blog_comments()
    {
        $query = $this->db->query('SELECT * FROM blog_comments WHERE status = 1 ORDER BY created_at DESC');

        return $query->result();
    }

    //comments
    public function get_blog_comments($post_id, $limit)
    {
        $sql = 'SELECT * FROM blog_comments WHERE post_id = ? AND status = 1 ORDER BY created_at DESC LIMIT ?';
        $query = $this->db->query($sql, [clean_number($post_id), clean_number($limit)]);

        return $query->result();
    }

    //comment
    public function get_blog_comment($comment_id)
    {
        $sql = 'SELECT * FROM blog_comments WHERE id = ?';
        $query = $this->db->query($sql, [clean_number($comment_id)]);

        return $query->row();
    }

    //post comment count
    public function get_blog_comment_count($post_id)
    {
        $sql = 'SELECT COUNT(blog_comments.id) AS count FROM blog_comments WHERE post_id = ? AND status = 1 ';
        $query = $this->db->query($sql, [clean_number($post_id)]);

        return $query->row()->count;
    }

    //approve comment
    public function approve_blog_comment($id)
    {
        $comment = $this->get_blog_comment($id);
        if (!empty($comment)) {
            $data = [
                'status' => 1,
            ];
            $this->db->where('id', $comment->id);

            return $this->db->update('blog_comments', $data);
        }

        return false;
    }

    //approve multi comments
    public function approve_multi_blog_comments($comment_ids)
    {
        if (!empty($comment_ids)) {
            foreach ($comment_ids as $id) {
                $this->approve_blog_comment($id);
            }
        }
    }

    //delete comment
    public function delete_blog_comment($id)
    {
        $comment = $this->get_blog_comment($id);
        if (!empty($comment)) {
            $this->db->where('id', $comment->id);

            return $this->db->delete('blog_comments');
        }

        return false;
    }

    //delete multi comments
    public function delete_multi_blog_comments($comment_ids)
    {
        if (!empty($comment_ids)) {
            foreach ($comment_ids as $id) {
                $this->db->where('id', clean_number($id));
                $this->db->delete('blog_comments');
            }
        }
    }
}

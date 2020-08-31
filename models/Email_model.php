<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'third_party/swiftmailer/vendor/autoload.php';
require APPPATH . 'third_party/phpmailer/vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Email_model extends CI_Model
{
    //send email activation
    public function send_email_activation($user_id)
    {
        $user_id = clean_number($user_id);
        $user = $this->auth_model->get_user($user_id);
        if (!empty($user)) {
            $token = $user->token;
            //check token
            if (empty($token)) {
                $token = generate_token();
                $data = [
                    'token' => $token,
                ];
                $this->db->where('id', $user->id);
                $this->db->update('users', $data);
            }

            $data = [
                'subject' => trans('confirm_your_email'),
                'to' => $user->email,
                'template_path' => 'email/email_activation',
                'token' => $token,
            ];

            $this->send_email($data);
        }
    }

    //send email reset password
    public function send_email_reset_password($user_id)
    {
        $user_id = clean_number($user_id);
        $user = $this->auth_model->get_user($user_id);
        if (!empty($user)) {
            $token = $user->token;
            //check token
            if (empty($token)) {
                $token = generate_token();
                $data = [
                    'token' => $token,
                ];
                $this->db->where('id', $user->id);
                $this->db->update('users', $data);
            }

            $data = [
                'subject' => trans('reset_password'),
                'to' => $user->email,
                'template_path' => 'email/email_reset_password',
                'token' => $token,
            ];

            $this->send_email($data);
        }
    }

    //send email new product
    public function send_email_new_product($product_url)
    {
        $data = [
            'subject' => trans('email_text_new_product'),
            'product_url' => $product_url,
            'to' => $this->general_settings->mail_options_account,
            'template_path' => 'email/email_new_product',
            'product_url' => $product_url,
        ];

        $this->send_email($data);
    }

    //send email contact message
    public function send_email_contact_message($message_name, $message_email, $message_text)
    {
        $data = [
            'subject' => trans('contact_message'),
            'to' => $this->general_settings->mail_options_account,
            'template_path' => 'email/email_contact_message',
            'message_name' => $message_name,
            'message_email' => $message_email,
            'message_text' => $message_text,
        ];

        $this->send_email($data);
    }

    //send email new order
    public function send_email_new_order($order_id)
    {
        $order_id = clean_number($order_id);
        $order = get_order($order_id);
        $order_products = $this->order_model->get_order_products($order_id);
        $order_shipping = get_order_shipping($order_id);
        if (!empty($order)) {
            //send to buyer
            $to = '';
            if (!empty($order_shipping)) {
                $to = $order_shipping->shipping_email;
            }
            if ('registered' == $order->buyer_type) {
                $user = get_user($order->buyer_id);
                if (!empty($user)) {
                    $to = $user->email;
                }
            }
            $data = [
                'subject' => trans('email_text_thank_for_order'),
                'order' => $order,
                'order_products' => $order_products,
                'to' => $to,
                'template_path' => 'email/email_new_order',
            ];
            $this->send_email($data);

            //send to seller
            if (!empty($order_products)) {
                $seller_ids = [];
                foreach ($order_products as $order_product) {
                    $seller = get_user($order_product->seller_id);
                    if (!empty($seller)) {
                        if (1 == $seller->send_email_when_item_sold && !in_array($seller->id, $seller_ids)) {
                            array_push($seller_ids, $seller->id);
                            $seller_order_products = $this->order_model->get_seller_order_products($order_id, $seller->id);
                            $data = [
                                'subject' => trans('you_have_new_order'),
                                'order' => $order,
                                'order_products' => $seller_order_products,
                                'to' => $seller->email,
                                'template_path' => 'email/email_new_order_seller',
                            ];
                            $this->send_email($data);
                        }
                    }
                }
            }
        }
    }

    //send email new message
    public function send_email_new_message($user, $message_subject, $message_text)
    {
        $data = [
            'subject' => trans('you_have_new_message'),
            'to' => $user->email,
            'template_path' => 'email/email_new_message',
            'message_sender' => $user->username,
            'message_subject' => $message_subject,
            'message_text' => $message_text,
        ];
        $this->send_email($data);
    }

    //send email order shipped
    public function send_email_order_shipped($order_product)
    {
        $order = get_order($order_product->order_id);
        if (!empty($order)) {
            $to = $order->shipping_email;
            if ('registered' == $order->buyer_type) {
                $user = get_user($order->buyer_id);
                $to = $user->email;
            }
            $data = [
                'subject' => trans('your_order_shipped'),
                'to' => $to,
                'template_path' => 'email/email_order_shipped',
                'order' => $order,
                'order_product' => $order_product,
            ];

            $this->send_email($data);
        }
    }

    //send email newsletter
    public function send_email_newsletter($subscriber, $subject, $message)
    {
        if (!empty($subscriber)) {
            if (empty($subscriber->token)) {
                $this->newsletter_model->update_subscriber_token($subscriber->email);
                $subscriber = $this->newsletter_model->get_subscriber($subscriber->email);
            }

            $data = [
                'subject' => $subject,
                'message' => $message,
                'to' => $subscriber->email,
                'template_path' => 'email/email_newsletter',
                'subscriber' => $subscriber,
            ];

            return $this->send_email($data);
        }
    }

    //send email
    public function send_email($data)
    {
        if ('swift' == $this->general_settings->mail_library) {
            try {
                // Create the Transport
                $transport = (new Swift_SmtpTransport($this->general_settings->mail_host, $this->general_settings->mail_port, 'tls'))
                    ->setUsername($this->general_settings->mail_username)
                    ->setPassword($this->general_settings->mail_password);

                // Create the Mailer using your created Transport
                $mailer = new Swift_Mailer($transport);

                // Create a message
                $message = (new Swift_Message($this->general_settings->application_name))
                    ->setFrom([$this->general_settings->mail_username => $this->general_settings->application_name])
                    ->setTo([$data['to'] => ''])
                    ->setSubject($data['subject'])
                    ->setBody($this->load->view($data['template_path'], $data, true), 'text/html');

                //Send the message
                $result = $mailer->send($message);
                if ($result) {
                    return true;
                }
            } catch (\Swift_TransportException $Ste) {
                $this->session->set_flashdata('error', $Ste->getMessage());

                return false;
            } catch (\Swift_RfcComplianceException $Ste) {
                $this->session->set_flashdata('error', $Ste->getMessage());

                return false;
            }
        } elseif ('php' == $this->general_settings->mail_library) {
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = $this->general_settings->mail_host;
                $mail->SMTPAuth = true;
                $mail->Username = $this->general_settings->mail_username;
                $mail->Password = $this->general_settings->mail_password;
                $mail->SMTPSecure = 'tls';
                $mail->CharSet = 'UTF-8';
                $mail->Port = $this->general_settings->mail_port;
                //Recipients
                $mail->setFrom($this->general_settings->mail_username, $this->general_settings->application_name);
                $mail->addAddress($data['to']);
                //Content
                $mail->isHTML(true);
                $mail->Subject = $data['subject'];
                $mail->Body = $this->load->view($data['template_path'], $data, true, 'text/html');
                $mail->send();

                return true;
            } catch (Exception $e) {
                $this->session->set_flashdata('error', $mail->ErrorInfo);

                return false;
            }
        } else {
            $this->load->library('email');

            $settings = $this->settings_model->get_general_settings();
            if ('mail' == $settings->mail_protocol) {
                $config = [
                    'protocol' => 'mail',
                    'smtp_host' => $settings->mail_host,
                    'smtp_port' => $settings->mail_port,
                    'smtp_user' => $settings->mail_username,
                    'smtp_pass' => $settings->mail_password,
                    'smtp_timeout' => 30,
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'wordwrap' => true,
                ];
            } else {
                $config = [
                    'protocol' => 'smtp',
                    'smtp_host' => $settings->mail_host,
                    'smtp_port' => $settings->mail_port,
                    'smtp_user' => $settings->mail_username,
                    'smtp_pass' => $settings->mail_password,
                    'smtp_timeout' => 30,
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'wordwrap' => true,
                ];
            }

            //initialize
            $this->email->initialize($config);

            //send email
            $message = $this->load->view($data['template_path'], $data, true);
            $this->email->from($settings->mail_username, $settings->application_name);
            $this->email->to($data['to']);
            $this->email->subject($data['subject']);
            $this->email->message($message);

            $this->email->set_newline("\r\n");

            if ($this->email->send()) {
                return true;
            }
            $this->session->set_flashdata('error', $this->email->print_debugger(['headers']));

            return false;
        }
    }
}

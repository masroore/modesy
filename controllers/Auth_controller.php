<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Login Post.
     */
    public function login_post()
    {
        //check auth
        if (auth_check()) {
            $data = [
                'result' => 1,
            ];
            echo json_encode($data);
            exit();
        }
        //validate inputs
        $this->form_validation->set_rules('email', trans('email_address'), 'required|xss_clean|max_length[100]');
        $this->form_validation->set_rules('password', trans('password'), 'required|xss_clean|max_length[30]');
        if (false == $this->form_validation->run()) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->auth_model->input_values());
            $this->load->view('partials/_messages');
        } else {
            if ($this->auth_model->login()) {
                $data = [
                    'result' => 1,
                ];
                echo json_encode($data);
            } else {
                $data = [
                    'result' => 0,
                    'error_message' => $this->load->view('partials/_messages', '', true),
                ];
                echo json_encode($data);
            }
            reset_flash_data();
        }
    }

    /**
     * Connect with Facebook.
     */
    public function connect_with_facebook()
    {
        $fb_url = 'https://www.facebook.com/v3.3/dialog/oauth?client_id=' . $this->general_settings->facebook_app_id . '&redirect_uri=' . base_url() . 'facebook-callback&scope=email&state=' . generate_unique_id();

        $this->session->set_userdata('fb_login_referrer', $this->agent->referrer());
        redirect($fb_url);
        exit();
    }

    /**
     * Facebook Callback.
     */
    public function facebook_callback()
    {
        require_once APPPATH . 'third_party/facebook/vendor/autoload.php';

        $fb = new \Facebook\Facebook([
            'app_id' => $this->general_settings->facebook_app_id,
            'app_secret' => $this->general_settings->facebook_app_secret,
            'default_graph_version' => 'v2.10',
        ]);
        try {
            $helper = $fb->getRedirectLoginHelper();
            $permissions = ['email'];
            if (isset($_GET['state'])) {
                $helper->getPersistentDataHandler()->set('state', $_GET['state']);
            }
            $accessToken = $helper->getAccessToken();
            $response = $fb->get('/me?fields=name,email', $accessToken);
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $user = $response->getGraphUser();
        $fb_user = new stdClass();
        $fb_user->id = $user->getId();
        $fb_user->email = $user->getEmail();
        $fb_user->name = $user->getName();

        $this->auth_model->login_with_facebook($fb_user);

        if (!empty($this->session->userdata('fb_login_referrer'))) {
            redirect($this->session->userdata('fb_login_referrer'));
        } else {
            redirect(base_url());
        }
    }

    /**
     * Connect with Google.
     */
    public function connect_with_google()
    {
        require_once APPPATH . 'third_party/google/vendor/autoload.php';

        $provider = new League\OAuth2\Client\Provider\Google([
            'clientId' => $this->general_settings->google_client_id,
            'clientSecret' => $this->general_settings->google_client_secret,
            'redirectUri' => base_url() . 'connect-with-google',
        ]);

        if (!empty($_GET['error'])) {
            // Got an error, probably user denied access
            exit('Got error: ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));
        }
        if (empty($_GET['code'])) {

            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            $this->session->set_userdata('g_login_referrer', $this->agent->referrer());
            header('Location: ' . $authUrl);
            exit();
        }
        if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            // State is invalid, possible CSRF attack in progress
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        }
        // Try to get an access token (using the authorization code grant)
        $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code'],
            ]);
        // Optional: Now you have a token you can look up a users profile data
        try {
            // We got an access token, let's now get the owner details
            $user = $provider->getResourceOwner($token);

            $g_user = new stdClass();
            $g_user->id = $user->getId();
            $g_user->email = $user->getEmail();
            $g_user->name = $user->getName();
            $g_user->avatar = $user->getAvatar();

            $this->auth_model->login_with_google($g_user);

            if (!empty($this->session->userdata('g_login_referrer'))) {
                redirect($this->session->userdata('g_login_referrer'));
            } else {
                redirect(base_url());
            }
        } catch (Exception $e) {
            // Failed to get user details
            exit('Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Admin Login.
     */
    public function admin_login()
    {
        if (auth_check()) {
            redirect(lang_base_url());
        }
        $data['title'] = trans('login');
        $data['description'] = trans('login') . ' - ' . $this->settings->site_title;
        $data['keywords'] = trans('login') . ', ' . $this->general_settings->application_name;
        $this->load->view('admin/login', $data);
    }

    /**
     * Admin Login Post.
     */
    public function admin_login_post()
    {
        //validate inputs
        $this->form_validation->set_rules('email', trans('form_email'), 'required|xss_clean|max_length[200]');
        $this->form_validation->set_rules('password', trans('form_password'), 'required|xss_clean|max_length[30]');

        if (false == $this->form_validation->run()) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->auth_model->input_values());
            redirect($this->agent->referrer());
        } else {
            if ($this->auth_model->login()) {
                redirect(admin_url());
            } else {
                //error
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', trans('login_error'));
                redirect($this->agent->referrer());
            }
        }
    }

    /**
     * Register.
     */
    public function register()
    {
        //check if logged in
        if (auth_check()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans('register');
        $data['description'] = trans('register') . ' - ' . $this->app_name;
        $data['keywords'] = trans('register') . ',' . $this->app_name;

        $this->load->view('partials/_header', $data);
        $this->load->view('auth/register');
        $this->load->view('partials/_footer');
    }

    /**
     * Register Post.
     */
    public function register_post()
    {
        //check if logged in
        if (auth_check()) {
            redirect(lang_base_url());
        }

        if (true == $this->recaptcha_status) {
            if (!$this->recaptcha_verify_request()) {
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', trans('msg_recaptcha'));
                redirect($this->agent->referrer());
                exit();
            }
        }

        //validate inputs
        $this->form_validation->set_rules('username', trans('username'), 'required|xss_clean|min_length[4]|max_length[100]');
        $this->form_validation->set_rules('email', trans('email_address'), 'required|xss_clean|max_length[200]');
        $this->form_validation->set_rules('password', trans('password'), 'required|xss_clean|min_length[4]|max_length[50]');
        $this->form_validation->set_rules('confirm_password', trans('password_confirm'), 'required|xss_clean|matches[password]');

        if (false === $this->form_validation->run()) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->auth_model->input_values());
            redirect($this->agent->referrer());
        } else {
            $email = $this->input->post('email', true);
            $username = $this->input->post('username', true);

            //is email unique
            if (!$this->auth_model->is_unique_email($email)) {
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', trans('msg_email_unique_error'));
                redirect($this->agent->referrer());
            }
            //is username unique
            if (!$this->auth_model->is_unique_username($username)) {
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', trans('msg_username_unique_error'));
                redirect($this->agent->referrer());
            }
            //register
            $user_id = $this->auth_model->register();
            if ($user_id) {
                $user = get_user($user_id);
                if (!empty($user)) {
                    //update slug
                    $this->auth_model->update_slug($user->id);
                    if (1 != $this->general_settings->email_verification) {
                        $this->auth_model->login_direct($user);
                        $this->session->set_flashdata('success', trans('msg_register_success'));
                        redirect(lang_base_url() . 'settings/update-profile');
                    }
                }
                redirect(lang_base_url() . 'register');
            } else {
                //error
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', trans('msg_error'));
                redirect(lang_base_url() . 'register');
            }
        }
    }

    /**
     * Logout.
     */
    public function logout()
    {
        $this->auth_model->logout();
        redirect($this->agent->referrer());
    }

    /**
     * Confirm Email.
     */
    public function confirm_email()
    {
        $data['title'] = trans('confirm_your_account');
        $data['description'] = trans('confirm_your_account') . ' - ' . $this->app_name;
        $data['keywords'] = trans('confirm_your_account') . ',' . $this->app_name;

        $token = trim($this->input->get('token', true));
        $data['user'] = $this->auth_model->get_user_by_token($token);

        if (empty($data['user'])) {
            redirect(lang_base_url());
        }
        if (1 == $data['user']->email_status) {
            redirect(lang_base_url());
        }

        if ($this->auth_model->verify_email($data['user'])) {
            $data['success'] = trans('msg_confirmed');
        } else {
            $data['error'] = trans('msg_error');
        }
        $this->load->view('partials/_header', $data);
        $this->load->view('auth/confirm_email', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Forgot Password.
     */
    public function forgot_password()
    {
        //check if logged in
        if (auth_check()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans('reset_password');
        $data['description'] = trans('reset_password') . ' - ' . $this->app_name;
        $data['keywords'] = trans('reset_password') . ',' . $this->app_name;

        $this->load->view('partials/_header', $data);
        $this->load->view('auth/forgot_password');
        $this->load->view('partials/_footer');
    }

    /**
     * Forgot Password Post.
     */
    public function forgot_password_post()
    {
        //check auth
        if (auth_check()) {
            redirect(lang_base_url());
        }

        $email = $this->input->post('email', true);
        //get user
        $user = $this->auth_model->get_user_by_email($email);

        //if user not exists
        if (empty($user)) {
            $this->session->set_flashdata('error', html_escape(trans('msg_reset_password_error')));
            redirect($this->agent->referrer());
        } else {
            $this->load->model('email_model');
            $this->email_model->send_email_reset_password($user->id);
            $this->session->set_flashdata('success', trans('msg_reset_password_success'));
            redirect($this->agent->referrer());
        }
    }

    /**
     * Reset Password.
     */
    public function reset_password()
    {
        //check if logged in
        if (auth_check()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans('reset_password');
        $data['description'] = trans('reset_password') . ' - ' . $this->app_name;
        $data['keywords'] = trans('reset_password') . ',' . $this->app_name;

        $token = $this->input->get('token', true);
        //get user
        $data['user'] = $this->auth_model->get_user_by_token($token);
        $data['success'] = $this->session->flashdata('success');

        if (empty($data['user']) && empty($data['success'])) {
            redirect(lang_base_url());
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('auth/reset_password');
        $this->load->view('partials/_footer');
    }

    /**
     * Reset Password Post.
     */
    public function reset_password_post()
    {
        $success = $this->input->post('success', true);
        if (1 == $success) {
            redirect(lang_base_url());
        }

        $this->form_validation->set_rules('password', trans('new_password'), 'required|xss_clean|min_length[4]|max_length[50]');
        $this->form_validation->set_rules('password_confirm', trans('password_confirm'), 'required|xss_clean|matches[password]');

        if (false == $this->form_validation->run()) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->profile_model->change_password_input_values());
            redirect($this->agent->referrer());
        } else {
            $user_id = $this->input->post('id', true);
            if ($this->auth_model->reset_password($user_id)) {
                $this->session->set_flashdata('success', trans('msg_change_password_success'));
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', trans('msg_change_password_error'));
                redirect($this->agent->referrer());
            }
        }
    }

    /**
     * Send Activation Email.
     */
    public function send_activation_email_post()
    {
        post_method();
        $user_id = $this->input->post('id', true);
        $token = $this->input->post('token', true);
        $type = $this->input->post('type', true);
        if ('login' == $type) {
            $this->session->set_flashdata('success', trans('msg_send_confirmation_email') . "&nbsp;<a href='javascript:void(0)' class='link-resend-activation-email' onclick=\"send_activation_email('" . $user_id . "','" . $token . "');\">" . trans('resend_activation_email') . '</a>');
        } else {
            $this->session->set_flashdata('success', trans('msg_send_confirmation_email') . "&nbsp;<a href='javascript:void(0)' class='link-resend-activation-email' onclick=\"send_activation_email_register('" . $user_id . "','" . $token . "');\">" . trans('resend_activation_email') . '</a>');
        }

        $data = [
            'result' => 1,
            'success_message' => $this->load->view('partials/_messages', '', true),
        ];
        echo json_encode($data);
        reset_flash_data();
        $this->auth_model->send_email_activation($user_id, $token);
    }
}

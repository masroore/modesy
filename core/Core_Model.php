<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Core_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        ci_core_construct();

        //check auth
        $this->auth_check = auth_check();
        if ($this->auth_check) {
            $this->auth_user = user();
        }
    }
}

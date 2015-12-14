<?php

class util extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('user_agent');
    }

    public function permission_denied() {
        $this->load_view('util/permission_denied');
    }

    public function setLanguage() {
        if ($this->uri->segment(3) == "ro") {
            $cookie = array(
                'name' => 'site_language',
                'value' => "ro",
                'expire' => time() + 10 * 365 * 24 * 60 * 60,
                'path' => "/"
            );
           
             set_cookie($cookie);
        } else {
            $cookie = array(
                'name' => 'site_language',
                'value' => "en",
                'expire' => time() + 10 * 365 * 24 * 60 * 60,
                'path' => "/"
            );
        }
        set_cookie($cookie);
        redirect($this->agent->referrer());
        exit();
    }

}

?>

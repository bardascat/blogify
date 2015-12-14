<?php

class testimoniale extends PUBLIC_Controller {

    public function __construct() {

        parent::__construct();

        $this->view->setMetaTitle("Helpie - Testimoniale");
    }

    public function index() {
        $data = array();

        $this->load_view("testimoniale/landing", $data);
    }

}

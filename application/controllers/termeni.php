<?php

class termeni extends PUBLIC_Controller {

    public function __construct() {

        parent::__construct();
    }

    public function index() {
        $data = array();

        $this->load_view("termeni/landing", $data);
    }
  

}

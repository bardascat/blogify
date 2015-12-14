<?php

class simple_page extends \CI_Controller {

    private $LandingModel;

    function __construct() {
        parent::__construct();
        $this->LandingModel = new \BusinessLogic\Models\LandingModel();
    }

    public function index() {
        $slug = $this->uri->segment(1);
        if (!$slug)
            show_404();

        $page = $this->LandingModel->getPage($slug);
        if (!$page)
            show_404();
        $this->load_view('simplepage/index', array("page" => $page));
    }

}

?>

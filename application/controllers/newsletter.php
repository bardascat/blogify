<?php

class newsletter extends \CI_Controller {

    private $OffersModel;
    private $User;

    function __construct() {
        parent::__construct();
        $this->OffersModel = new \BusinessLogic\Models\OffersModel();
        $this->load->library('user_agent');
        $this->User = $this->getLoggedUser(true);
        $this->load->library('form_validation');
    }

    public function index() {
        $offers = $this->OffersModel->getNewsletterOffers();
        $this->load->view('newsletter/general', array("offers" => $offers));
    }

    public function send_newsletter() {
        $email = 'bardas.catalin@yahoo.com';

        $body = file_get_contents(base_url('newsletter'));
        $subject = "Test newsletter";
        \NeoMail::genericMail($body, $subject, $email);
        echo 'done';
    }

    public function subscribe() {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array("type" => "error"));
        } else {
            $status = $this->UserModel->newsletterSubscribe($email);
            if (!$status)
                echo json_encode(array("type" => "already"));
            else
                echo json_encode(array("type" => "success"));
        }
        exit();
    }

}

?>

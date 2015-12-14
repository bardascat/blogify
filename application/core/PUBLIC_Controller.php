<?php

/** @property  Auth  $auth  */
class PUBLIC_Controller extends CI_Controller {

    private static $instance;

    /**
     *
     * @var \BusinessLogic\Models\UserModel
     */
    protected $UserModel;

    /**
     * @var \BusinessLogic\Models\CategoriesModel
     */
    protected $CategoriesModel = null;

    /**
     * @var \BusinessLogic\Models\PagesModel
     */
    protected $PagesModel = null;

    function __construct() {
        parent::__construct();

        
        $this->view->setNotification($this->session->flashdata('notification'));

        $this->view->auth = $this->auth;
        //$this->initDependencies();
        // $this->devMode();
    }

    public static function &get_instance() {
        return self::$instance;
    }

    public function load_view($view, $data = array()) {
        $this->load->view('header', $data);
        $this->load->view($view, $data);

        if (!isset($data['no_footer']))
            $this->load->view('footer');
    }

    public function load_view_admin($view, $vars = array()) {
        $this->load->view('admin/header', $vars);
        $this->load->view($view, $vars);
    }

    public function load_view_admin_popup($view, $vars = array()) {
        $this->load->view('admin/header_popup', $vars);
        $this->load->view($view, $vars);
    }

    public function load_view_user($view, $data = array()) {
        $this->load->view('user/header', $data);
        $this->load->view($view, $data);
        if (!isset($data['no_footer']))
            $this->load->view('footer');
    }

    /**
     * Genereaza un un hash unic pentru cart
     * @return type
     */
    protected static function setHash() {
        if (get_cookie('cart_id')) {
            $cookie_id = unserialize(get_cookie('cart_id'));
            return $cookie_id;
        } else {
            $cookie_id = self::generateHash();
            $cookie = array(
                'name' => 'cart_id',
                'value' => serialize($cookie_id),
                'expire' => time() + 10 * 365 * 24 * 60 * 60,
                'path' => "/"
            );
            set_cookie($cookie);

            return $cookie_id;
        }
    }

    public static function getCartHash() {
        return self::setHash();
    }

    private static function generateHash() {
        if (isset($_SERVER['HTTP_USER_AGENT']))
            return md5(uniqid(microtime()) . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
        else
            return false;
    }

    private function initDependencies() {
        /*        $this->UserModel = new \BusinessLogic\Models\UserModel();
          $this->PagesModel=new \BusinessLogic\Models\PagesModel();
          $this->CategoriesModel = new BusinessLogic\Models\CategoriesModel();

          $this->view->setPagesModel($this->PagesModel);
          $this->view->setUser($this->getLoggedUser());
          $this->view->setPages($this->PagesModel->getPages());
          $this->view->setCategories($this->CategoriesModel->getRootCategories(true));
          $this->view->setNotification($this->session->flashdata('notification'));
          $this->generateAclResources();
          self::setHash(); */
    }

    private function devMode() {
        return false;
        if (isset($_POST['access']) && $_POST['access'] == "calorifer") {
            $cookie = array(
                'name' => 'secret_access',
                'value' => 'all',
                'expire' => time() + 10 * 365 * 24 * 60 * 60,
                'path' => "/"
            );
            set_cookie($cookie);
            redirect(base_url());
            exit();
        }
        $access = get_cookie('secret_access');
        if (!$access) {

            echo "<form method='post'><input type='text' name='access'/></form>";
            exit();
        }
    }

}

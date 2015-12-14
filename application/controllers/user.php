<?php

class user extends PUBLIC_Controller {

    private $OrderModel;
    private $EmailModel;

    function __construct() {

        parent::__construct();

        $this->OrderModel = new \BusinessLogic\Models\OrderModel();

        $this->EmaiModel = new \BusinessLogic\Models\EmailModel();

        $this->load->library('form_validation');
    }

    public function login() {

        $nomenclator = new \BusinessLogic\Models\NomenclatorModel();

        $pachete = $nomenclator->getPachete(false);

        //userul este deja logat, ii facem redirect la homepage

        if ($this->auth->isLoggedIn()) {

            redirect(base_url('account'));
        }

        $data = array(
            "pachete" => $pachete
        );

        $this->load_view('user/login', $data);
    }

    public function login_submit() {

        $nomenclator = new \BusinessLogic\Models\NomenclatorModel();

        $pachete = $nomenclator->getPachete(false);


//userul este deja logat, ii facem redirect la homepage

        if ($this->auth->isLoggedIn()) {

            redirect(base_url());
        }

//procesam requestul

        $this->form_validation->set_rules('password', 'parola', 'required|xss_clean');

        $this->form_validation->set_rules('email', 'email', 'required');

        $msgRequired = 'Campul <b>%s</b> este obligatoriu';
        if (BusinessLogic\Util\Language::getLanguage() == "en") {
            $msgRequired = 'Please fill in <b>%s</b>.';
        }
        $this->form_validation->set_message('required', $msgRequired);

        if ($this->form_validation->run() == FALSE) {

            $this->session->set_flashdata('notification', array("type" => "error", "html" => (BusinessLogic\Util\Language::getLanguage() == "en" ? "Invalid credentials" : "Date incorecte")));

            $this->load_view('user/login', array(
                "no_footer" => true,
                "no_header" => true,
                "pachete" => $pachete,
                "notification" => array(
                    "type" => "form_notification",
                    "message" => validation_errors(),
                    "cssClass" => "error"
            )));
        } else {

            $iUserId = $this->auth->authenticateUser($this->input->post('email'), $this->input->post('password'), TRUE);

            if (!$iUserId) {

                $this->session->set_flashdata('notification', array("type" => "error", "html" => "Date Incorecte"));

//datele introduse nu sunt corecte

                $this->load_view('user/login', array(
                    "no_footer" => true,
                    "no_header" => true,
                    "notification" => array(
                        "type" => "form_notification",
                        "message" => "Datele introduse sunt incorecte",
                        "cssClass" => "error",
                )));
            } else {

                $this->auth->disconnectAccount($iUserId);

                //Conecteaza utilizatorul curent

                $this->auth->loginUser($iUserId);



                //userul a fost logat

                redirect(base_url('account'));
            }
        }
    }

    public function logout() {

        $this->auth->logout();

        redirect(base_url());
    }

    public function register() {



        $nomenclator = new \BusinessLogic\Models\NomenclatorModel();

        $pachete = $nomenclator->getPachete(false);





        //userul este deja logat, ii facem redirect la homepage

        if ($this->auth->isLoggedIn()) {

            redirect(base_url('account'));
        }

        $data = array(
            "no_footer" => true,
            "no_header" => true,
            "pachete" => $pachete
        );

        $this->load_view('user/register', $data);
    }

    public function register_submit() {

        if (!$_POST)
            redirect(base_url('account/register'));



        $nomenclator = new \BusinessLogic\Models\NomenclatorModel();



        $pachete = $nomenclator->getPachete(false);



//userul este deja logat, ii facem redirect la homepage

        if ($this->auth->isLoggedIn()) {

            redirect(base_url());
        }

//procesam requestul

        $this->form_validation->set_rules('newPassword', 'Parola', 'required|xss_clean|min_length[6]');

        $this->form_validation->set_rules('lastname', 'Nume', 'required|xss_clean');

        $this->form_validation->set_rules('firstname', 'Prenume', 'required|xss_clean');

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        //$this->form_validation->set_rules('agreement', 'Termeni si conditii', 'callback_accept_terms');

        $this->form_validation->set_message('required', 'Campul <b>%s</b> este obligatoriu');

        $this->form_validation->set_message('min_length', '%s prea scurta. Minim %s caractere');


        if ($this->form_validation->run() == FALSE) {

            $this->load_view('user/login', array(
                "no_footer" => true,
                "no_header" => true,
                "pachete" => $pachete,
                "notification" => array(
                    "type" => "form_notification",
                    "message" => "Contul nu a fost creat, completati toate datele",
                    "cssClass" => "error"
                )
            ));
        } else {



            $UserModel = new \BusinessLogic\Models\UserModel();



            $_POST['rol_id'] = 2;

            $_POST['user_activ'] = 1;





            $bResponse = $UserModel->editUser($_POST);



            if (is_a($bResponse, "\BusinessLogic\Models\Entities\User")) {



                $this->auth->disconnectAccount($bResponse->getId_user());

                //Conecteaza utilizatorul curent

                $this->auth->loginUser($bResponse->getId_user());



                //facem comanda de pachet



                header("Location:" . base_url('neocart/process_payment?payment_method=' . $_POST['payment_method'] .
                                "&order_type=" . $_POST['order_type'] . "&id_pachet=" . $_POST['id_pachet']));

                exit();
            } else {

                $this->load_view('user/register', array(
                    "no_footer" => true,
                    "no_header" => true,
                    "pachete" => $pachete,
                    "notification" => array(
                        "type" => "form_notification",
                        "message" => $bResponse,
                        "cssClass" => "error"
                )));
            }
        }
    }

    private function login_user($email, $password = false) {

        /* @var  $user \BusinessLogic\Models\Entities\User  */

        if (!$password || $password == DLConstants::$MASTER_PASSWORD)
            $user = $this->UserModel->checkEmail($email);
        else
            $user = $this->UserModel->find_user($email, sha1($password));



        if ($user) {

            $cookie = array('id_user' => $user->getId_user(), 'email' => $user->getEmail(), 'role' => $user->getAclRole()->getName(), "gender" => $user->getGender(), "firstname" => $user->getFirstname(), "lastname" => $user->getLastname(), "username" => $user->getUsername());

            $cookie = array(
                'name' => 'dl_loggedin',
                'value' => serialize($cookie),
                'expire' => time() + 10 * 365 * 24 * 60 * 60,
                'path' => "/"
            );

            set_cookie($cookie);

            return $user;
        }

        return false;
    }

    public function fblogin() {



// incarcam modelul 



        $fb_data = $this->UserModel->fbLogin();



        if ((!$fb_data['uid']) || (!$fb_data['me'])) {

// If this is a protected section that needs user authentication
// you can redirect the user somewhere else
// or take any other action you need
// redirect('cart');



            if (isset($_GET['return_fb'])) {

//a venit dupa facebook dar nu a luat nimic
//redirect(base_url('account/fberror?msg=01'));

                exit();
            } else {



                header('Location:' . $fb_data['loginUrl']);

                exit();
            }
        } else {

            $userData = $fb_data['me'];



            if (!$userData['email']) {

                redirect(base_url('account/fberror?msg=02'));

                exit();
            } else {

//procesam datele primite de pe facebook

                $user = $this->UserModel->checkEmail($userData['email']);

                if ($user) {

//contul exista, il logam doar

                    $this->login_user($user->getEmail());

                    $this->session->set_flashdata('notification', array("type" => "success", "html" => "Autentificare cu succes!"));
                } else {

//facem un cont nou

                    $userData['gender'] = ($userData['gender'] == "male" ? "m" : "f");

                    if (isset($userData['location']['name'])) {

                        $location = explode(',', $userData['location']['name']);

                        $city = (isset($location[0]) ? $location[0] : "Bucuresti");

                        if ($city == "Bucharest")
                            $city = "Bucuresti";
                    }

                    if (!isset($city))
                        $city = "Bucuresti";

                    $userData['city'] = $city;

                    $date = new DateTime($userData['birthday']);

                    $now = new DateTime();

                    $interval = $now->diff($date);

                    $age = $interval->y;

                    switch ($age) {

                        case ($age >= 18 && $age <= 25): {

                                $userData['age'] = "18-25";
                            }break;

                        case ($age > 25 && $age <= 30): {

                                $userData['age'] = "25-30";
                            }break;

                        case ($age > 30 && $age <= 40): {

                                $userData['age'] = "30-40";
                            }break;

                        case ($age > 40): {

                                $userData['age'] = ">40";
                            }break;

                        default: {

                                $userData['age'] = "18-25";
                            }break;
                    }





                    $userData['lastname'] = $userData['last_name'];

                    $userData['firstname'] = $userData['first_name'];

                    $userData['role'] = DLConstants::$USER_ROLE;

                    $userData['fb'] = 1;



                    $r = $this->UserModel->createUser($userData);

                    $user = $this->login_user($userData['email']);

                    $this->session->set_flashdata('notification', array("type" => "success", "html" => "Contul dumneavoastra a fost creat. Va multumim !"));
                }

                redirect(base_url('account'));
            }
        }
    }

    public function fberror() {

        show_404();
    }

    public function accept_terms() {

        if (isset($_POST['agreement']))
            return true;

        $this->form_validation->set_message('accept_terms', 'Va rugam sa acceptati termenii si conditiile');

        return false;
    }

    public function password_match() {

        $old_password = $_POST['old_password'];

        if (sha1($old_password) != $this->getLoggedUser(true)->getPassword()) {

            $this->form_validation->set_message('password_match', 'Parola veche este incorecta');

            return false;
        } else
            return true;
    }

    public function getTransactions() {
        
    }

    public function login_submit_mobile() {



        $UserModel = new \BusinessLogic\Models\UserModel();



        $iUserId = $this->auth->authenticateUser($this->input->post('email'), $this->input->post('password'), TRUE);





        if (!$iUserId) {

            echo json_encode(array(
                "status" => 0,
                "data" => false,
                "mesaj" => "Datele introduse sunt incorecte"
            ));
        } else {

            $user = $UserModel->getUserByPk($iUserId);

            $activPachet = $user->getActivePachet();

            if (!$activPachet) {

                echo json_encode(array(
                    "status" => 0,
                    "data" => false,
                    "mesaj" => "Nu aveti niciun pachet cumparat"
                ));

                exit();
            }

            if (!$activPachet->getIsEnabled()) {

                echo json_encode(array(
                    "status" => 0,
                    "data" => false,
                    "mesaj" => "Pachetul dumneavoastră este inactiv"
                ));

                exit();
            }

            if ($activPachet->getIsExpired()) {

                echo json_encode(array(
                    "status" => 0,
                    "data" => false,
                    "mesaj" => "Pachetul dumneavoastră a expirat in data: " . $activPachet->getExpireDate()
                ));

                exit();
            }

            $pachet = $user->getActivePachet();

            echo json_encode(array(
                "status" => 1,
                "data" => array(
                    "id_user" => $user->getId_user(),
                    "last_name" => $user->getLastname(),
                    "first_name" => $user->getFirstname(),
                    "pachet_nume" => $pachet->getName(),
                    "pachet_pret" => $pachet->getPrice(),
                    "pachet_expired" => $pachet->getIsExpired(),
                    "pachet_active" => $pachet->getIsEnabled(),
                    "pachet_valabilitate" => $pachet->getExpireDate(),
                    "sold" => $user->getSold()
                ),
                "mesaj" => "ok"
            ));
        }
    }

    public function getMessagesMobile() {

        $UserModel = new \BusinessLogic\Models\UserModel();

        $id = $this->input->get("id_user");

        $user = $UserModel->getUserByPk($id);

        if (!$user) {

            echo json_encode(array(
                "status" => 0,
                "data" => false,
                "mesaj" => "Invalid User id"
            ));

            exit();
        }



        $this->user = $user;



        $UserModel->setMessagesViewed($user);



        if (!$this->input->get("message_type") || $this->input->get("message_type") == "inbox") {

            $type = "inbox";

            $messages = $this->user->getInbox();
        } else {

            $type = "outbox";

            $messages = $this->user->getSent();
        }



        $msg = array();

        if (count($messages)) {

            foreach ($messages as $message) {

                $std = $message->generateStdObject();

                $std->date = $message->getcDate()->format("d-m-Y");



                switch ($type) {

                    case "inbox": {

                            $name = "From: " . $message->getFrom()->getFirstname() . " " . $message->getFrom()->getLastname();
                        }break;

                    case "outbox": {

                            $name = "To: " . $message->getTo()->getFirstname();
                        }break;
                };



                $std->name = $name;

                $msg[] = $std;
            }



            echo json_encode(array(
                "status" => 1,
                "data" => $msg,
                "mesaj" => "ok"
            ));
        } else {

            echo json_encode(array(
                "status" => 1,
                "data" => false,
                "mesaj" => "Nu aveti niciun mesaj"
            ));
        }
    }

    public function sendMessageMobile() {



        $UserModel = new \BusinessLogic\Models\UserModel();

        $id = $this->input->post("id_user");

        $user = $UserModel->getUserByPk($id);

        if (!$user) {

            echo json_encode(array(
                "status" => 0,
                "data" => false,
                "mesaj" => "Invalid User id"
            ));

            exit();
        }



        $this->user = $user;



        $taskModel = new \BusinessLogic\Models\TaskModel();

        $to = $taskModel->getAvailableOperator();

        $_POST['to_email'] = $to->getId_user();

        $_POST['title'] = 'Mesaj de la clientul: ' . $this->user->getFirstname() . " " . $this->user->getLastname();

        $emailModel = new \BusinessLogic\Models\EmailModel();

        $emailModel->sendEmail($this->user, $_POST);



        echo json_encode(array(
            "status" => 1,
            "data" => false,
            "mesaj" => "ok"
        ));

        exit();
    }

    public function getTransactionsMobile() {



        $UserModel = new \BusinessLogic\Models\UserModel();

        $id = $this->input->get("id_user");

        $transaction_type = $this->input->get("id_user");

        $user = $UserModel->getUserByPk($id);

        if (!$user) {

            echo json_encode(array(
                "status" => 0,
                "data" => false,
                "mesaj" => "Invalid User id"
            ));

            exit();
        }

        $this->user = $user;

        $transactions = $UserModel->getTransactions($this->user, $_GET);



        $data = array();

        foreach ($transactions as $transaction) {

            $std = $transaction->generateStdObject();

            $std->stamp = $transaction->getStamp()->format("d-m-Y H:i");

            switch ($transaction->getType()) {

                case App_constants::$TRANZACTIE_CHELTUIELI: {

                        $std->valueHtml = "<span style='color:red'>-" . $transaction->getValue() . '</span>';
                    }break;

                default: {

                        $std->valueHtml = "<span style='color:green'>+" . $transaction->getValue() . '</span>';
                    }break;
            }

            $data[] = $std;
        }



        echo json_encode(array(
            "status" => 1,
            "currentSold" => $user->getSold(),
            "data" => (count($data) ? $data : false),
            "mesaj" => "ok"
        ));

        exit();
    }

    public function getServiciiMobile() {



        $UserModel = new \BusinessLogic\Models\UserModel();

        $id = $this->input->get("id_user");

        $user = $UserModel->getUserByPk($id);

        if (!$user) {

            echo json_encode(array(
                "status" => 0,
                "data" => false,
                "mesaj" => "Invalid User id"
            ));

            exit();
        }

        $this->user = $user;

        $pachet = $user->getActivePachet();

        $servicii = $pachet->getServicii();

        $servicii_array = array();

        foreach ($servicii as $serviciu) {

            $servicii_array[] = $serviciu->generateStdObject();
        }

        echo json_encode(array(
            "status" => 1,
            "data" => (count($servicii_array) ? $servicii_array : false),
            "mesaj" => "lista servicii"
        ));

        exit();
    }

    public function solicitareTaskMobile() {



        $UserModel = new \BusinessLogic\Models\UserModel();

        $id = $this->input->post("id_user");

        $user = $UserModel->getUserByPk($id);

        if (!$user) {

            echo json_encode(array(
                "status" => 0,
                "data" => false,
                "mesaj" => "Invalid User id"
            ));

            exit();
        }

        $this->user = $user;







        $errors = "";

        if (!$_POST['id_serviciu'] || !$_POST['date'])
            $errors.="Serviciul si data finalizare sunt campuri obligatorii";



        else {

            $date = date("Y-m-d", strtotime(($_POST['date'])));

            if ($date < date("Y-m-d"))
                $errors.="Data de finalizare este incorecta.";
        }



        if ($errors) {

            echo json_encode(array(
                "status" => 0,
                "data" => false,
                "mesaj" => $errors
            ));
        } else {

            if (isset($_FILES["file"])) {

                if ($_FILES["file"]["tmp_name"]) {

                    $dir = "userUploads/" . $this->user->getId_user();

                    if (!file_exists($dir)) {

                        mkdir($dir, 0777, true);
                    }

                    $file_name = rand(0, 999999);

                    $file_name.=".jpg";

                    move_uploaded_file($_FILES["file"]["tmp_name"], $dir . "/" . $file_name);

                    $file = $dir . "/" . $file_name;
                }
            }





            $_POST['file'] = (isset($file) ? $file : false);

            $operator = $this->EmaiModel->solicitareTask($_POST, $this->user);

            if (!$operator) {

                $msg = "Nu exista niciun operator disponibil. Va rugam contactati echipa Helpie.";
            } else {

                $msg = "Solicitarea a fost creata  si atribuita operatorului: " . $operator->getFirstname() . " " . $operator->getLastname();
            }



            echo json_encode(array(
                "status" => 1,
                "data" => false,
                "mesaj" => $msg
            ));
        }
    }

    public function getUserDetailsMobile() {



        $UserModel = new \BusinessLogic\Models\UserModel();

        $id = $this->input->get("id_user");

        $user = $UserModel->getUserByPk($id);

        if (!$user) {

            echo json_encode(array(
                "status" => 0,
                "data" => false,
                "mesaj" => "Invalid User id"
            ));

            exit();
        }

        $this->user = $user;



        $std = $user->generateStdObject();

        $notifications = $user->getUserNotifications();

        $notifications_array = array();

        if ($notifications) {

            foreach ($notifications as $notification) {

                $notifications_array[] = $notification->generateStdObject();
            }
        }

        $std->notifications = (count($notifications_array) ? $notifications_array : false);



        echo json_encode(array(
            "status" => 1,
            "data" => $std,
            "mesaj" => "ok"
        ));
    }

    public function updateUserMobile() {

        $UserModel = new \BusinessLogic\Models\UserModel();

        $id = $this->input->post("id_user");

        $user = $UserModel->getUserByPk($id);

        if (!$user) {

            echo json_encode(array(
                "status" => 0,
                "data" => false,
                "mesaj" => "Invalid User id"
            ));

            exit();
        }

        $this->user = $user;



        $this->form_validation->set_rules('phone', 'Telefon', 'required|numeric|xss_clean');

        $this->form_validation->set_rules('lastname', 'Nume', 'required|xss_clean');

        $this->form_validation->set_rules('firstname', 'Prenume', 'required|xss_clean');

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        $this->form_validation->set_rules('serie_buletin', 'Serie Buletin', 'required|xss_clean');

        $this->form_validation->set_rules('address', 'Adresa', 'required|xss_clean');

        $this->form_validation->set_message('required', 'Campul <b>%s</b> este obligatoriu');



        if ($this->form_validation->run() == FALSE) {



            echo json_encode(array(
                "status" => 0,
                "data" => false,
                "mesaj" => "Va rugam completati toate datele."
            ));
        } else {



            $UserModel->updateUserMobile($_POST, $user);



            echo json_encode(array(
                "status" => 1,
                "data" => false,
                "mesaj" => "Datele au fost salvate."
            ));
        }



        exit();
    }

    public function getUnseenMessages() {



        $UserModel = new \BusinessLogic\Models\UserModel();

        $id = $this->input->get("id_user");

        $user = $UserModel->getUserByPk($id);

        if (!$user) {

            echo json_encode(array(
                "status" => 0,
                "data" => false,
                "mesaj" => "Invalid User id"
            ));

            exit();
        }

        $this->user = $user;



        echo json_encode(array(
            "status" => 1,
            "data" => $UserModel->getUnseenComments($this->user),
            "mesaj" => "ok"
        ));

        exit();
    }

    public function refreshUserData() {



        $UserModel = new \BusinessLogic\Models\UserModel();

        $id = $this->input->get("id_user");

        $user = $UserModel->getUserByPk($id);

        if (!$user) {

            echo json_encode(array(
                "status" => 0,
                "data" => false,
                "mesaj" => "Invalid User id"
            ));

            exit();
        }

        $this->user = $user;



        $data = array(
            "sold" => $user->getSold(),
            "valabilitate_pachet" => $user->getActivePachet()->getExpireDate()
        );

        echo json_encode(array(
            "status" => 1,
            "data" => $data,
            "mesaj" => "ok"
        ));
    }

}
?>


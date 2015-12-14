<?php

/**
 *
 * @author Neo aka Bardas Catalin
 */
class neocart extends PUBLIC_Controller {

    private $OrdersModel;

    function __construct() {
        parent::__construct();
        $this->load->library('user_agent');
        $this->OrdersModel = new \BusinessLogic\Models\OrderModel();



        if (!$this->auth->getUserDetails()) {
            // redirect(base_url('account/login'));
        }
        $this->view->setUser($this->auth->getUserDetails());
    }

    public function finalizare_op() {

        $order = $this->OrdersModel->getOrderByCode($this->input->get("code"));
        if (!$order)
            show_404();

        $this->load_view_user('user/finalizare_op', array("user" => $this->auth->getUserDetails(), "order" => $order, "arrow" => "transactions"));
    }

    public function finalizare_card() {
        $order = $this->OrdersModel->getOrderByCode($this->input->get("code"));

        if (!$order)
            show_404();

        $this->load_view_user('user/finalizare_card', array("user" => $this->auth->getUserDetails(), "order" => $order, "arrow" => "transactions"));
    }

    public function index() {
        $cart = $this->NeoCartModel->getCart(self::getCartHash());
        $this->load_view('cart/index', array("neoCart" => $cart));
    }

    public function update_quantity() {
        if (!isset($_POST['cartItem']))
            exit("Page not found");

        $this->NeoCartModel->updateQuantity($_POST);

        redirect($this->agent->referrer());
    }

    public function deleteCartItem() {
        $this->NeocartModel->deleteCartItem($_POST['cartItem']);
        redirect($this->agent->referrer());
    }

    public function add_to_cart() {
        $hash = $this->getCartHash();
        $cart = $this->NeoCartModel->getCart($hash);

        //hai sa bagam produsul in shopping cart
        $this->NeoCartModel->addToCart($_POST, $cart);
    }

    /**
     * @AclResource User: Genereaza Comanda
     */
    public function process_payment() {

        if (!$this->auth->isLoggedIn()) {
            redirect(base_url('user/login'));
        }

        $user = $this->auth->getUserDetails();


        $data = false;
        if (count($_POST))
            $data = $_POST;
        else
            $data = $_GET;

        $data['id_client'] = $user->getId_user();

        switch ($data['payment_method']) {
            case "CARD": {
                    $this->processCardPayment($data);
                }break;
            case "OP": {
                    $this->processOpPayment($data);
                }break;
            case "RAMBURS": {
                    //  $this->processRambursPayment();
                }break;
            case "FREE": {
                    $this->processFreePayment();
                }break;
        }
    }

    private function processFreePayment() {

        /* @var $order Entity\Order */
        $order = $this->NeoCartModel->insertOrder($this->getLoggedUser(true), $_POST);


        $email = $order->getUser()->getEmail();

        ob_start();
        require_once("application/views/mailMessages/freeOrder.php");
        $body = ob_get_clean();
        $subject = "Comanda " . $order->getOrderNumber() . DLConstants::$WEBSITE_COMMERCIAL_NAME;

        /**
         * Nu mai generam nimic, serverul e prea praf si dureaza mult finalizarea comenzii.
         * Poate un request ajax in pagina de thankyou
         * 
          $vouchers = $this->NeoCartModel->generateVouchers($order);
          NeoMail::genericMailAttach($body, $subject, $email, $vouchers);
          //$this->informOwner($order);
         */
        redirect(base_url('account/finalizare?type=free&code=' . $order->getOrderNumber()));
        exit();
    }

    private function processOpPayment($data) {
        /* @var $order Entity\Order */
        switch ($data['order_type']) {
            case "alimentare": {
                    $order = $this->OrdersModel->alimentareCont($data);
                }break;
            case "pachet": {
                    $order = $this->OrdersModel->buyPachet($data);
                }break;
            default: {
                    echo "<h1>Ooops, eroare. Contactati administratorul helpie.</h1>";
                    exit();
                }break;
        }


        //$email = $order->getUser()->getEmail();
        //NeoMail::getInstance()->genericMail($body, $subject, $email);
        //$this->informOwner($order);
        header("Location: " . base_url('neocart/finalizare_op?code=' . $order->getOrderNumber()));
    }

    private function processRambursPayment() {
        /* @var $order Entity\Order */
        $order = $this->NeoCartModel->insertOrder($this->logged_user['orm'], $_POST);

        $email = $order->getUser()->getEmail();
        $vouchers = $this->NeoCartModel->generateVouchers($order);

        ob_start();
        require_once("mailMessages/rambursConfirm.php");
        $body = ob_get_clean();
        $subject = "Confirmare comandă nr. " . $order->getOrderNumber();

        if ($vouchers)
            NeoMail::getInstance()->genericMailAttach($body, $subject, $email, $vouchers);
        else
            NeoMail::getInstance()->genericMail($body, $subject, $email);

        $this->informOwner($order);
        header('Location:' . URL . 'cont/finalizare_ramburs?code=' . $order->getOrderNumber());
        exit();
    }

    private function processCardPayment($data) {

        switch ($data['order_type']) {
            case "alimentare": {
                    $order = $this->OrdersModel->alimentareCont($data);
                }break;
            case "pachet": {
                    $order = $this->OrdersModel->buyPachet($data);
                }break;
            default: {
                    echo "<h1>Ooops, eroare. Contactati administratorul helpie.</h1>";
                    exit();
                }break;
        }

        require_once 'application/libraries/Mobilpay/Payment/Request/Abstract.php';
        require_once 'application/libraries/Mobilpay/Payment/Request/Card.php';
        require_once 'application/libraries/Mobilpay/Payment/Invoice.php';
        require_once 'application/libraries/Mobilpay/Payment/Address.php';


        $paymentUrl = 'http://sandboxsecure.mobilpay.ro';
        //$paymentUrl = 'https://secure.mobilPay.ro';
        $x509FilePath = 'application/libraries/Mobilpay/public.cer';
        try {
            srand((double) microtime() * 1000000);
            $objPmReqCard = new \Mobilpay_Payment_Request_Card();
            $objPmReqCard->signature = 'NVQD-8THN-YH1P-QY3M-D5T9';
            $objPmReqCard->orderId = $order->getOrderNumber();

            $objPmReqCard->confirmUrl = base_url('neocart/payment_confirm');
            $objPmReqCard->returnUrl = base_url('neocart/finalizare_card?code=' . $order->getOrderNumber());

            $objPmReqCard->invoice = new \Mobilpay_Payment_Invoice();
            $objPmReqCard->invoice->currency = 'RON';

            $objPmReqCard->invoice->customer_type = 2;


            $total = $order->getTotal();
            if (!$total)
                exit("ERROR: 3:31, Please contact administrator !");

            $objPmReqCard->invoice->amount = $total;
            $objPmReqCard->invoice->details = 'Tranzactii Helpie';

            $billingAddress = new \Mobilpay_Payment_Address();
            $billingAddress->type = "person";
            $billingAddress->firstName = $order->getUser()->getFirstname();
            $billingAddress->lastName = $order->getUser()->getLastname();
            $billingAddress->email = $order->getUser()->getEmail();
            $billingAddress->mobilePhone = $order->getUser()->getPhone();

            $objPmReqCard->invoice->setBillingAddress($billingAddress);

            $objPmReqCard->encrypt($x509FilePath);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        $e = "";

        echo '
        <div class="span-15 prepend-1" style="margin:0 auto; text-align:center; margin-top:100px;">
        <?php if (!($e instanceof Exception)): ?>
                <p>
                <form name="frmPaymentRedirect" method="post" action="' . $paymentUrl . '">
                    <input type="hidden" name="env_key" value="' . $objPmReqCard->getEnvKey() . '"/>
                    <input type="hidden" name="data" value="' . $objPmReqCard->getEncData() . '"/>
                    <p>	

                        Pentru a finaliza plata vei redirectat catre pagina de plati securizata a mobilpay.ro
                    </p>
                    <p>
                        Daca nu esti redirectat in 3 secunde apasa <input type="image" value="Redirect"/>
                    </p>
                </form>
            </p>
            <script type="text/javascript" language="javascript">
               window.setTimeout(document.frmPaymentRedirect.submit(),1000);
            </script>
        <?php else: ?>
            <p><strong><?php echo $e->getMessage(); ?></strong></p>
        <?php endif; ?>
</div>';
    }

    public function payment_confirm() {

        require_once 'application/libraries/Mobilpay/Payment/Request/Abstract.php';
        require_once 'application/libraries/Mobilpay/Payment/Request/Card.php';
        require_once 'application/libraries/Mobilpay/Payment/Invoice.php';
        require_once 'application/libraries/Mobilpay/Payment/Request/Notify.php';
        require_once 'application/libraries/Mobilpay/Payment/Address.php';

        $errorCode = 0;
        $errorType = \Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_NONE;


        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'post') == 0) {
            if (isset($_POST['env_key']) && isset($_POST['data'])) {
                $privateKeyFilePath = 'application/libraries/Mobilpay/private.key';

                try {
                    $objPmReq = \Mobilpay_Payment_Request_Abstract::factoryFromEncrypted($_POST['env_key'], $_POST['data'], $privateKeyFilePath);


                    $order = $this->OrdersModel->getOrderByCode($objPmReq->orderId);

                    if ($objPmReq->objPmNotify->errorCode != 0) {

                        $this->OrdersModel->setOrderPaymentStatus(App_constants::$PAYMENT_STATUS_CANCELED, $order);
                    } else
                        switch ($objPmReq->objPmNotify->action) {
                            #orice action este insotit de un cod de eroare si de un mesaj de eroare. Acestea pot fi citite folosind $cod_eroare = $objPmReq->objPmNotify->errorCode; respectiv $mesaj_eroare = $objPmReq->objPmNotify->errorMessage;
                            #pentru a identifica ID-ul comenzii pentru care primim rezultatul platii folosim $id_comanda = $objPmReq->orderId;
                            case 'confirmed': {
                                    $this->OrdersModel->setOrderPaymentStatus(App_constants::$PAYMENT_STATUS_CONFIRMED, $order);

                                    $email = $order->getUser()->getEmail();

                                    $subject = "Confirmare comandă nr. " . $order->getOrderNumber();

                                    $email = $order->getUser()->getEmail();

                                    $body="Buna Ziua<br/> Plata dumneavoastra pe Mobilpay.ro a fost finalizata cu success. <br/><br/> Va multumim,<br/> <b>Helpie</b>";
                                    \NeoMail::genericMail($body, $subject, $email);
                                }
                                break;
                            case 'confirmed_pending': {
                                    #cand action este confirmed_pending inseamna ca tranzactia este in curs de verificare antifrauda. Nu facem livrare/expediere. In urma trecerii de aceasta verificare se va primi o noua notificare pentru o actiune de confirmare sau anulare.
                                    $this->OrdersModel->setOrderPaymentStatus(App_constants::$PAYMENT_STATUS_PENDING, $order);
                                }
                                break;
                            case 'paid_pending': {
                                    $this->OrdersModel->setOrderPaymentStatus($objPmReq->objPmNotify->action, $order);
                                    #cand action este paid_pending inseamna ca tranzactia este in curs de verificare. Nu facem livrare/expediere. In urma trecerii de aceasta verificare se va primi o noua notificare pentru o actiune de confirmare sau anulare.
                                    $errorMessage = $objPmReq->objPmNotify->getCrc();
                                }
                                break;
                            case 'paid': {
                                    $this->OrdersModel->setOrderPaymentStatus($objPmReq->objPmNotify->action, $order);
                                    #cand action este paid inseamna ca tranzactia este in curs de procesare. Nu facem livrare/expediere. In urma trecerii de aceasta procesare se va primi o noua notificare pentru o actiune de confirmare sau anulare.
                                    $errorMessage = $objPmReq->objPmNotify->getCrc();
                                }
                                break;
                            case 'canceled': {
                                    $this->OrdersModel->setOrderPaymentStatus($objPmReq->objPmNotify->action, $order);
                                    #cand action este canceled inseamna ca tranzactia este anulata. Nu facem livrare/expediere.
                                    $errorMessage = $objPmReq->objPmNotify->getCrc();
                                }
                                break;
                            case 'credit': {
                                    #cand action este credit inseamna ca banii sunt returnati posesorului de card. Daca s-a facut deja livrare, aceasta trebuie oprita sau facut un reverse. 
                                    $errorMessage = $objPmReq->objPmNotify->getCrc();
                                    $this->OrdersModel->setOrderPaymentStatus(App_constants::$PAYMENT_STATUS_CANCELED, $order);
                                }
                                break;
                            default:
                                $errorType = \Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;
                                $errorCode = \Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_ACTION;
                                $errorMessage = 'mobilpay_refference_action paramaters is invalid';
                                break;
                        }
                } catch (Exception $e) {


                    $errorType = \Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_TEMPORARY;
                    $errorCode = $e->getCode();
                    $errorMessage = $e->getMessage();
                }
            } else {
                $errorType = \Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;
                $errorCode = \Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_POST_PARAMETERS;
                $errorMessage = 'mobilpay.ro posted invalid parameters';
            }
        } else {
            $errorType = \Mobilpay_Payment_Request_Abstract::CONFIRM_ERROR_TYPE_PERMANENT;
            $errorCode = \Mobilpay_Payment_Request_Abstract::ERROR_CONFIRM_INVALID_POST_METHOD;
            $errorMessage = 'invalid request metod for payment confirmation';
        }

        header('Content-type: application/xml');
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        if (isset($errorCode) && isset($errorMessage) && $errorCode == 0) {
            echo "<crc>{$errorMessage}</crc>";
        } else {
            if (!isset($errorMessage))
                $errorMessage = "";
            echo "<crc error_type=\"{$errorType}\" error_code=\"{$errorCode}\">{$errorMessage}</crc>";
        }
    }

    private function informOwner(Entity\Order $order) {
        ob_start();
        require_once("mailMessages/informOwner.php");
        $body = ob_get_clean();
        $subject = "A fost plasata comanda " . $order->getOrderNumber() . ' Sa curga banii !';
        NeoMail::getInstance()->genericMail($body, $subject, $email);
    }

    private function validatePaymentProcess($post, BusinessLogic\Models\Entities\NeoCart $cart) {

        $hasErrors = false;
        $cartItems = $cart->getCartItems();
        if (!$cartItems) {
            header('Location: ' . base_url());
            exit();
        }

        foreach ($cartItems as $cartItem) {
            $item = $cartItem->getItem();
            for ($i = 0; $i < $cartItem->getQuantity(); $i++) {
                if ($cartItem->getIs_gift()) {
                    if (strlen($post['name_' . $cartItem->getId()][$i]) < 2 || !filter_var($post['email_' . $cartItem->getId()][$i], FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "Introduceți  corect datele prietenului !";
                        $hasErrors = true;
                        break 2;
                    }
                } else
                if (strlen($post['name_' . $cartItem->getId()][$i]) < 2) {
                    $errors[] = "Completati numele beneficiarilor!";
                    $hasErrors = true;
                    break 2;
                }
            }
        }

        if (!isset($_POST['payment_method']))
            $errors[] = "Alegeti metoda de plata";


        if ($hasErrors)
            return $errors;
        else
            return false;
    }

}

<?php

class Order extends MY_Controller {

    private $aPost = array();
    private $OrderModel;

    function __construct() {
        parent::__construct();
        copyPost($this->aPost);
        $this->OrderModel=new \BusinessLogic\Models\OrderModel();
    }

    public function getOrdersGrid() {
        $data = $this->OrderModel->getOrdersGrid($this->aPost);
        echo json_encode($data);
        exit();
    }

   public function confirmOrder(){

       $order=$this->OrderModel->confirmOrder($this->aPost);
       if($order->getOrder_type()=="alimentare")
           $msg=" Comanda a fost confirmata si contul alimentat cu ".$order->getTotal()." lei";
       else
           $msg="Comanda a fost confirmata si pachetul a fost activat.";
       
       $this->showExtjsMessage("succes",$msg);
       
   }
   public function cancelOrder(){

       $status=$this->OrderModel->cancelorder($this->aPost);
       $this->showExtjsMessage("succes","Comanda a fost anulata cu succes.");
       
   }
   
   public function newOrder(){
        $this->OrderModel->newOrder($this->aPost);
        $this->showExtjsMessage("success","Comanda a fost confirmata si pachetul a fost activat.");
   }

   
   public function newTransaction(){
       $this->OrderModel->newTransaction($this->auth->getUserDetails(),$this->aPost);
       $this->showExtjsMessage("success","Tranzactia a fost adaugata cu succes");
       
   }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */

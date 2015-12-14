<?php

namespace BusinessLogic\Models;

use Doctrine\ORM\EntityManager;
use BusinessLogic\Models\Entities as Entities;

class OrderModel extends AbstractModel {

    private $CI;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
    }

    public function getOrdersGrid($aPost) {

        $aColumnMapping = array(
            array("table" => false, "col" => "CONCAT(users.lastname, ' ',users.firstname)", "ref" => "lastname")
        );


        $dql = $this->em->getConnection()->createQueryBuilder();
        $dql->select("o.*,users.lastname, users.firstname,pachet.name as nume_pachet")
                ->from("orders", "o")
                ->join("o", "users", "users", "o.id_user=users.id_user")
                ->join("o", "orders_items", "oItems", "o.id_order=oItems.id_order")
                ->join("oItems", "pachet", "pachet", "oItems.id_pachet=pachet.id_pachet");

        if (isset($aPost['id_user'])) {
            $dql->where("o.id_user=" . $aPost['id_user']);
        }

        if (isset($aPost['filter'])) {
            $filters = json_decode($aPost['filter']);
            foreach ($filters as $key => $filter) {
                if ($filter->field == "payment_status") {
                    switch (strtolower($filter->value)) {
                        case "pending": {
                                $filter->value = \App_constants::$PAYMENT_STATUS_PENDING;
                            }break;
                        case "confirmat": {
                                $filter->value = \App_constants::$PAYMENT_STATUS_CONFIRMED;
                            }break;
                        case "anulat": {
                                $filter->value = \App_constants::$PAYMENT_STATUS_CANCELED;
                            }break;
                    }
                    $filters[$key] = $filter;
                }
            }
            $aPost['filter'] = json_encode($filters);
        }

        $filters = $this->getGridFilterParams($aPost);


        $this->gridFiltersExt($dql, $filters, $aColumnMapping);


        $result = $dql->execute()->fetchAll();


        $totalCount = $this->getFoundRows();
        $data = array(
            'totalCount' => $totalCount,
            'data' => $result
        );

        return $data;
    }

    /**
     * 
     * @param type $id_order
     * @return Entities\Order
     */
    public function getOrderByPk($id_order) {
        return $this->em->find("Entities:Order", $id_order);
    }

    /**
     * 
     * @param type $id_order
     * @return Entities\Order
     */
    public function getOrderByCode($code) {
        $rep = $this->em->getRepository("Entities:Order");
        $orders = $rep->findBy(array("order_number" => $code));
        if (!$orders)
            return false;
        else
            return $orders[0];
    }

    /**
     * 
     * @param type $aPost
     * @return Entities\Order
     */
    public function confirmOrder($aPost) {
        $order = $this->getOrderByPk($aPost['id_order']);
        if ($order->getOrder_type() == "alimentare") {
            $this->createAlimentareTransaction($order);
        }

        $order->setPayment_status(\App_constants::$PAYMENT_STATUS_CONFIRMED);
        $order->setOrderStatus(\App_constants::$ORDER_STATUS_CONFIRMED);
        $this->em->persist($order);
        $this->em->flush();
        return $order;
    }

    public function setOrderPaymentStatus($status, $order) {
        switch ($status) {
            case \App_constants::$PAYMENT_STATUS_CONFIRMED: {
                    $this->confirmOrder(array("id_order" => $order->getId_order()));
                }break;
            default: {
                    $order->setPayment_status($status);
                    $order->setOrderStatus($order);
                    $this->em->persist($order);
                    $this->em->flush();
                }break;
        }
    }

    private function createAlimentareTransaction(Entities\Order $order) {
        $transaction = new Entities\Transaction();
        $client = $order->getUser();
        $transaction->setClient($client);

        $admin = $this->em->createQueryBuilder()
                        ->select("u")
                        ->from("Entities:User", "u")
                        ->join("u.roluri", 'roluri')
                        ->where("roluri.rol_nume='admin'")
                        ->getQuery()->getResult();

        $transaction->setType(\App_constants::$TRANZACTIE_DEBITARE);
        $transaction->setValue($order->getTotal());
        $transaction->setDetails("Alimentare cont in valoare de: " . $transaction->getValue() . " lei");
        $transaction->setOperator($admin[0]);
        $sold_nou = $client->getSold() + $order->getTotal();
        $client->setSold($sold_nou);
        $transaction->setCurrent_sold($sold_nou);
        $this->em->persist($client);
        $this->em->persist($transaction);
        $this->em->flush();
    }

    public function cancelorder($aPost) {
        $order = $this->getOrderByPk($aPost['id_order']);
        $order->setPayment_status(\App_constants::$PAYMENT_STATUS_CANCELED);
        $order->setOrderStatus(\App_constants::$ORDER_STATUS_CANCELED);
        $this->em->persist($order);
        $this->em->flush();
    }

    //o creaza aadminul
    public function newOrder($aPost) {
        $order = new Entities\Order();

        $order->setUser($this->em->find("Entities:User", $aPost['id_client']));
        $order->setOrderNumber($this->generateOrderNumber());
        $order->setPayment_status(\App_constants::$PAYMENT_STATUS_CONFIRMED);
        $order->setPayment_method($aPost['payment_method']);
        $order->setOrderStatus(\App_constants::$ORDER_STATUS_CONFIRMED);

        $pachet = $this->em->find("Entities:Pachet", $aPost['id_pachet']);

        $orderItem = new Entities\OrderItem();
        $orderItem->setItem($pachet);
        $expires = date("d-m-Y", strtotime(date("Y-m-d") . " +1 month"));
        $orderItem->setExpires($expires);
        $order->addOrderItem($orderItem);
        $order->setTotal($pachet->getPrice());
        $orderItem->setStatus("F");
        $orderItem->setTotal($pachet->getPrice());
        $orderItem->setQuantity(1);

        $this->em->persist($orderItem);
        $this->em->persist($order);
        $this->em->flush();
    }

    public function buyPachet($aPost) {

        $order = new Entities\Order();

        $order->setUser($this->em->find("Entities:User", $aPost['id_client']));
        $order->setOrderNumber($this->generateOrderNumber());
        $order->setPayment_status(\App_constants::$PAYMENT_STATUS_PENDING);
        $order->setPayment_method($aPost['payment_method']);
        $order->setOrderStatus(\App_constants::$ORDER_STATUS_PENDING);

        $pachet = $this->em->find("Entities:Pachet", $aPost['id_pachet']);

        $orderItem = new Entities\OrderItem();
        $orderItem->setItem($pachet);
        $order->addOrderItem($orderItem);
        $order->setTotal($pachet->getPrice());
        $orderItem->setStatus(\App_constants::$PAYMENT_STATUS_PENDING);
        $orderItem->setTotal($pachet->getPrice());
        $expires = date("d-m-Y", strtotime(date("Y-m-d") . " +1 month"));
        $orderItem->setExpires(new \DateTime($expires));
        $orderItem->setQuantity(1);

        $this->em->persist($orderItem);
        $this->em->persist($order);
        $this->em->flush();

        $title = "Solicitare cumparare pachet Helpie";
        $body = "Utilizatorul " . $order->getUser()->getFirstname() . " " . $order->getUser()->getLastname() . " a cumparat un pachet Helpie: ".$pachet->getName();
        $body.="Metoda de plata aleasa: " . $order->getPayment_method();
        $body.="</br></br><b>Mesaj automat Helpie</b> ";

        \NeoMail::genericMail($body, $title, \App_constants::$OFFICE_EMAIl);


        return $order;
    }

    public function alimentareCont($aPost) {

        $order = new Entities\Order();

        $order->setUser($this->em->find("Entities:User", $aPost['id_client']));
        $order->setOrderNumber($this->generateOrderNumber());
        $order->setPayment_status(\App_constants::$PAYMENT_STATUS_PENDING);
        $order->setPayment_method($aPost['payment_method']);
        $order->setOrderStatus(\App_constants::$ORDER_STATUS_PENDING);

        $order->setOrder_type("alimentare");

        //am crete in BD un produs dummy pentru a ne folosi de functia e cumparare produse
        $produsRep = $this->em->getRepository("Entities:Pachet");
        $produseAlimentare = $produsRep->findBy(array("name" => "Alimentare cont"));
        if (!$produseAlimentare) {
            exit("<h1>Ooops eroare. Contactati administratorul helpie   </h1>");
        }


        $orderItem = new Entities\OrderItem();
        $orderItem->setItem($produseAlimentare[0]);
        $order->addOrderItem($orderItem);
        $order->setTotal($aPost['value']);
        $orderItem->setStatus(\App_constants::$PAYMENT_STATUS_PENDING);
        $orderItem->setTotal($aPost['value']);
        $orderItem->setQuantity(1);
        $orderItem->setExpires(new \DateTime("now"));

        $this->em->persist($orderItem);
        $this->em->persist($order);
        $this->em->flush();


        $title = "Solicitare alimentare cont Helpie";
        $body = "Utilizatorul " . $order->getUser()->getFirstname() . " " . $order->getUser()->getLastname() . " a solicita o alimentare a contului sau cu suma de:" . $order->getTotal() . " lei <br/><br/>";
        $body.="Metoda de plata aleasa: " . $order->getPayment_method();
        $body.="</br></br><b>Mesaj automat Helpie</b> ";

        \NeoMail::genericMail($body, $title, \App_constants::$OFFICE_EMAIl);


        return $order;
    }

    private function generateOrderNumber() {
        $nextOrderId = $this->getNextId("orders", "id_order");
        $code = strtoupper(\App_constants::$OPCODE . $nextOrderId . 'V' . substr(uniqid(), -4));
        return $code;
    }

    public function newTransaction($operator, $aPost) {


        $transaction = new Entities\Transaction();
        $transaction->postHydrate($aPost);
        //setam partenerul
        if ($aPost['id_partener']) {
            $partener = $this->em->find("Entities:Partener", $aPost['id_partener']);
            $transaction->setPartener($partener);
        }
        /* @var $client Entities\User */

        $client = $this->em->find("Entities:User", $aPost['id_client']);
        $transaction->setClient($client);

        $transaction->setOperator($operator);


        switch ($aPost['type']) {
            //debit
            case 1: {
                    $sold_nou = $client->getSold() + $aPost['value'];
                    $client->setSold($sold_nou);
                    $transaction->setCurrent_sold($sold_nou);
                }break;
            case 2: {
                    $sold_nou = $client->getSold() - $aPost['value'];
                    $client->setSold($sold_nou);
                    $transaction->setCurrent_sold($sold_nou);
                }break;
        }

        $this->em->persist($client);
        $this->em->persist($transaction);
        $this->em->flush();
    }

    /**
     * 
     * @param type $user
     * @param type $from
     * @param type $to
     * @return Entities\Transaction
     */
    public function getTransactions($user, $from, $to) {


        try {
            $qb = $this->em->createQueryBuilder();
            $qb->select("t")
                    ->from("Entities:Transaction", "t")
                    ->join("t.client", "client")
                    ->where("client.id_user=:client")
                    ->setParameter(":client", $user->getId_user());

            if ($from) {
                $qb->andWhere("t.stamp>=:from")
                        ->setParameter("from", date("Y-m-d 00:00:00", strtotime($from)));
            }

            if ($to) {

                $qb->andWhere("t.stamp<=:to")
                        ->setParameter("to", date("Y-m-d 23:59:59", strtotime($to)));
            }
            $qb->orderBy("t.stamp", "asc");


            $result = $qb->getQuery()
                    ->getResult();

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getSumaFundatie() {
        $total_fundatie = 0;
        $ordersRep = $this->em->getRepository("Entities:Order");
        $orders = $ordersRep->findBy(array("payment_status" => \App_constants::$PAYMENT_STATUS_CONFIRMED, "order_type" => "pachet"));
        /* @var $order Entities\Order */
        if ($orders) {
            foreach ($orders as $order) {
                //        switch($order->)
                $items = $order->getItems();
                switch ($items[0]->getPachet()->getId_pachet()) {
                    case 1: {
                            $total_fundatie+=5;
                        }break;
                    case 2: {
                            $total_fundatie+=8;
                        }break;
                    case 3: {
                            $total_fundatie+=10;
                        }break;
                }
            }
        }

        return ($total_fundatie > 540 ? $total_fundatie : 540);
    }

}

?>

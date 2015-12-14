<?php

/**
 * Description of neocart_model
 * @author Bardas Catalin
 * date: sept , 2013
 */

namespace BusinessLogic\Models;

use Doctrine\ORM\EntityManager;
use NeoMvc\Models\Entity as Entity;

class NeoCartModel extends \NeoMvc\Models\Model {

    function __construct() {
        $this->em = $this->getConnection();
    }

    public function addToCart($_REQUEST, Entity\NeoCart $cart) {

        $cartItem = new Entity\CartItem();
        //get item
        $item = $this->em->find("Entity:Item", $_REQUEST['id_item']);
        $cartItem->setItem($item);

        $cartItem->setQuantity($_REQUEST['quantity']);
        if (isset($_REQUEST['size']))
            $cartItem->setSize($_REQUEST['size']);

        //setam unique hash. Acest hash este generat de atributele ce fac cartitem-ul unic=> id_cart,id_size,id_item
        $cartItem->setId_cart($cart->getId_cart());

        if (isset($_REQUEST['is_gift'])) {
            $cartItem->setIs_gift(1);
            $cartItem->setDetails($_REQUEST['details']);
        }

        if (isset($_REQUEST['variant']) && $_REQUEST['variant'])
            $cartItem->setProductVariant($_REQUEST['variant']);


        $cartItem->setUniqueHash();

        //Verificam daca produsul cu acelasu hash a mai fost adaugat in cos. Daca da facem update la cantitate
        if ($this->tryUpdateQuantity($cart, $cartItem)) {
            return true;
        }
        $cart->addCartItem($cartItem);
        $this->em->persist($cart);
        $this->em->flush();

        return true;
    }

    /**
     * TODO: in cazul in are se mai baga un cadou trebuie sa facem update la detaliile frietenilor
     * @param \NeoMvc\Models\Entity\NeoCart $cart
     * @param \NeoMvc\Models\Entity\CartItem $cartItem
     * @return boolea
     */
    private function tryUpdateQuantity(Entity\NeoCart $cart, Entity\CartItem $cartItem) {

        $rows = $this->em->createQuery("update Entity:CartItem c set c.quantity=c.quantity+:quantity where c.unique_hash=:hash")
                ->setParameter(":hash", $cartItem->getUnique_hash())
                ->setParameter(":quantity", $cartItem->getQuantity())
                ->execute();

        //in rows avem cate >0 => a facut updatate, teoretic daca e 1 a fost un duplicat
        if ($rows)
            return true;
        else
            return false;
    }

    public function createCart(Entity\NeoCart $cart) {
        $this->em->persist($cart);
        $this->em->flush();
        return true;
    }

    /**
     * Intoarce shopping cartul in functie de cookie. Daca nu exista il creeaza
     * @param type $hash
     * @return \NeoMvc\Models\Entity\NeoCart
     */
    public function getCart($hash) {

        $cartRep = $this->em->getRepository("Entity:NeoCart");
        $cart = $cartRep->findBy(array("hash" => $hash));

        if (isset($cart[0]))
            return $cart[0];
        else {
            //trebuie sa cream una
            $cart = new Entity\NeoCart();
            $cart->setHash($hash);
            $this->em->persist($cart);
            $this->em->flush($cart);
            return $cart;
        }
    }

    /**
     * Face update la cantitatea unui item din cart
     * @param type $_POST
     * @param type $cartHash
     * @return Boolean
     */
    public function updateQuantity($_POST) {
        $cartItem = $this->getCartItemByPk($_POST['cartItem']);
        $remove = false;

        if (isset($_POST['plus']))
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        else {
            //stergem itemul
            if ($cartItem->getQuantity() <= 1) {
                $remove = true;
            }
            $cartItem->setQuantity($cartItem->getQuantity() - 1);
        }
        if ($remove)
            $this->em->remove($cartItem);
        else
            $this->em->persist($cartItem);

        $this->em->flush();
    }

    public function deleteCartItem($id_item) {

        $dql = $this->em->createQuery("delete from Entity:CartItem item where item.id=:id_item");
        $dql->setParameter(":id_item", $id_item);
        $dql->execute();
        return true;
    }

    public function getNrItems() {

        $hash = \NeoMvc\Controllers\controller::getHash();
        if (!$hash)
            return 0;

        $dql = $this->em->createQuery("
            select sum(cartItems.quantity) as nr_items from Entity:NeoCart cart join cart.CartItems cartItems
            where cart.hash=:hash");

        $dql->setParameter(":hash", $hash);
        $r = $dql->getResult();
        if (!$r[0]['nr_items'])
            $nr_items = 0;
        else {
            $r = $r[0];
            $nr_items = $r['nr_items'];
        }
        return $nr_items;
    }

    /**
     * Intoarce un obiect cartItem
     * @param type $id
     * @return Entity\CartItem
     */
    public function getCartItemByPk($id) {
        $cartItem = $this->em->find("Entity:CartItem", $id);
        return $cartItem;
    }

    public function emptyCart() {

        $dql = $this->em->createQuery('delete Entity:NeoCart cart where cart.hash=:hash');
        $dql->setParameter(":hash", \NeoMvc\Controllers\NeoCart::getHash());
        $dql->execute();
        return true;
    }

    /**
     * 
     * @param \NeoMvc\Models\Entity\User $user
     * @param type $params
     * @return \NeoMvc\Models\Entity\Order
     */
    public function insertOrder(Entity\User $user, $params) {
        $nextOrderId = $this->getNextId("orders", "id_order");

        //luam shopping cart
        $cart = $this->getCart(\NeoMvc\Controllers\NeoCart::getHash());
        if (!$cart) {
            header('Location: ' . URL);
            exit();
        }

        $cartItems = $cart->getCartItems();
        if (!count($cartItems)) {
            //nu are nimic in cos
            header("Location: " . URL . 'cart');
            exit();
        }
        $order = new Entity\Order();

        $total = 0;
        //valentines day discount
        $valentine_discount_items = $this->getValentineDiscount($cart);

        /**
         * Generam item-urile comenzii
         */
        /* @var $cartItem Entity\CartItem */
        foreach ($cartItems as $cartItem) {
            $orderItem = new Entity\OrderItem();

            $item = $cartItem->getItem();

            /* @var $itemDetails Entity\Product */ // sau Entity\Offer
            $itemDetails = $item->getItemDetails();

            /*
             * valentines day discount
             * se da o reducere de 20 la suta la al 2 lea produs
              $discount = 0;
              if (in_array($item->getIdItem(), $valentine_discount_items)) {
              $discount = round((20 * $itemDetails->getSale_price()) / 100,2);
              }
             * 
             */

            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setTotal($cartItem->getTotal($itemDetails->getSale_price()) - $discount);
            //end valentines day discount

            $orderItem->setItem($item);
            if ($cart->hasOnlyOffers())
                $orderItem->setStatus("F");
            else
                $orderItem->setStatus("W");
            //daca itemul are varianta o adaugam
            if ($cartItem->getProductVariant())
                $orderItem->setProductVariant($cartItem->getProductVariant());

            $total+=$orderItem->getTotal();

            /**
             * Daca Itemul este oferta, atunci trebuie sa adaugam vouchere
             * Observatie: item-ul poate fi facut si cadou atunci mai avem in post pe langa nume si emailul prietenului
             */
            if ($item->getItem_type() == "offer") {
                for ($i = 0; $i < $orderItem->getQuantity(); $i++) {
                    $voucher = new Entity\OrderVoucher();
                    $voucher->setRecipientName($_POST['name_' . $cartItem->getId()][$i]);
                    if ($cartItem->getIs_gift()) {
                        $voucher->setRecipientEmail($_POST['email_' . $cartItem->getId()][$i]);
                        $voucher->setIs_gift(1);
                    }
                    $code = "ORV" . $nextOrderId . 'V' . substr(uniqid(), -4);
                    $voucher->setCode($code);
                    $orderItem->addVoucher($voucher);
                }
            }

            $order->addOrderItem($orderItem);
        }

        //generam 4 cifre pentru orderCode
        $date = new \DateTime();
        $stamp = $date->getTimestamp();
        $last_four = substr($stamp, -4);

        $order->setPayment_method($params['payment_method']);

        //rate mobilpay
        if (isset($_POST['installments']))
            $order->setInstallments($_POST['installments']);
        
        //rate euplatesc
        if (isset($_POST['installments_euplatesc']))
            $order->setInstallments($_POST['installments_euplatesc']);

        if (isset($_POST['christmas_shipping']))
            $order->setChristmas_shipping(1);

        $order->setShipping_cost($this->getShippingCost($params, $total));
        $order->setTotal($total + $order->getShipping_cost());
        $order->setUser($user);
        $orderCode = "ORO" . $nextOrderId . 'O' . $last_four;
        $order->setOrderNumber($orderCode);

        //daca comanda contine doar cupoane este confirmata automat
        if ($cart->hasOnlyOffers()) {
            $order->setPayment_status("F"); // 
            $order->setOrderStatus("F");
            $order->setMail_notification(1);
        }

        /**
         * Adresa de livrare/facturare. Doar atunci cand comanda contine si produse
         * Sunt 2 situatii:
         *  1. Userul decide ca vrea o adresa de livrare noua si atunci id-ul adresei de livrare este new.
         *  2. Userul alege o adresa de livrare deja existenta
         */
        if (!$cart->hasOnlyOffers()) {
            if ($_POST['shipping_address_id'] == "new") {
                $shippingAddress = new Entity\ShippingAddress();
                $shippingAddress->postHydrate($_POST);
                $cargusDistrict = $this->em->getRepository("Entity:CargusDistrict")->findBy(array("district_code" => $shippingAddress->getShipping_district_code()));
                if (isset($cargusDistrict[0]))
                    $shippingAddress->setShipping_district($cargusDistrict[0]->getDistrict());
                else
                    $shippingAddress->setShipping_district($_POST['shipping_district_code']);
                $user->setShippingAddresses($shippingAddress);
            }
            else {
                $shippingAddress = $this->em->find("Entity:ShippingAddress", $_POST['shipping_address_id']);
            }

            if (!$shippingAddress) {
                exit("Eroare: 1:22. Adresa de livrare aleasă nu există");
            }

            /* Daca datele omului nu sunt completate le luam din adresa de livrare */
            if (!$user->getNume())
                $user->setNume($shippingAddress->getShipping_name());

            if (!$user->getPhone())
                $user->setPhone($shippingAddress->getShipping_phone());

            /**
             * Adresa de facturare
             */
            $billingAddress = new Entity\BillingAddress();
            //factura persoana juridica

            if (isset($_POST['new_billing_address'])) {
                $billingAddress->postHydrate($_POST);
                $billingAddress->setBilling_type("legal");
            }
            //factura persoana fizica, datele de pe pe factura sunt aceleasi ca cele din adresa de livrare
            else {
                $billingAddress->setIndividualDetails($shippingAddress);
                $billingAddress->setBilling_type("individual");
            }

            //adaugam noua adresa de facturare la user
            $user->setBillingAddresses($billingAddress);

            $order->setShippingAddress($shippingAddress);
            $order->setBillingAddress($billingAddress);

            //setam numele si telefonul userului din adresa de livrare, daca nu sunt setate
            if (!$user->getNume())
                $user->getNume($shippingAddress->getShipping_name());

            if (!$user->getPhone())
                $user->getNume($shippingAddress->getShipping_phone());
        } //end adresa de facturare/livrare

        $this->em->persist($order);
        $this->em->persist($user);
        $this->em->flush();
       // $this->emptyCart();
        return $order;
    }

    public function getShippingCost($params, $total) {

        //de valetine transportul este moca

        $cDate = date("Y-m-d");
        if ($cDate >= '2014-02-14' && $cDate <= '2014-02-28')
            return 0;

        //daca suma dapaseste 250 lei transprot grauit
        if ($total >= \NeoMvc\Controllers\controller::TRANSPORT_GRATIS)
            return 0;
        $tax = 0;
        switch ($params['payment_method']) {
            case "card": {
                    $tax = 12;
                }break;
            case "card_euplatesc": {
                    $tax = 12;
                }break;
            case "op": {
                    $tax = 12;
                }break;
            case "ramburs": {
                    $tax = 17;
                }break;
            case "free": {
                    $tax = 0;
                }break;
            default: {
                    exit("Err:12:00 Payment method not implemented");
                }break;
        }

        if (isset($params['christmas_shipping']))
            $tax = $tax + (20 - $tax);


        return $tax;
    }

    /**
     * Genereaza fisierele pdf cu vouchere, si intoarce locatia lor pe server
     */
    public function generateVouchers(Entity\Order $order) {

        $vouchers_list = array();
        $orderItems = $order->getItems();
        foreach ($orderItems as $orderItem) {

            if ($orderItem->getItem()->getItem_type() == "offer") {
                $item = $orderItem->getItem();
                $offer = $item->getOffer();
                $company = $item->getCompany();
                $companyDetails = $company->getCompanyDetails();
                $vouchers = $orderItem->getVouchers();
                foreach ($vouchers as $voucher) {

                    $file = "application_uploads/vouchers/" . $order->getId_order() . '/' . $voucher->getId_voucher() . '.pdf';
                    ob_start();
                    require('views/popups/voucher.php');
                    $voucherHtml = ob_get_clean();
                    require_once("NeoMvc/Libs/mpdf54/mpdf.php");
                    $mpdf = new \mPDF('utf-8', array(190, 536), '', 'Arial', 2, 2, 2, 2, 2, 2);
                    $mpdf->WriteHTML(utf8_encode($voucherHtml));

                    if (!is_dir("application_uploads/vouchers/" . $order->getId_order()))
                        mkdir("application_uploads/vouchers/" . $order->getId_order(), 0777);
                    $mpdf->Output($file);
                    $vouchers_list[] = $file;
                }
            }
        }
        if (count($vouchers_list) < 1)
            return false;
        else
            return $vouchers_list;
    }

    /**
     * Acorda reducere de 20% celui de al 2 lea produs din categoriile Acesorii Telefoane/Accesori Tablete
     * @return produsele pentru care se acord reducere de 20%
     * @param \NeoMvc\Models\Entity\NeoCart $cart
     */
    public function getValentineDiscount(Entity\NeoCart $cart, $name = false) {
        return false;
        $items_in_category = array();
        $cartItems = $cart->getCartItems();
        foreach ($cartItems as $cartItem) {
            $item = $cartItem->getItem();
            if ($item->getCategory()->getId_category() == "884" || $item->getCategory()->getId_category() == "79") {
                for ($i = 0; $i < $cartItem->getQuantity(); $i++) {
                    $items_in_category[] = $item->getIdItem();
                }
            }
        }

        //scoatem din $items_in_category produsele de pe pozitiile impare
        //ex: 3,4,5,6 => 4,6
        $step = 1;
        $length = count($items_in_category);

        for ($i = 0; $i < $length; $i++) {
            if ($step % 2 != 0) {
                unset($items_in_category[$i]);
            }
            $step++;
        }
        $items = array();
        $discount = 0;
        if ($name) {
            foreach ($items_in_category as $id_item) {
                $item = $this->em->find('Entity:Item', $id_item);
                $discount+=round(($item->getItemDetails()->getSale_price() * 20) / 100, 2);
                $items[] = $item->getName();
            }
            return array(
                "items" => $items,
                "discount_total" => $discount
            );
        }
        return $items_in_category;
    }

    //intoarce suma totala de plata din cos
    public function getTotal() {
        $cart = $this->getCart(\NeoMvc\Controllers\controller::setHash());

        if (!$cart)
            return false;

        $total = 0;
        $cartItems = $cart->getCartItems();
        foreach ($cartItems as $orderItem) {
            $total+=$orderItem->getTotal($orderItem->getItem()->getProduct()->getSale_price());
        }
        return $total;
    }

}

?>

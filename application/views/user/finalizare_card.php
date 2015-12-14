<?php
/* @var $user BusinessLogic\Models\Entities\User */
/* @var $view View  */
?>

<div class='inner_small'>
    <div class="account messages">

        <?php require_once 'application/views/user/user_menu.php' ?>


        <div class='right_side'>

            <?php if (isset($notification)) echo $this->view->show_message($notification) ?>

            <div class="finalizare_op">

                <table  class="content_table user_settings" border="0" width="100%" cellpadding="0" cellspacing="0">


                    <tr id="accountForm">

                        <td style="padding-top:20px;" colspan="3" >

                            <div class="title" style='font-size: 1.5em; margin-bottom: 1em;'>

                                <?php
                                switch ($order->getPayment_status()) {
                                    case App_constants::$PAYMENT_STATUS_CONFIRMED: {
                                            echo "Comanda Finalizata";
                                        }break;
                                    case App_constants::$PAYMENT_STATUS_PENDING: {
                                            echo "Comanda in asteptare";
                                        }break;
                                    case App_constants::$PAYMENT_STATUS_CANCELED: {
                                            echo "Comanda nefinalizata";
                                        }break;
                                }
                                ?>

                            </div>

                        </td>

                    </tr>

                    <tr>

                        <td style='vertical-align: top;' colspan="3">

                            <div class="payment_information">

                                <?php
                                switch ($order->getPayment_status()) {
                                    case App_constants::$PAYMENT_STATUS_CONFIRMED: {
                                            echo "Comanda dvs. cu numărul " . $order->getOrderNumber() . "s-a efectuat cu succes !<br/><br/><br/>
";
                                        }break;
                                    case App_constants::$PAYMENT_STATUS_PENDING: {
                                            echo "Comanda dvs. cu numărul " . $order->getOrderNumber() . " este in asteptare. Aceasta va fi procesata in scurt timp. !<br/><br/><br/>
";
                                        }break;
                                    case App_constants::$PAYMENT_STATUS_CANCELED: {
                                            echo "Comanda dvs. cu numărul " . $order->getOrderNumber() . " nu a fost procesata! Pentru mai multe detalii contactati echipa Helpie.<br/><br/><br/>
";
                                        }break;
                                }
                                ?>




                                Vă dorim toate cele bune,<br/>
                                E-Mail: <?php echo App_constants::$OFFICE_EMAIl ?><br/>



                                Echipa Helpie<br/>

                            </div>

                        </td>

                    </tr>

                </table>

            </div>

            <div id="clear"></div>
        </div>


        <div id="clear"></div>
    </div>
</div>



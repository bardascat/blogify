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

                        <td style="padding-top: 40px;" colspan="3" >

                            <div class="title">

                                Comandă Finalizată

                            </div>

                        </td>

                    </tr>

                    <tr>

                        <td colspan="3">

                            <div class="payment_information">

                                Comanda dvs. cu numărul <?= $order->getOrderNumber() ?>  s-a efectuat cu succes !<br/><br/>

                                Contul bancar în care trebuie să efectuați transferul: <span style='color:blue'><?php echo App_constants::$SUPPLIER_IBAN?></span><br/>

                                Banca: <span style='color:blue'><?php echo App_constants::$SUPPLIER_BANK?></span><br/>

                                Beneficiar:  <span style="color: #0011bf"><?php echo App_constants::$SUPPLIER_NAME?></span><br/><br/><br/>





                                Transferul trebuie efectuat într-un termen de 72 de ore de la finalizarea comenzii.<br/>

                                <b>Vă rugăm să puneti numărul comenzii  <?= $order->getOrderNumber() ?> în detaliile transferului.</b><br/><br/>



                                Pentru asistență vă rugăm să ne contactați (Luni-Vineri 09:00-18:00): <?php echo App_constants::$WEBSITE_PHONE?><br/><br/>



                                

                                

                                E-Mail: <?php echo App_constants::$OFFICE_EMAIl?><br/><br/>



                                O zi excelenta,<br/>

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



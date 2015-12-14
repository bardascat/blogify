<?php
/* @var $user BusinessLogic\Models\Entities\User */
/* @var $view View  */
?>

<div class='inner_small'>
    <div class="account messages">

        <?php require_once 'application/views/user/user_menu.php' ?>


        <div class='right_side'>

            <?php if (isset($notification)) echo $this->view->show_message($notification) ?>

            <div class="alimentare_cont">
                <h2><?php echo \BusinessLogic\Util\Language::output("alimenteaza_cont")?></h2>
                <div class="info">
                    <span>
                        <?php echo \BusinessLogic\Util\Language::output("alimenteaza_desc")?>
                    </span>
                </div>

                <div class="alimentareFom">
                    <form method="post" action="<?php echo base_url('neocart/process_payment') ?>">

                        <input type="hidden" name="order_type" value="alimentare"/>
                        
                        <div class="payment_method">
                            <label><?php echo \BusinessLogic\Util\Language::output("modalitate_plata")?></label>
                            <select name="payment_method">
                                <option value="CARD"><?php echo \BusinessLogic\Util\Language::output("card_online")?></option>
                                <option value="OP"><?php echo \BusinessLogic\Util\Language::output("transfer_bancar")?></option>
                            </select>
                        </div>

                        <div class="valoare_alimentare">
                            <label><?php echo \BusinessLogic\Util\Language::output("valoarea_alimentarii")?></label>
                            <input class="numbersOnly" type="text" name="value" placeholder="RON"/>
                        </div>

                        <div onclick="alimenteaza()" class="blueBtn"><?php echo \BusinessLogic\Util\Language::output("alimenteaza")?></div>
                    </form>
                </div>

            </div>

            <div id="clear"></div>
        </div>


        <div id="clear"></div>
    </div>
</div>
<script>
    function alimenteaza(){
        if(!$('.numbersOnly').val()){
            alert('Introduceti valoarea alimentarii');
            return false;
        }
        
        $('.alimentareFom form').submit();
    }
    $(document).ready(function() {

        $('.numbersOnly').keyup(function() {
            this.value = this.value.replace(/[^0-9\.]/g, '');
        });

    })
</script>
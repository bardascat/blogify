<?php
/* @var $user BusinessLogic\Models\Entities\User */
/* @var $view View  */
?>

<div class='inner_small'>
    <div class="account messages">

        <?php require_once 'application/views/user/user_menu.php' ?>


        <div class='right_side'>

            <a id="newMessageBtn" href="<?php echo base_url('account/messages') ?>">CANCEL</a>
            <?php if (isset($notification)) echo $this->view->show_message($notification) ?>

            <div class="mesaj_nou">

                <div class="mesaj">
                    <h2>Mesaj Nou</h2>
                    <form method="post" action="<?php echo base_url('account/newMessage')?>">
                        <textarea name="content"></textarea>
                        <div onclick="$('.right_side form').submit()" class="blueBtn"><?php echo \BusinessLogic\Util\Language::output("trimite")?></div>
                    </form>
                </div>
               
                <div id="clear"></div>
            </div>

            <div id="clear"></div>
        </div>


        <div id="clear"></div>
    </div>
</div>
<style>
    #sb-site{

        background-image: url('<?php echo base_url('assets/frontend/layout/login_bgg.png') ?>');
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        background-position: center 50px;
        overflow: hidden;
    }
</style>

<div class="login_page_outer">

    <div class="login_page inner_small">

        <style>
            #wrapper .login_page   td{padding-bottom: 0.3em;}
            select{
                font-size: 1.5em;
                padding: 0.2em;
                color: #999ca0;
                border:1px solid #999ca0;
            }
        </style>

        <form method="post" style="margin-top: 3em;" action="<?php echo base_url('user/register_submit') ?>">
            <input type="hidden" name="order_type" value="pachet"/>
            <table width="100%">
                <tr>
                    <td style="padding-bottom: 0.2em" colspan="2">
                        <?php if (isset($notification)) echo $this->view->show_message($notification) ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <label><?php echo \BusinessLogic\Util\Language::output("prenume") ?></label>
                        <input type="text" name="firstname" value="<?php echo set_value('firstname') ?>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label><?php echo \BusinessLogic\Util\Language::output("nume") ?></label>
                        <input type="text" name="lastname" value="<?php echo set_value('lastname') ?>"/>
                    </td>
                </tr>


                <tr>
                    <td colspan="2">
                        <label>E-mail</label>
                        <input type="text" name="email" value="<?php echo set_value('email') ?>"/>
                    </td>
                </tr>
                <tr>

                    <td colspan="2">
                        <label><?php echo \BusinessLogic\Util\Language::output("password") ?>:</label>
                        <input type="password" name="newPassword"/>
                    </td>
                </tr>


                <tr>
                    <td style="padding-top: 1.5em;">
                        <label><?php echo \BusinessLogic\Util\Language::output("alege_pachet") ?></label>
                        <select name="id_pachet">
                            <?php foreach ($pachete as $pachet) { ?>
                                <option value="<?php echo $pachet['id_pachet'] ?>"><?php echo $pachet['name'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td style="padding-top: 1.5em;">
                        <div style="float: right">
                            <label><?php echo \BusinessLogic\Util\Language::output("modalitate_plata") ?></label>
                            <select name="payment_method">
                                <option value="CARD"><?php echo \BusinessLogic\Util\Language::output("card_online") ?></option>
                                <option value="OP"><?php echo \BusinessLogic\Util\Language::output("transfer_bancar") ?></option>
                            </select>
                        </div>
                    </td>
                    <td>

                    </td>
                </tr>

                <tr>

                    <td colspan="2" style="padding-top: 3em;" >
                        <a class="blueBtn loginBtn" onclick="$('.login_page form').submit()" href="javascript:void()"><?php echo \BusinessLogic\Util\Language::output("inregistrare") ?></a>
                    </td>
                </tr>
            </table>

    </div>
</div>
<div id="content">
    <div class="inner_content">
        <div class="register">
            <div class="breadcrumbs">
                <h1>Recupereaza parola<span style="font-size: 13px; margin-left: 10px;"></span></h1>
            </div>

            <form method="post" action="<?php echo base_url('account/forgot_password_submit') ?>">
                <table>
                    <tr>
                        <td colspan="2">
                            <?php if (isset($notification)) echo $this->view->show_message($notification) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Adresa Email:</label>
                        </td>
                        <td>
                            <input type="text" name="email" value="<?php echo set_value('email') ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label>Noua parola va fi trimisa pe adresa de email</label>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 20px;" colspan="2">
                            <input id="greenButton" type="submit" value="Recupereaza"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <div id="clear"></div>
</div>
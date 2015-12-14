<?php
/* @var $user BusinessLogic\Models\Entities\User */
/* @var $view View  */
?>

<div class='inner_small'>
    <div class="account date_profil">

        <?php require_once 'application/views/user/user_menu.php' ?>

        <div class='right_side'>

            <?php if (isset($notification)) echo $this->view->show_message($notification) ?>

            <form method="post" action="<?php echo base_url('account/change_settings') ?>">
                <input type="hidden" name="id_user"/>
                <div class='data_form'>
                    <div class='row'>
                        <div class='left_row'>
                            <label><?php echo \BusinessLogic\Util\Language::output("nume") ?></label>
                            <input type='text' name='lastname'/>
                        </div>
                        <div class='right_row'>
                            <label><?php echo \BusinessLogic\Util\Language::output("prenume") ?></label>
                            <input type='text' name='firstname'/>
                        </div>
                    </div>
                    <div class='row'>
                        <label>E-mail</label>
                        <input type='text' name='email'/>
                    </div>
                    <div class='row'>
                        <label>Serie Buletin</label>
                        <input type='text' name='serie_buletin'/>
                    </div>
                    <div class='row'>
                        <label>Adresa</label>
                        <input type='text' name='address'/>
                    </div>

                    <div class='row'>
                            <label><?php echo \BusinessLogic\Util\Language::output("telefon") ?></label>
                            <input type='text' name='phone'/>
                        
                    </div>
                    
                    <div class="row">
                        <!--<div class='right_row'>-->
                            <div class='notificari'>
                                <label style="display: inline-block;"><?php echo \BusinessLogic\Util\Language::output("tip_notificari") ?></label>
                                <div class='list' style="display: inline-block;margin-left: 35px;">
                                    <?php
                                    $notifications = array();
                                    if ($user->getUserNotifications())
                                        foreach ($user->getUserNotifications() as $not) {
                                            $notifications[] = $not->getType();
                                        }
                                    ?>
                                    <div class='notificare'>
                                        <input id="ck-email" type='checkbox' <?php if (in_array(App_constants::$NOTIFICATION_EMAIL, $notifications)) echo "checked"; ?> value='<?php echo App_constants::$NOTIFICATION_EMAIL ?>' name='notification[]'/>
                                        <label for="ck-email">E-mail</label>
                                    </div>
                                    <div class='notificare'>
                                        <input id="ck-sms" type='checkbox'  <?php if (in_array(App_constants::$NOTIFICATION_SMS, $notifications)) echo "checked"; ?> value='<?php echo App_constants::$NOTIFICATION_SMS ?>' name='notification[]'/>
                                        <label for="ck-sms">SMS</label>
                                    </div>
                                    <div class='notificare'>                                        
                                        <input id="ck-tel" type='checkbox'  <?php if (in_array(App_constants::$NOTIFICATION_PHONE, $notifications)) echo "checked"; ?> value='<?php echo App_constants::$NOTIFICATION_PHONE ?>' name='notification[]'/>
                                        <label for="ck-tel"><?php echo \BusinessLogic\Util\Language::output("telefon") ?></label>
                                    </div>
                                    <div class='notificare'>
                                        <input id="ck-cnthlp" type='checkbox'  <?php if (in_array(App_constants::$CONT_HELPIE, $notifications)) echo "checked"; ?> value='<?php echo App_constants::$CONT_HELPIE ?>' name='notification[]'/>
                                        <label for="ck-cnthlp"><?php echo \BusinessLogic\Util\Language::output("cont_helpie") ?></label>

                                    </div>
                                </div>
                            </div>
                        <!--</div>-->
                    </div>
                    
                    <div style='position: absolute;bottom : 20px;right : 50px;' class='blackButton' onclick='$(".date_profil form").submit()'><?php echo \BusinessLogic\Util\Language::output("salveaza") ?></div>

                    <div id='clear'></div>
                </div>
            </form>
            <div class='photo'>
                <div class="userIcon" style='position:static; margin: auto; float: none;'>

                    <form style="visibility:0; height:0em; width:0em; border:0px;" id="hdn_img_ajax_upload_form_pen" method="post"  enctype="multipart/form-data" action='<?php echo base_url('account/setImageProfilePicture') ?>' target="upload_to">
                        <input type="file" name="file" style="visibility: hidden;" class="upload_image" />
                    </form>

                    <iframe style="visibility:0; height:0em; width:0em; border:0px;" name='upload_to' class="hiddenIframe"></iframe>	

                    <div class='user_image' style="background-image: url('<?php echo $this->view->getUser()->getUserImage() ?>'); margin:auto; float:none;" class="user_image"></div>
                    <div class='blackButton' onclick='$(".upload_image").click()'><?php echo \BusinessLogic\Util\Language::output("alege_foto") ?></div>
                    <div id='clear'></div>
                </div>
            </div>

            <div id="clear"></div>
        </div>
        <div id="clear"></div>
    </div>
    
    
</div>

    <?php $this->load->view("util/simple-and-easy-band");?>

<script>
    $(document).ready(function() {
        $(".upload_image").change(
                function() {
                    alert('Asteptati va rog...');
                    $("#hdn_img_ajax_upload_form_pen").submit();
                });

        $('iframe[name=upload_to]').load(
                function() {
                    var result = $(this).contents().text();
                    if(result){
                      window.location = window.location.href;
                    }
                }
        );

    })
</script>
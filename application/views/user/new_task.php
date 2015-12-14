<?php
/* @var $user BusinessLogic\Models\Entities\User */
/* @var $view View  */
?>

<div class='inner_small'>
    <div class="account solicitare_serviciu">

        <?php require_once 'application/views/user/user_menu.php' ?>

        <div class='right_side'>

            <div class='content'>

                <form method="post" enctype="multipart/form-data" action="<?php echo base_url('account/solicitareTask') ?>"
                      <div class='pachet_ales'>
                        <table width='100%' border='0'>
                            <tr>
                                <td width="20%" class="pachet">
                                    <?php
                                    switch ($user->getActivePachet()->getId_pachet()) {
                                        case 1: {
                                                $image = base_url('assets/frontend/layout-reloaded/silver-package-logo.png');
                                            }break;
                                        case 2: {
                                                $image = base_url('assets/frontend/layout-reloaded/gold-package-logo.png');
                                            }break;
                                        case 3: {
                                                $image = base_url('assets/frontend/layout-reloaded/platinum-package-logo.png');
                                            }break;
                                    }
                                    ?>   
                                    <img style="width:100%" src="<?php echo $image ?>"/>  
                                </td>
                                <td width="80%" class="pachetDetails" style="vertical-align: top;">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="pachetNume" style="position: relative;">
                                                <h3>
                                                    <?php
                                                    if (\BusinessLogic\Util\Language::getLanguage() == "ro")
                                                        echo $user->getActivePachet()->getName();
                                                    else
                                                        echo $user->getActivePachet()->getName_en();
                                                    ?>
                                                </h3>
                                                <div style="position: absolute;right : 0 ; top : 40px;">
                                                    <a style="" href="<?php echo base_url('pachete') ?>">
                                                        <?php echo \BusinessLogic\Util\Language::output("vezi_pachete_btn") ?>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="pachetPrice">
                                                <h3>
                                                    <?php echo $user->getActivePachet()->getPrice() ?> lei
                                                </h3>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <h2 class="chooseServiceH2">  <?php echo \BusinessLogic\Util\Language::output("alege_serviciul") ?></h2>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>

                        <?php
                        $servicii = $user->getActivePachet()->getServicii();
                        if (count($servicii) < 9)
                            $col1 = count($servicii);
                        else {
                            $col1 = 9;
                        }
                        ?>
                        <div class="lista_servicii">
                            <div class="servicii_left">
                                <?php for ($i = 0; $i < $col1; $i++) { ?>
                                    <div class="serviciu serviciuNume">
                                        <!--<div class="checkbox">-->
                                            <input id="serv_<?php echo $i ?>" type="radio" name="id_serviciu" value=" <?php echo $servicii[$i]->getId_serviciu() ?>">
                                        <!--</div>-->
                                        <!--<div class="serviciuNume">-->
                                            <label for="serv_<?php echo $i ?>">
                                            <?php
                                            if (\BusinessLogic\Util\Language::getLanguage() == "ro")
                                                echo $servicii[$i]->getName();
                                            else
                                                echo $servicii[$i]->getName_en();
                                            ?>
                                            </label>
                                        <!--</div>-->
                                    </div>
                                <?php } ?>

                            </div>
                            <div class="servicii_right">
                                <?php for ($i = $col1; $i < count($user->getActivePachet()->getServicii()); $i++) { ?>
                                    <div class="serviciu serviciuNume">
                                        <!--<div class="checkbox">-->
                                            <input id="serv_<?php echo $i;?>" type="radio" name="id_serviciu" value=" <?php echo $servicii[$i]->getId_serviciu() ?>">
                                        <!--</div>-->
                                        <!--<div class="serviciuNume">-->
                                            <label for="serv_<?php echo $i;?>">
                                            <?php echo $servicii[$i]->getName() ?>
                                            </label>
                                        <!--</div>-->
                                    </div>
                                <?php } ?>

                            </div>
                            <div id="clear"></div>
                        </div>


                          <table width="100%">
                              <tr>
                                  <td width="50%">
                                        <div class="optiuni">
                                            <div class="alege_data">
                                                <label><?php echo \BusinessLogic\Util\Language::output("alege_data") ?></label>
                                                <input style="width:60%;" placeholder="data finalizare" type="text" name="date"/>
                                            </div>
                                        </div>
                                  </td>
                                  <td>
                                      
                                      <div class="upload_image">
                                        
                                        <div class="image">
                                            <input type="file" name="file"/>
                                        </div>
                                    </div>
                                      
                                  </td>
                              </tr>
                          </table>
                          
<!--                        


                        <div class="upload_image">
                            <label style="float: left">
                                <?php echo \BusinessLogic\Util\Language::output("detaliile_taskului") ?>
                            </label>
                            <div class="image">
                                <input type="file" name="file"/>
                            </div>
                        </div>-->

                        <div class="observatii">
                            <label><?php echo \BusinessLogic\Util\Language::output("observatii") ?></label>
                            <textarea name="observatii"></textarea>
                        </div>

                        <div style="cursor: pointer;" onclick="$('.solicitare_serviciu form').submit()" class="blueBtn"><?php echo \BusinessLogic\Util\Language::output("solicita") ?></div>

                    </div>
                </form>

            </div>

            <div id="clear"></div>
        </div>


        <div id="clear"></div>
    </div>
</div>

<script>
    $(function () {
        $(".alege_data input").datepicker({dateFormat: 'dd-mm-yy'});
    });

</script>
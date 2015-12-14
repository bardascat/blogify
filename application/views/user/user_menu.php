
<div class='left_side' style="position: relative;">

    <div style="position: absolute; width:50em; top:-2em;" class="pachet_status">
        <?php if (!$user->getActivePachet()->getIsEnabled()) { ?>
        <!--<div><?php echo \BusinessLogic\Util\Language::output("pachet_inactiv")?></div>-->
        <?php } 
         else if ($user->getActivePachet()->getIsExpired()) { ?>
        <div><?php echo \BusinessLogic\Util\Language::output("pachet_expirat")?><?php echo $user->getActivePachet()->getExpireDate() ?></div>
        <?php } ?>
    </div>
    <ul>
        <li class="<?php echo $arrow == "messages" ? "selected" : "";?>">
            <div class='inner_li'>
                <?php if ($arrow == "messages"): ?>
                    <div class='arrow'><img src='<?php echo base_url('assets/frontend/layout/user_arrow.png') ?>'/></div>
                <?php endif; ?>
                    <a href='<?php echo base_url('account/messages') ?>'><?php echo \BusinessLogic\Util\Language::output("mesaje")?>
                <?php if(isset($messages) && $messages) {?> <b>(<?php echo $messages?>)</b> <?php } ?>
                </a>
            </div>
        </li>
        <?php if($user->getActivePachet()->getIsEnabled() && !$user->getActivePachet()->getIsExpired()) {?>
        <li class="<?php echo $arrow == "newtask" ? "selected" : ""; ?>">
            <div class='inner_li'>
                <?php if ($arrow == "newtask"): ?>
                    <div class='arrow'><img src='<?php echo base_url('assets/frontend/layout/user_arrow.png') ?>'/></div>
                <?php endif; ?>
                <a href='<?php echo base_url('account/newtask') ?>'><?php echo \BusinessLogic\Util\Language::output("solicitare_servicii")?></a>
            </div>
        </li>
        <?php } ?>
        <li class="<?php echo $arrow == "transactions" ? "selected" : ""; ?>">
            <div class='inner_li'>
                <?php if ($arrow == "transactions"): ?>
                    <div class='arrow'><img src='<?php echo base_url('assets/frontend/layout/user_arrow.png') ?>'/></div>
                <?php endif; ?>
                <a href='<?php echo base_url('account/transactions') ?>'><?php echo \BusinessLogic\Util\Language::output("tranzactii")?></a>
            </div>
        </li>
       <li class="<?php echo $arrow == "account" ? "selected" : ""; ?>">
            <div class='inner_li'>
                <?php if ($arrow == "account"): ?>
                    <div class='arrow'><img src='<?php echo base_url('assets/frontend/layout/user_arrow.png') ?>'/></div>
                <?php endif; ?>

                <a href='<?php echo base_url('account') ?>'><?php echo \BusinessLogic\Util\Language::output("date_profil")?></a>
            </div>
        </li>
        <li>
            <div class='inner_li'>

                <a href='<?php echo base_url('') ?>'><?php echo \BusinessLogic\Util\Language::output("inapoi_in_site")?></a>
            </div>
        </li>
    </ul>
</div>
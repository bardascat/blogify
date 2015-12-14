<div class="top">
    <a class="logo pointer" href="<?php echo base_url() ?>">
        <img src="<?php echo base_url(\BusinessLogic\Util\Language::output("helpie_logo_admin")) ?>"/>
    </a>
</div>


<nav class="desktop-menu" style="right:0em; margin-right: 0em; padding-top: 1.5em; padding-bottom: 1.5em;"> 
    <a href="<?php echo base_url('account') ?>">Dashboard</a>
    <a style="border:0px;" href="<?php echo base_url('user/logout') ?>">Logout</a>
</nav>

<nav class="mobile-menu">
    <div class="icon">
        <img class="sb-toggle-right" src="<?php echo base_url('assets/frontend/layout/menu.png'); ?>"/>
    </div>
</nav>

<div class="userIcon">
    <div class='welcome'>
        <?php
        if (!$user->getActivePachet()) {
            echo "Nu aveti niciun pachet cumparat";
            exit();
        } else
            echo \BusinessLogic\Util\Language::output("welcome")
            ?>, <?php echo $this->view->getUser()->getFirstname() ?> !
    </div>
</div>

<div class="tip_pachet">
    <div class="name"><?php echo \BusinessLogic\Util\Language::output("utilizator") ?> <?php echo $user->getActivePachet()->getName() ?></div>
    <div class="price"><?php echo \BusinessLogic\Util\Language::output("pret") ?>: <?php echo $user->getActivePachet()->getPrice() ?>  lei </div>
    <div class="expires"><?php echo \BusinessLogic\Util\Language::output("expira") ?> <?php echo $user->getActivePachet()->getExpireDate() ?></div>
</div>
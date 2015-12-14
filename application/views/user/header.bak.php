<?php /* @var $view View  */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="Romanian" />
        <meta http-equiv="Content-Language" content="ro" />
        <title><?php echo $this->view->getMetaTitle() ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
            <meta name="description" content="<?php echo $this->view->getMetaDesc() ?>"/>
            <meta name="keywords" content="<?php echo $this->view->getMetaKeywords() ?>"/>
            <?php echo $this->view->getCss(); ?>
            <link href='http://fonts.googleapis.com/css?family=Noto+Sans' rel='stylesheet' type='text/css'>
                <?php echo $this->view->getJs(); ?>
                <?php echo $this->view->getNotification() ?>
                <?php echo $this->view->getPopulate_form() ?>
                <link rel="shortcut icon"  type="image/png"  href="<?php echo base_url() ?>assets/images_fdd/favicon.ico">
                    </head>
                    <body>
                        <script>
                            (function($) {
                                $(document).ready(function() {
                                    $.slidebars();
                                });
                            })(jQuery);
                        </script>
                        <div class="sb-slidebar sb-right">
                            <nav>
                                <div class='sb-menu'>

                                    <li class='logo_nav'>
                                        <img src='<?php echo base_url('assets/frontend/layout/helpie_logo.png') ?>'/>
                                    </li>
                                    <li class='sb-close'><a href="<?php echo base_url('account') ?>">Dashboard</a></li>
                                    <li class='sb-close'><a href="<?php echo base_url('user/logout') ?>">Logout</a></li>
                                </div>
                            </nav>
                        </div>
                        <div id="wrapper" class="user_wrapper">
                            <div id='sb-site'>
                                <div class="header_outer">

                                    <div class="header-inner inner_small">
                                        <a class="logo pointer" href="<?php echo base_url() ?>">
                                            <img src="<?php echo base_url(\BusinessLogic\Util\Language::output("helpie_logo_admin")) ?>"/>
                                        </a>

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

                                            <div class='user_image' style="background-image: url('<?php echo $this->view->getUser()->getUserImage() ?>')" class="user_image">

                                            </div>
                                            <div class='welcome'>

                                                <?php
                                                if (!$user->getActivePachet()){
                                                    echo "Nu aveti niciun pachet cumparat";
                                                    exit();
                                                }
                                                else
                                                    echo \BusinessLogic\Util\Language::output("welcome")
                                                    ?>, <?php echo $this->view->getUser()->getFirstname() ?> !

                                            </div>

                                        </div>

                                        <div class="tip_pachet">
                                            <div class="name"><?php echo \BusinessLogic\Util\Language::output("utilizator") ?> <?php echo $user->getActivePachet()->getName() ?></div>
                                            <div class="price"><?php echo \BusinessLogic\Util\Language::output("pret") ?>: <?php echo $user->getActivePachet()->getPrice() ?>  lei </div>
                                            <div class="expires"><?php echo \BusinessLogic\Util\Language::output("expira") ?> <?php echo $user->getActivePachet()->getExpireDate() ?></div>
                                        </div>

                                    </div>
                                </div>
                                <?php 
                                
                             
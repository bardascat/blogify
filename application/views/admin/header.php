<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="Romanian" />
        <meta http-equiv="Content-Language" content="ro" />
        <?php echo $this->view->getCss('admin'); ?>
         <?php echo $this->view->getJs('admin'); ?>
         <?php echo $this->view->getPopulate_form() ?>
          <?php echo $this->view->getNotification() ?>
            <meta name="description" content=""/>
            <link rel="shortcut icon"  type="image/png"  href="<?php echo base_url() ?>assets/images_fdd/favicon.ico">
           </head>
                <body>
                    <div id="wrapper">
                        <div id="header">
                            <div class="admin_icon">
                                <a href='<?= base_url() ?>admin'>
                                    <img src="<?= base_url() ?>assets/images/admin/admin_icon.png" width="70"/>
                                </a>
                            </div>
                            <h2><?=$this->view->getPage_name() ?></h2>
                            <div class="menu">
                                <ul>
                                    <li><a href="<?php echo base_url() ?>">BusinessLogic.ro</a></li>
                                    <li><a href="<?php echo base_url('account/logout') ?>">Logout</a></li>
                                </ul>
                            </div>
                        </div>


                        <div id="dialog-confirm" title="Stergeti?" style='display: none;'>
                            <p style="margin-top: 10px;"><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Atentie ! Item-ul va fi sters definitiv !</p>
                        </div>
                        
                    
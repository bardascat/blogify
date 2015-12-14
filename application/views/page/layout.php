<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head profile="http://gmpg.org/xfn/1">


        <?php
        $ExtFolder = '../assets/admin/ext-3.4.0';
        ?>

        <base href="<? echo $this->config->config['base_url'] ?>"/>
        <title>Helpie Admin</title>
        <meta http-equiv="X-UA-Compatible" content="chrome=1">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <meta http-equiv="Content-Language" content="Romanian"/>
            <meta name="Author" content="Webmaster Office"/>
            <meta name="Category" content="Services"/>
            <meta name="Distribution" content="Global"/>
            <meta name="Doc-class" content="Living Document"/>
            <meta name="Document-rights" content="Copyrighted Work"/>
            <meta name="Language" content="ro"/>
            <meta name="MSSmartTagsPreventParsing" content="true"/>
            <meta name="Rating" content="General"/>
            <meta name="Resource-Type" content="document"/>
            <meta name="Revisit-after" content="5 days"/>
            <meta name="Robots" content="all"/>
            <meta name="Subject" content="Bine ai venit!"/>
            <meta http-equiv="Cache-Control" content="no-cache"/>

            <link rel="shortcut icon" href="<? echo $this->config->config['base_url'] ?>/favicon.ico" type="image/x-icon">


                <!-- CSS APP -->
                <link rel="stylesheet" type="text/css" href="provider/componentCss/<?= time() ?>.css"/>

                <!-- CSS EXTJS -->
                <link rel="stylesheet" type="text/css" href="<?= $ExtFolder ?>/resources/css/ext-all.css">
                    <link rel="stylesheet" type="text/css" href="<?= $ExtFolder ?>/resources/css/ext-all-notheme.css">
                        <link rel="stylesheet" type="text/css" href="<?= $ExtFolder ?>/resources/css/xtheme-gray.css">
                            <link rel="stylesheet" type="text/css" href="<?= $ExtFolder ?>/resources/css/xtheme-blue.css">
 



                                <!-- EXTJS  -->
                                <script http-equiv="content-script-type" content="text/javascript" src="<?= $ExtFolder ?>/adapter/ext/ext-base.js"></script>
                                <script http-equiv="content-script-type" content="text/javascript" src="<?= $ExtFolder ?>/ext-all.js"></script>
                                <!--
                                <script http-equiv="content-script-type" content="text/javascript" src="r<?= $ExtFolder ?>/src/locale/ext-lang-ro.js"></script>
                                -->

                                <script type="text/javascript">
                                    Ext.ns('APP');
                                    Ext.chart.Chart.CHART_URL = "<?= $ExtFolder ?>/resources/charts.swf";
                                    APP = {
                                        logout: 'admin/sessions/logout',
                                        user: [],
                                        user_rol: [],
                                        security_answer: 0,
                                        security_question: [],
                                        config: {
                                            items: []
                                        },
                                        menuUpdater: []
                                    };

                                <?= 'APP.main_panel_title = "' . ($main_panel_title ? $main_panel_title : false) . '";'; ?>
                                <?= 'APP.user_rol = ' . $userroles . ';'; ?>
                                <?= 'APP.user = ' . $userdetails . ';'; ?>
                                </script>


                                <script language="JavaScript" type="text/JavaScript" src="../assets/frontend/jquery.1.10.min.js"></script>
                                <!-- LOAD ALL EXTENSIONS -->
                                <script type="text/javascript" src="provider/extensions/<?= time() ?>.js"></script>
                                <!-- LOAD ALL MODULES -->
                                <script type="text/javascript" src="provider/allowed_components/<?= time() ?>.js"></script>
                                <script type="text/javascript" src="../assets/admin/resources/js/applicationFrame.js"></script>

                                <style>
                                    .down {
                                        background-color: #fff !important;
                                        text-align: center;
                                        color: #000;
                                    }

                                    .down p {
                                        background-color: #fff !important;
                                        text-align: left;
                                        color: #000;
                                        text-indent: 30px;
                                    }

                                </style>
                                </head>
                                <body>
                                    <div style="" id="loading-mask"></div>
                                    <div id="loading">
                                        <div class="loading-indicator">
                                            <img width="43" height="32" style="margin-right: 8px; float: left; vertical-align: top;" src="assets/admin/resources/img/preloader_atom.gif"/>Helpie.ro
                                            <br/>
                                            <span id="loading-msg">Initializare...</span>
                                        </div>
                                    </div>

                                    <div id="south_region_iefix"></div>

                                </body>
                                <Script>
                                    Ext.onReady(APP.main.exec, APP.main);
                                    //Ext.onReady(APP.lucrare.exec, APP.lucrare);

                                    /*
                                     var obj = {
                                     status_cod: "lucrari_dirig",
                                     title: "Lucrari constructor",
                                     iconCls: 'icon-fugue-node-insert-previous'
                                     }
                                     
                                     
                                     Ext.onReady(function () {
                                     
                                     //Ext.onReady(APP.sl_lucrare.exec(obj), APP.sl_lucrare);
                                     
                                     if(APP.mesaj_avertizare !== false) {
                                     new Ext.Window({
                                     bodyCssClass : "down",
                                     closable : true,
                                     width : 600,
                                     height : 450,
                                     html : APP.mesaj_avertizare,
                                     modal : false
                                     }).show();
                                     }
                                     
                                     if (parseInt(APP.security_answer, 10) !== 1) {
                                     return;
                                     }
                                     Ext.onReady(APP.securityAnswer.exec, APP.securityAnswer);
                                     });
                                     */
                                </script>

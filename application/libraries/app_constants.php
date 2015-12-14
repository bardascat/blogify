<?php

class App_constants {

    private static $JS_FILES = array(
        'assets/frontend/jquery.1.10.min.js',
        'assets/frontend/global.js',
        'assets/frontend/jquery_ui/ui-1-10.js',
        'assets/frontend/noty-2.2.2/js/noty/packaged/jquery.noty.packaged.js',
        "assets/frontend/source_fancy/jquery.fancybox.js",
        "assets/frontend/mobilemenu/slidebars.js",
    );
    private static $CSS_FILES = array(
        'assets/frontend/css/main.css',
        'assets/frontend/jquery_ui/ui-1-10.css',
        "assets/frontend/source_fancy/jquery.fancybox.css",
        "assets/frontend/mobilemenu/slidebars.css",
    );
    private static $ADMIN_JS_FILES = array(
        'assets/frontend/jquery.1.10.min.js',
        'assets/frontend/jquery_ui/ui-1-10.js',
        'assets/frontend/admin.js',
        "assets/frontend/source_fancy/jquery.fancybox.js",
        "assets/frontend/custom_alert/customAlert.js",
        "assets/frontend/ckeditorScripts/ckeditor.js",
        "assets/frontend/timepicker/timepicker.js",
        'assets/frontend/noty-2.2.2/js/noty/packaged/jquery.noty.packaged.js',
        'assets/frontend/treeview/jquery.treeview.js',
    );
    private static $ADMIN_CSS_FILES = array(
        'assets/frontend/css/admin.css',
        'assets/frontend/jquery_ui/ui-1-10.css',
        "assets/frontend/source_fancy/jquery.fancybox.css",
        "assets/frontend/custom_alert/customAlert.css",
        'assets/frontend/treeview/jquery.treeview.css',
    );
    private static $ADMIN_CSS_FILES_POPUP = array(
        'assets/frontend/css/popups.css',
        'assets/frontend/jquery_ui/ui-1-10.css',
        "assets/frontend/source_fancy/jquery.fancybox.css",
        "assets/frontend/custom_alert/customAlert.css"
    );
    public static $MASTER_PASSWORD = "MAST3Rpassword";
    public static $DEFAULT_ROLE = "guest";
 
    public static $OPERATOR_ROLE = "operator";
    public static $ADMIN_ROLE = "admin";
    public static $USER_ROLE = "client";
    public static $WEBSITE = "dev.helpie.ro";
    public static $WEBSITE_COMMERCIAL_NAME = "helpie.ro";
    public static $OFFICE_EMAIl = "contact@helpie.ro";
    //public static $OFFICE_EMAIl = "catalin.bardas@codesphere.ro";
    public static $WEBSITE_PHONE = "0311 000 399";
    //PAYMENT METHODS
    public static $PAYMENT_METHOD_CARD = 'CARD';
    public static $PAYMENT_METHOD_OP = 'OP';
    public static $PAYMENT_METHOD_FREE = 'FREE';
    public static $PAYMENT_METHOD_RAMBURS = 'RAMBURS';
    //PAYMENT STATUS
    public static $PAYMENT_STATUS_CONFIRMED = "F";
    public static $PAYMENT_STATUS_PENDING = "W";
    public static $PAYMENT_STATUS_CANCELED = "C";
    //PAYMENT STATUS
    public static $ORDER_STATUS_CONFIRMED = "F";
    public static $ORDER_STATUS_PENDING = "W";
    public static $ORDER_STATUS_CANCELED = "C";
    //tasks list
    public static $TASKLIST_PENDING = 2;
    public static $TASKLIST_CLOSED = 3;
    public static $TASKLIST_CANCELED = 4;
    //task status
    public static $TASKSTATUS_PENDING = 1;
    public static $TASKSTATUS_CLOSED= 2;
    public static $TASKSTATUS_CANCELED = 3;
    //supplierr info
    public static $SUPPLIER_NAME = " HELPIE SERIVCES SRL";
    public static $SUPPLIER_REG_COM = "REG COM";
    public static $SUPPLIER_CUI = "CUI";
    public static $SUPPLIER_ADDRESS = " Str Barbu Vacarescu nr 42A";
    public static $SUPPLIER_IBAN = "RO11 RZBR 0000 0600 1701 4383#";
    public static $SUPPLIER_BANK = "Raiffesien Bank";
    public static $CRON_ACCESS = "145839a025a99bda2e8f4f6a21ebbd5a4c893e91";
    
    public static $TRANZACTIE_DEBITARE=1;
    public static $TRANZACTIE_CHELTUIELI=2;
    //OP code
    public static $OPCODE="HPIE";
    
    public static $NOTIFICATION_EMAIL=1;
    public static $NOTIFICATION_PHONE=2;
    public static $NOTIFICATION_SMS=3;
    public static $CONT_HELPIE=4;

    
    public static function pushCSS($css) {
        array_push(self::$CSS_FILES, $css);
    }

    public static function pushJS($js) {
        array_push(self::$JS_FILES, $js);
    }

    public static function getJS_FILES() {
        return self::$JS_FILES;
    }

    public static function getCSS_FILES() {
        return self::$CSS_FILES;
    }

    public static function getADMIN_JS_FILES() {
        return self::$ADMIN_JS_FILES;
    }

    public static function getADMIN_CSS_FILES() {
        return self::$ADMIN_CSS_FILES;
    }

    public static function getADMIN_CSS_FILES_POPUP() {
        return self::$ADMIN_CSS_FILES_POPUP;
    }


}

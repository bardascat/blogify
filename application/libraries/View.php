<?php

class View {

    private $css;
    private $js;
    private $logged_user = FALSE;
    private $cart;
    private $page_name;
    private $page_description;
    private $populate_form;
    private $categories;
    private $notification;
    private $nrCartItems;
    private $metaTitle;
    private $metaDesc;
    private $metaKeywords;
     private $pages;
     private $PagesModel;
     
    function __construct() {
        
    }

    
    public function getPagesModel() {
        return $this->PagesModel;
    }

    public function setPagesModel($PagesModel) {
        $this->PagesModel = $PagesModel;
    }
    
    public function getPages() {
        return $this->pages;
    }

    public function setPages($pages) {
        $this->pages = $pages;
    }

        public function getPopulate_form() {
        return $this->populate_form;
    }

    public function setPopulate_form($populate_form) {
        $this->populate_form = $populate_form;
        return $this;
    }

    public function getCss($type = false) {
        switch ($type) {
            case "admin": {
                    $cssFiles = App_constants::getADMIN_CSS_FILES();
                }break;
            case "admin_popup": {
                    $cssFiles = App_constants::getADMIN_CSS_FILES_POPUP();
                }break;
            default: {
                    $cssFiles = App_constants::getCSS_FILES();
                }
        }

        if ($cssFiles) {
            $scripts = '';
            foreach ($cssFiles as $css) {
                $scripts.='<link rel="stylesheet" type="text/css" href="' . base_url($css) . '"/>';
            }
        }
        return $scripts;
    }

    public function getJs($admin = false) {
        $jsFiles = $cssFiles = ($admin ? App_constants::getADMIN_JS_FILES() : App_constants::getJS_FILES());
        if ($jsFiles) {
            $scripts = '';
            foreach ($jsFiles as $js) {
                $scripts.='<script type="text/javascript" src="' . base_url($js) . '"></script>';
            }
        }
        return $scripts;
    }

    /**
     * 
     * @return \BusinessLogic\Models\Entities\User
     */
    public function getUser() {
        return $this->logged_user;
    }

    public function setUser($logged_user) {
        $this->logged_user = $logged_user;
        return $this;
    }

    public function show_message($notification) {

        return "<div id='" . $notification['type'] . "' class='" . $notification['cssClass'] . "'>" . $notification['message'] . "</div>";
    }

    public function getPage_name() {
        return $this->page_name;
    }

    public function setPage_name($page_name) {
        $this->page_name = $page_name;
        return $this;
    }

    public function getPage_description() {
        return $this->page_description;
    }

    public function setPage_description($page_description) {
        $this->page_description = $page_description;
        return $this;
    }

    public function getCategories() {
        return $this->categories;
    }

    public function setCategories($categories) {
        $this->categories = $categories;
        return $this;
    }

    public function getNotification() {
        if (!$this->notification)
            return false;
        $text=$this->notification['html'];
        if(isset($this->notification['plugin']) && $this->notification['plugin']=="jqueryui"){
            
             $js="var dynamicDialog=$('<div id=\"MyDialog\">".$this->notification['html']."</div>');
            dynamicDialog.dialog({ title: '".$this->notification['title']."',
                   modal: true,
                   buttons: [{ text: 'Ok', click: function () {\$(this).dialog('close'); } }]
               });";
        }else
        $js = "var n=noty({
            layout: 'topCenter',
            type: '" . $this->notification['type'] . "',
            text: '".trim(preg_replace('/\s+/', ' ',$text))."',
            dismissQueue: true, // If you want to use queue feature set this true
            timeout: 3000,
            });";
        
        return '<script>$(document).ready(function(){' . $js . '});</script>';
    }

    public function setNotification($notification) {
        $this->notification = $notification;
    }

    public function getNrCartItems() {
        return $this->nrCartItems;
    }

    public function setNrCartItems($nrCartItems) {
        $this->nrCartItems = $nrCartItems;
        return $this;
    }
    public function getMetaTitle() {
        return $this->metaTitle;
    }

    public function getMetaDesc() {
        return $this->metaDesc;
    }

    public function getMetaKeywords() {
        return $this->metaKeywords;
    }

    public function setMetaTitle($metaTitle) {
        $this->metaTitle = $metaTitle;
        return $this;
    }

    public function setMetaDesc($metaDesc) {
        $this->metaDesc = $metaDesc;
        return $this;
    }

    public function setMetaKeywords($metaKeywords) {
        $this->metaKeywords = $metaKeywords;
        return $this;
    }

    public function getCartModel() {
        return $this->cart;
    }

    public function setCart($cart) {
        $this->cart = $cart;
        return $this;
    }




}
?>
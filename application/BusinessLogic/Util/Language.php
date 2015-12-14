<?php

/**
 * @author Bardas Catalin 
 */

namespace BusinessLogic\Util;

class Language {

    function __construct() {
        
    }

    static function getLanguage() {
        if (!isset($_COOKIE['site_language']))
            $_COOKIE['site_language'] = "en";
        return $_COOKIE['site_language'];
    }

    static function output($text) {
        if (!isset($_COOKIE['site_language']))
            $_COOKIE['site_language'] = "en";

        switch ($_COOKIE['site_language']) {
            case "ro": {
                    $language_xml = simplexml_load_file("application/language/ro/ro.xml");
                }break;
            default: {
                    $language_xml = simplexml_load_file("application/language/en/en.xml");
                }
        }

        $text = strtolower($text);
        return (String) $language_xml->$text;
    }

}

?>

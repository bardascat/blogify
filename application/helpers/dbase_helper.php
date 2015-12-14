<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//creare sintaxa WHERE pt quick search

function getQuickWhere($aFields, $sQuery = NULL) {

    $where = "";
    if (is_null($sQuery)) {
        return $sQuery;
    }

    if ($sQuery && is_array($aFields) && sizeof($aFields)) {
        $a = array();
        foreach ($aFields as $f) {
            $a[] = "$f regexp '$sQuery'";
        }
        $where .= $where ? " and(" : " (";
        $where .= implode(" or ", $a) . ")";
    }
    return $where;
}

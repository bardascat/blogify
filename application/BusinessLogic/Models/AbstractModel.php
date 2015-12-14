<?php

namespace BusinessLogic\Models;

use Doctrine\ORM\EntityManager;

class AbstractModel {

    /**
     *
     * @var EntityManager $em;
     */
    protected $em;

    function __construct() {
        $this->em = \Doctrine::getInstance()->getEm();
    }

    public function getNextId($table) {
        $q = "SHOW TABLE STATUS LIKE '$table'";

        $stmt = $this->em->getConnection()->prepare($q);
        $stmt->execute();
        $r = $stmt->fetchAll();
        return $r[0]['Auto_increment'];
    }

    public function getCurrentId($table, $column) {
        $q = "select * from $table order by $column desc limit 1";
        $stmt = $this->em->getConnection()->prepare($q);
        $stmt->execute();
        $r = $stmt->fetchAll();
        if (isset($r[0]['id']))
            return $r[0]['id'];
        else
            return 1;
    }

    public function getFoundRows() {
        // Run FOUND_ROWS query and add to results array
        $sql = 'SELECT FOUND_ROWS() AS foundRows';
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('foundRows', 'foundRows');
        $query = $this->em->createNativeQuery($sql, $rsm);
        $foundRows = $query->getResult();
        if ($foundRows[0]['foundRows'])
            return $foundRows[0]['foundRows'];
        else
            return 0;
    }

    function gridFiltersExt(\Doctrine\DBAL\Query\QueryBuilder &$dbalQuery, $params, $aColumnMapping) {




        if (is_array($aColumnMapping) && (count($aColumnMapping) > 0)) {
            foreach ($aColumnMapping as $value) {
                if ($value["ref"] == $params['sSortBy']) {
                    if (!$value["table"])
                         $params['sSortBy'] = $value["col"];
                    else
                         $params['sSortBy'] = $value["table"] . "." . $value["col"];

                   
                    break;
                }
            }
        }

        if (is_array($params['aFilters'])) {
            foreach ($params['aFilters'] as $oFilter) {
                $sFieldname = $oFilter->field;

                if (is_array($aColumnMapping) && (count($aColumnMapping) > 0)) {
                    foreach ($aColumnMapping as $value) {
                        if ($value["ref"] == $sFieldname) {
                            /**
                             * Update Catalin:
                             * Exista situatii cand nu am nevoie de tabel.coloana ex cand folosesc functii gen GROUP_CONCAT
                             */
                            if (!$value["table"])
                                $sFieldname = $value["col"];
                            else
                                $sFieldname = $value["table"] . "." . $value["col"];

                            break;
                        }
                    }
                }

                $sValue = $oFilter->value;
                $sCompare = isset($oFilter->comparison) ? $oFilter->comparison : NULL;
                $sFilterType = $oFilter->type;
                switch ($sFilterType) {
                    case 'string' :
                        $dbalQuery->andWhere($sFieldname . " like '%$sValue%'");
                        break;
                    case 'list' :
                        if (strstr($sValue, ',')) {
                            $aValues = explode(',', $sValue);
                            $this->db->andWhere_in($sFieldname, $aValues);
                        } else {
                            $this->db->andWhere($sFieldname, $sValue);
                        }
                        break;
                    case 'boolean' :
                        $this->db->andWhere($sFieldname, $sValue);
                        break;
                    case 'combo' :
                        $this->db->andWhere($sFieldname, $sValue);
                        break;
                    case 'numeric' :
                        switch ($sCompare) {
                            case 'eq' :
                                $dbalQuery->andWhere($sFieldname . '=' . $sValue);
                                break;
                            case 'lt' :
                                $dbalQuery->andWhere($sFieldname . '<' . $sValue);

                                break;
                            case 'gt' :
                                $dbalQuery->andWhere($sFieldname . '>' . $sValue);
                                break;
                            case 'gte' :
                                $dbalQuery->andWhere($sFieldname . '>=' . $sValue);
                                break;
                            case 'lte' :
                                $dbalQuery->andWhere($sFieldname . '<=' . $sValue);
                                break;
                        }
                        break;
                    case 'date' :
                        switch ($sCompare) {
                            case 'eq' :
                                $dbalQuery->andWhere("DATE_FORMAT($sFieldname, '%Y-%m-%d')='" . date('Y-m-d', strtotime($sValue)) . "'");

                                break;
                            case 'lt' :
                                $dbalQuery->andWhere("DATE_FORMAT($sFieldname, '%Y-%m-%d')<='" . date('Y-m-d', strtotime($sValue)) . "'");
                                break;
                            case 'gt' :
                                $dbalQuery->andWhere("DATE_FORMAT($sFieldname, '%Y-%m-%d')>='" . date('Y-m-d', strtotime($sValue)) . "'");
                                break;
                        }
                        break;
                }
            }
        }
        if (!$params['bIsExport']) {
           
            $dbalQuery->orderBy($params['sSortBy'], $params['sDir']);
            $dbalQuery->setFirstResult($params['sStart']);
            $dbalQuery->setMaxResults($params['sLimit']);
        } else {
            //Limita maxima de inregistrari
            $dbalQuery->setMaxResults(50000);
        }
    }

    /**
     * Seteaza un array cu parametrii de configurare/filtrare a gridului extjsj
     * @param type $aPost
     * @return type
     */
    function getGridFilterParams($aPost) {


        $sSort = ( isset($aPost["sort"]) && $aPost["sort"] != "" ) ? $aPost["sort"] : false;
        $sDir = ( isset($aPost["dir"]) && $aPost["dir"] != "" ) ? $aPost["dir"] : "ASC";
        $sStart = ( isset($aPost["start"]) && $aPost["start"] != "" ) ? $aPost["start"] : "0";
        $sLimit = ( isset($aPost["limit"]) && $aPost["limit"] != "" ) ? $aPost["limit"] : "50";
        $aFilters = isset($aPost["filter"]) ? json_decode($aPost["filter"]) : NULL;

        return array(
            "sSortBy" => $sSort,
            "sDir" => $sDir,
            "sStart" => $sStart,
            "sLimit" => $sLimit,
            "aFilters" => $aFilters,
            "bIsExport" => false
        );
    }

}

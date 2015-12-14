<?php
class Permisiunemodel extends MY_Model {
    function __construct() {
        parent::__construct();
    }

    /**
     * Functie care permisiunile
     */
    function getData($aPost) {

	    $aColumnMapping = array();
        $sSort = (isset($aPost["sort"]) && $aPost["sort"] != "") ? $aPost["sort"] : "permisiune_nume";
        $sDir = (isset($aPost["dir"]) && $aPost["dir"] != "") ? $aPost["dir"] : "ASC";
        $sStart = (isset($aPost["start"]) && $aPost["start"] != "") ? $aPost["start"] : "0";
        $sLimit = (isset($aPost["limit"]) && $aPost["limit"] != "") ? $aPost["limit"] : "50";
	    $aFilters = isset($aPost["filter"]) ? json_decode($aPost["filter"]) : null;

        $this->db->start_cache();
	    $this -> gridFilters($sSort, $sDir, $sStart, $sLimit, $aFilters, false, $aColumnMapping);
        $query = $this->db->get("permisiune");
        $totalCount = $this->db->count_all_results("permisiune");
        $this->db->flush_cache();
        $this->db->stop_cache();
        $aData = $query->result_array();

        $data = array('totalCount' => $totalCount, 'data' => $aData);
        return $data;
    }


    /**
     * Adaugare / editare
     * @param  $aData
     * @return bool|string
     */
    function editRecord($aData) {

        $iPermId = $aData["perm_id"];
        unset($aData["perm_id"]);

        //verifica duplicate dupa cod
        if (isset($aData["perm_cod"]) && $aData["perm_cod"] != "") {
            $bDuplicate = $this->_verifyDuplicat($iPermId, $aData);
            if ($bDuplicate === TRUE) {
                return "Permisiune duplicat";
            }
        }

        //inserare
        if ($iPermId == 0) {
            $this->db->insert("permisiune", $aData);
        }
            //editare
        else {
            $this->db->where("perm_id", $iPermId);
            $this->db->update("permisiune", $aData);
        }

        return TRUE;
    }


    /** Verificare duplicat permisiune
     * @param  $iPermId
     * @param  $aData
     * @return bool
     */
    function _verifyDuplicat($iPermId, $aData) {
        $this->db->where("perm_cod", $aData["perm_cod"]);
        $this->db->where("perm_id !=", $iPermId);
        $iCount = $this->db->get("permisiune")->num_rows();

        if ($iCount > 0) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Functie care aduce datele despre o inregistrare
     */
    function getRecord($iPermId) {
        $this->db->where("perm_id", $iPermId);
        return $this->db->get("permisiune")->row_array();
    }

    /**
     * Inserare permisiune prin sincronizare
     * @param  $sController - nume controller
     * @param  $sFunction - nume functie
     * @return void
     */
    function insertSincPermisiune($sController, $sFunction) {
        $this->db->where("perm_cod", $sController . "_" . $sFunction);
        $iCount = $this->db->get("permisiune")->num_rows();

        if ($iCount == 0) {
            $this->db->set("perm_tip", "controller");
            $this->db->set("perm_cod", $sController . "_" . $sFunction);
            $this->db->set("perm_nume", "Acces la controller-ul {$sController} functia {$sFunction}");
            $this->db->insert("permisiune");
        }
    }
}

/* End of file permisiunemodel.php */
/* Location: ./application/models/permisiunemodel.php */
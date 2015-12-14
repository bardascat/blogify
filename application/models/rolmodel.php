<?php
class Rolmodel extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    /**
     * Functie care aduce datele despre useri
     */
    function getData($aPost) {

        $sSort = (isset($aPost["sort"]) && $aPost["sort"] != "") ? $aPost["sort"] : "rol_nume";
        $sSir = (isset($aPost["dir"]) && $aPost["dir"] != "") ? $aPost["dir"] : "ASC";

        if (isset($aPost["start"]) && ($aPost["start"] > 0)) {
            $this->db->limit($aPost["limit"], $aPost["start"]);
        }

        $this->db->order_by($sSort, $sSir);
        $query = $this->db->get("rol");
        $totalCount = $this->db->count_all_results("rol");
        $aData = $query->result_array();

        $data = array('totalCount' => $totalCount, 'data' => $aData);
        return $data;
    }


    /**
     * Returneaza nomenclatorul de roluri
     * @return array
     */
    function getRol() {
        $this->db->order_by("rol_nume");
        return $this->db->get("rol")->result_array();
    }


    /**
     * Adaugare / editare
     * @param  $aData
     * @return bool|string
     */
    function editRecord($aData) {
        //creare array insert/editare
        $aTemp = array(
            "rol_nume" => $aData["rol_nume"]
        );

        //verifica duplicatele ( vezi dbase in dir library)
        $bDuplicate = $this->_verifyDuplicat($aData["rol_id"], $aTemp);
        if ($bDuplicate === TRUE) {
            return "Rol duplicat";
        }

        //insert
        if ($aData["rol_id"] == 0) {
            $this->db->insert("rol", $aTemp);
        }
            //editare
        else {
            $this->db->where("rol_id", $aData["rol_id"]);
            $this->db->update("rol", $aTemp);
        }

        return TRUE;
    }

    /**  Verificare duplicat tol
     * @param  $iRolId
     * @param  $aData
     * @return bool
     */
    function _verifyDuplicat($iRolId, $aData) {
        $this->db->where("rol_nume", $aData["rol_nume"]);
        $this->db->where("rol_id !=", $iRolId);
        $iCount = $this->db->get("rol")->num_rows();

        if ($iCount > 0) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Functie care aduce datele despre o inregistrare
     */
    function getRecord($IRolId) {
        $this->db->where("rol_id", $IRolId);
        return $this->db->get("rol")->row_array();
    }

}

/* End of file rolmodel.php */
/* Location: ./application/models/rolmodel.php */
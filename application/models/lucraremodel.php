<?php
class Lucraremodel extends MY_Model {
   private $aUserDetails = array();
   function __construct() {
      parent::__construct();
   }

   function getData($aData) {

      $aUserDetails = $this -> auth -> getUserDetails();
      if ($aUserDetails["partener_id"] != "") {
         $this -> db -> where("constructor", $aUserDetails["partener_id"]);
      }

      $sSort = (isset($aData["sort"]) && $aData["sort"] != "") ? $aData["sort"] : "lucrare.crc";
      $sDir = (isset($aData["dir"]) && $aData["dir"] != "") ? $aData["dir"] : "DESC";
      $sStart = (isset($aData["start"]) && $aData["start"] != "") ? $aData["start"] : "0";
      $sLimit = (isset($aData["limit"]) && $aData["limit"] != "") ? $aData["limit"] : "25";

      $aFilters = isset($aData["filter"]) ? json_decode($aData["filter"]) : null;
      $aColumnMapping = array("lucrare" => "crc");

      if ((isset($aData["lucrariProprii"])) && ($aData["lucrariProprii"] == 1)) {
         $this -> db -> where("responsabil_ag", $aUserDetails["user_marca"]);
      }

      $this -> db -> select("SQL_CALC_FOUND_ROWS lucrare.crc, data_emitere_ol, data_semnare_ol, nume_client, nom_motiv_comanda.motiv_comanda_nume,  judet_denumire_loc_consum,  oras_loc_consum, strada_loc_consum, bloc_loc_consum, null as actiune_descriere,adresa_loc_consum, GROUP_CONCAT( DISTINCT rabt_tip_nume,' ',DATE_FORMAT(lucrare_rabt.data_remediere, '%d.%m.%Y') SEPARATOR '<br>' ) as rabt_nume,status_1_nume , status_2_nume,  data_reluare_lucrare, partener_nume,responsabil_ag_nume, data_depunere_cu, data_obtinere_cu, rasp_neces_valid_dt,  data_zatp, data_atp, data_depunere_ac, data_obtinere_ac, rasp_neces_valid_as, data_depunere_as, data_obtinere_as, rasp_neces_ref_pavaj , data_sol_incep_lucr, data_prec_incep_lucr, data_reala_exec_lucr , data_solicitare_rt, data_terminare_lucrare, lucrare_migrata ", false);

      //$this -> db -> where("data_semnare_ol is not null", null, false);
      $this -> db -> join("lucrare_status", 'lucrare_status.crc=lucrare.crc', "left");
      $this -> db -> join("lucrare_date", 'lucrare_date.crc=lucrare.crc', "left");
      $this -> db -> join("lucrare_doc", 'lucrare_doc.crc=lucrare.crc', "left");
      $this -> db -> join("lucrare_raspunsuri", "lucrare_raspunsuri.crc=lucrare.crc", "left");
      $this -> db -> join("nom_motiv_comanda", "nom_motiv_comanda.motiv_comanda_id=lucrare.motiv_comanda_crc", "left");
      $this -> db -> join("lucrare_rabt", "lucrare_rabt.crc=lucrare.crc and lucrare_rabt.rabt_status='nou'  ", "left");
      $this -> db -> join("nom_partener", "nom_partener.partener_id=lucrare.constructor ", "left");
      $this -> db -> join("nom_rabt_tip", "nom_rabt_tip.rabt_tip_id=lucrare_rabt.motiv_pozitie_rabt and lucrare_rabt.rabt_status='nou'  ", "left");

      $this -> db -> join("nom_status_1 ", "nom_status_1.status_1_id=lucrare.status_1_id ", "left");
      $this -> db -> join("nom_status_2 ", "nom_status_2.status_2_id=lucrare.status_2_id ", "left");

      if (isset($aData['arhiva']) && ($aData["arhiva"] == 1)) {
         $this -> db -> where("data_reluare_lucrare is null", null, false);
         $this -> db -> where(" (data_reziliere is not null OR data_terminare_lucrare is not null )", null, false);
      }

      if (isset($aData['lucru']) && ($aData["lucru"] == 1)) {
         $this -> db -> where(" (data_reziliere is null AND data_terminare_lucrare is null AND data_reluare_lucrare is null)  ", null, false);
      }

      if (isset($aData['suspendare']) && ($aData["suspendare"] == 1)) {
         $this -> db -> where("data_reluare_lucrare is not null", null, false);
      }

      $this -> gridFilters($sSort, $sDir, $sStart, $sLimit, $aFilters, false, $aColumnMapping);
      $this -> db -> where("lucrare_activa", 1);
      $this -> db -> group_by("lucrare.crc");
      $query = $this -> db -> get("lucrare");
      // e($this->db->last_query());

      //$totalCount = $this -> db -> count_all_results("lucrare");
      $totalCount = $this -> db -> query('SELECT FOUND_ROWS() total_rows') -> row() -> total_rows;

      $aData = $query -> result_array();
      $data = array(
         'totalCount' => $totalCount,
         'data' => $aData
      );
      return $data;
   }

   function getLogData($aData) {

      $aUserDetails = $this -> auth -> getUserDetails();
      $aColumnMapping = array("log_operatie" => "crc");
      $aFilters = isset($aData["filter"]) ? json_decode($aData["filter"]) : null;
      $sSort = (isset($aData["sort"]) && $aData["sort"] != "") ? $aData["sort"] : "operatie_data";
      $sDir = (isset($aData["dir"]) && $aData["dir"] != "") ? $aData["dir"] : "DESC";
      $sStart = (isset($aData["start"]) && $aData["start"] != "") ? $aData["start"] : "0";
      $sLimit = (isset($aData["limit"]) && $aData["limit"] != "") ? $aData["limit"] : "50";

      if ($aUserDetails["partener_id"] != "") {
         $this -> db -> where("constructor", $aUserDetails["partener_id"]);
      }

      $this -> db -> where("operatie_eroare", 0);
      $this -> gridFilters($sSort, $sDir, $sStart, $sLimit, $aFilters, false, $aColumnMapping);
      $this -> db -> select("SQL_CALC_FOUND_ROWS log_operatie.*, user_alias, data_emitere_ol,data_semnare_ol, nume_client, nom_motiv_comanda.motiv_comanda_nume,  judet_denumire_loc_consum,  oras_loc_consum, strada_loc_consum, bloc_loc_consum,partener_nume,responsabil_ag_nume ", false);
      $this -> db -> join("user", "user.user_id=log_operatie.operatie_user", "left");
      $this -> db -> join("lucrare", 'lucrare.crc=log_operatie.crc', "left");
      $this -> db -> join("nom_motiv_comanda", "nom_motiv_comanda.motiv_comanda_id=lucrare.motiv_comanda_crc", "left");
      $this -> db -> join("nom_partener", "nom_partener.partener_id=lucrare.constructor", "left");
      $this -> db -> where("lucrare_activa", 1);

      if (isset($aData['crc']) && ($aData['crc'] != "")) {
         $this -> db -> where("log_operatie.crc", $aData['crc']);
      }

      $this -> db -> limit($sLimit, $sStart);
      $this -> db -> where('log_operatie.crc >', "0", false);
      $query = $this -> db -> get("log_operatie");
      $totalCount = $this -> db -> query('SELECT FOUND_ROWS() total_rows') -> row() -> total_rows;

      $aData = $query -> result_array();

      $data = array(
         'totalCount' => $totalCount,
         'data' => $aData
      );
      return $data;
   }

   function getCrc($aData) {

      $aUserDetails = $this -> auth -> getUserDetails();

      $sSort = (isset($aData["sort"]) && $aData["sort"] != "") ? $aData["sort"] : "crc";
      $sSir = (isset($aData["dir"]) && $aData["dir"] != "") ? $aData["dir"] : "ASC";
      $sStart = (isset($aData["start"]) && $aData["start"] != "") ? $aData["start"] : "0";
      $sLimit = (isset($aData["limit"]) && $aData["limit"] != "") ? $aData["limit"] : "20";

      if (isset($aData['arhiva']) && ($aData["arhiva"] == 1)) {
         $this -> db -> where("data_reluare_lucrare is null", null, false);
         $this -> db -> where(" (data_reziliere is not null OR data_terminare_lucrare is not null )", null, false);
      }

      if ($aUserDetails["partener_id"] != "") {
         $this -> db -> where("constructor", $aUserDetails["partener_id"]);
      }

      if (isset($aData['lucru']) && ($aData["lucru"] == 1)) {
         $this -> db -> where(" (data_reziliere is null AND data_terminare_lucrare is null)  ", null, false);
      }

      if (isset($aData['suspendare']) && ($aData["suspendare"] == 1)) {
         $this -> db -> where("data_reluare_lucrare is not null", null, false);
      }

      $this -> db -> select("lucrare.crc ", false);
      $this -> db -> order_by($sSort, $sSir);
      $this -> db -> like("crc", $aData["query"]);
      $this -> db -> limit($sLimit, $sStart);
      $query = $this -> db -> get("lucrare");
      $totalCount = $this -> db -> count_all_results("lucrare");
      $aData = $query -> result_array();

      $data = array(
         'totalCount' => $totalCount,
         'data' => $aData
      );
      return $data;
   }

   function addDateInitiale($aData) {

      //daca este reincredintare (realocare) atunci se sterge lucrarea
      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> delete("lucrare");

      //daca este reincredintare (realocare) atunci se sterge lucrarea
      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> delete("log_operatie");

      //aducere nume responsabil; baza HR este p webin - aplicatia webex
      $DB1 = $this -> load -> database('hr', TRUE);
      $DB1 -> where("ID_PERSONAL", $aData["responsabil_ag"]);
      $aResponsabil = $DB1 -> get('PERSONAL') -> row_array();
      $aData["responsabil_ag_nume"] = $aResponsabil["NUME"] . ' ' . $aResponsabil["PRENUME"];

      $this -> db -> insert("lucrare", array("crc" => $aData["crc"]));
      $this -> db -> insert("lucrare_date", array("crc" => $aData["crc"]));
      $this -> db -> insert("lucrare_doc", array("crc" => $aData["crc"]));
      $this -> db -> insert("lucrare_raspunsuri", array("crc" => $aData["crc"]));
      $this -> db -> insert("lucrare_avize", array("crc" => $aData["crc"]));

      // pentru migrare se considera toate campurile din IDOC

      //update TABELA lucrare
      $aLucrareCol = $this -> db -> list_fields("lucrare");
      $aLucrareCol = array_flip($aLucrareCol);
      $aLucrare = array_intersect_key($aData, $aLucrareCol);

      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> update('lucrare', $aLucrare);

      //update TABELA lucrare_date
      $aLucrareDateCol = $this -> db -> list_fields("lucrare_date");
      $aLucrareDateCol = array_flip($aLucrareDateCol);
      $aLucrareDate = array_intersect_key($aData, $aLucrareDateCol);

      $aLucrareDate["data_depunere_cu_real"] = 1;
      if ($aLucrareDate["data_depunere_cu"] == "9999-01-01") {
         $aLucrareDate["data_depunere_cu"] = null;
         $aLucrareDate["data_depunere_cu_real"] = 0;
      }

      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> update('lucrare_date', $aLucrareDate);

      //update TABELA lucrare_doc
      $aLucrareDocCol = $this -> db -> list_fields("lucrare_doc");
      $aLucrareDocCol = array_flip($aLucrareDocCol);
      $aLucrareDoc = array_intersect_key($aData, $aLucrareDocCol);

      if ($aLucrareDoc["data_atp"] != "") {
         $aLucrareDoc["status_atp"] = "Admis";
      }
      else {
         if ($aLucrareDoc["motiv_resp_atp"] != "") {
            $aLucrareDoc["status_atp"] = "Respins";
         }
      }

      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> update('lucrare_doc', $aLucrareDoc);

      //update TABELA lucrare_raspunsuri
      $aLucrareRaspCol = $this -> db -> list_fields("lucrare_raspunsuri");
      $aLucrareRaspCol = array_flip($aLucrareRaspCol);
      $aLucrareRasp = array_intersect_key($aData, $aLucrareRaspCol);

      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> update('lucrare_raspunsuri', $aLucrareRasp);

      //update TABELA lucrare_avize
      $aLucrareAvizeCol = $this -> db -> list_fields("lucrare_avize");
      $aLucrareAvizeCol = array_flip($aLucrareAvizeCol);
      $aLucrareAvize = array_intersect_key($aData, $aLucrareAvizeCol);

      for ($i = 1; $i <= 25; $i++) {
         $sCuavz = str_pad($i, 2, "0", STR_PAD_LEFT);
         if (isset($aLucrareAvize["cuavz" . $sCuavz]) && ($aLucrareAvize["cuavz" . $sCuavz] != "")) {
            $aLucrareAvize["necesita_avize"] = 1;
            break;
         }
      }

      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> update('lucrare_avize', $aLucrareAvize);

      //spargere adresa consum
      $aJudete = $this -> db -> get_where("nom_judete", array("jud_id" => $aData["judet_loc_consum"])) -> row_array();
      $sJudet = $aAdresaBloc = null;
      if (isset($aJudete["jud_nume"])) {
         $sJudet = $aJudete["jud_nume"];
      }
      $aAdresa = explode(",", $aData["adresa_loc_consum"]);
      $aAdresaBloc = isset($aAdresa[3]) ? $aAdresa[3] : null;
      $aAdresaBloc .= isset($aAdresa[4]) ? $aAdresa[4] : null;

      $aTemp = array(
         "judet_denumire_loc_consum" => $sJudet,
         "oras_loc_consum" => trim($aAdresa[0]),
         'strada_loc_consum' => trim($aAdresa[2]),
         "bloc_loc_consum" => trim($aAdresaBloc)
      );
      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> update("lucrare", $aTemp);

      return TRUE;
   }

   function getLucrareById($iCrc) {
      $aAvizeObt = $sAvizeDep = array();
      for ($i = 1; $i <= 25; $i++) {
         $aAvizeObt[] = "IFNULL(data_dep_cuavz" . str_pad($i, 2, "0", STR_PAD_LEFT) . ",0)";
         $sAvizeDep[] = "IFNULL(data_obt_cuavz" . str_pad($i, 2, "0", STR_PAD_LEFT) . ",0)";
      }

      $this -> db -> select(" lucrare.*,  lucrare_avize.*, lucrare_date.*, lucrare_doc.*,  lucrare_raspunsuri.*,  
      GREATEST(" . implode(",", $aAvizeObt) . ") as aviz_obt,   
      GREATEST(" . implode(",", $sAvizeDep) . ") as aviz_dep ", false);
      $this -> db -> join("lucrare_avize", "lucrare_avize.crc=lucrare.crc", "left");
      $this -> db -> join("lucrare_date", "lucrare_date.crc=lucrare.crc", "left");
      $this -> db -> join("lucrare_doc", "lucrare_doc.crc=lucrare.crc", "left");
      $this -> db -> join("lucrare_raspunsuri", "lucrare_raspunsuri.crc=lucrare.crc", "left");
      $this -> db -> where("lucrare.crc", $iCrc);
      $aQuery = $this -> db -> get("lucrare");

      if ($aQuery -> num_rows() == 0) {
         return FALSE;
      }
      return $aQuery -> row_array();
   }

   /*
    * functie de aducere a datelor despre o lucrare in modulul de afisare informatii; NU SE folososeste functie getLucrareById pentru a nu se aduce mai multei informatii decat este necesar
    * */
   function getLucrareTotal($iCrc) {

      $aUserDetails = $this -> auth -> getUserDetails();

      $this -> db -> select("L.crc,L.data_emitere_ol, L.data_semnare_ol, L.adresa_loc_consum, L.nume_client, LD.data_depunere_cu, LD.data_obtinere_cu, LD.data_depunere_ac, LD.data_obtinere_ac, LD.data_depunere_as, LD.data_obtinere_as, data_sol_incep_lucr, data_prec_incep_lucr, LDOC.status_atp, LDOC.data_atp, LDOC.data_resp_atp, LDOC.status_zatp, LDOC.data_zatp, LDOC.data_resp_zatp, LDOC.status_cri,  LDOC.data_cri, LDOC.data_resp_cri, LDOC.status_ulf, LDOC.data_ulf, LDOC.data_resp_ulf,  L.status_1_id, status_1_nume, L.status_2_id, status_2_nume, ATP.motiv_id as motiv_id_atp, ATP.motiv_nume as motiv_nume_atp,  ZATP.motiv_id as motiv_id_zatp , ZATP.motiv_nume as motiv_nume_zatp, ULF.motiv_id as motiv_id_ulf, ULF.motiv_nume as motiv_nume_ulf, CRI.motiv_id as motiv_id_cri, CRI.motiv_nume as motiv_nume_cri, data_reala_exec_lucr, marca_diriginte, data_diriginte, data_terminare_lucrare, partener_nume, partener_id,  data_solicitare_rt, nume_firma_ref_pavaj, rasp_neces_valid_dt, rasp_neces_valid_as, motiv_comanda_id, CONCAT(motiv_comanda_id, ' ', motiv_comanda_nume) as motiv_comanda_nume , responsabil_ag, responsabil_ag_nume , data_reluare_lucrare, data_terminare_lucrare, data_depunere_cu_real, necesita_avize, lucrare_activa ", false);

      $this -> db -> join("lucrare_date LD", "LD.crc=L.crc", "left");
      $this -> db -> join("lucrare_avize", "lucrare_avize.crc=L.crc", "left");
      $this -> db -> join("lucrare_raspunsuri LR", "LR.crc=L.crc", "left");
      $this -> db -> join("lucrare_doc LDOC", "LDOC.crc=L.crc", "left");
      $this -> db -> join("nom_status_1 ", "nom_status_1.status_1_id=L.status_1_id ", "left");
      $this -> db -> join("nom_status_2 ", "nom_status_2.status_2_id=L.status_2_id ", "left");
      $this -> db -> join("nom_partener ", "nom_partener.partener_id=L.constructor ", "left");
      $this -> db -> join("nom_motiv_comanda", "nom_motiv_comanda.motiv_comanda_id=L.motiv_comanda_crc", "left");

      $this -> db -> join("nom_motiv_respingere ATP ", "ATP.motiv_id=LDOC.motiv_resp_atp ", "left");
      $this -> db -> join("nom_motiv_respingere ZATP ", "ZATP.motiv_id=LDOC.motiv_resp_zatp ", "left");
      $this -> db -> join("nom_motiv_respingere ULF ", "ULF.motiv_id=LDOC.motiv_resp_ulf ", "left");
      $this -> db -> join("nom_motiv_respingere CRI ", "CRI.motiv_id=LDOC.motiv_resp_cri ", "left");

      if ($aUserDetails["partener_id"] != "") {
         $this -> db -> where("constructor", $aUserDetails["partener_id"]);
      }

      $this -> db -> where("L.crc", $iCrc);
      $aQuery = $this -> db -> get("lucrare L");
      if ($aQuery -> num_rows() == 0) {
         return FALSE;
      }

      return $aQuery -> row_array();
   }

   function checkLucrareById($iCrc) {
      $aQuery = $this -> db -> get_where("lucrare", array("crc" => $iCrc));
      if ($aQuery -> num_rows() == 0) {
         //e("yyy");
         return FALSE;
      }
      return TRUE;
   }

   /*functie de inserare data semnare OL*/
   function addDataSemnareOL($aLucrare) {
      $this -> db -> where("crc", $aLucrare["crc"]);
      $this -> db -> update("lucrare", $aLucrare);
      return TRUE;
   }

   function addStatusDTavizare($aLucrare) {

      if (!isset($aLucrare['motiv_resp_atp']) || ($aLucrare['motiv_resp_atp'] == "")) {
         $aTemp = array(
            "data_atp" => $aLucrare["data_atp"],
            "status_atp" => "Admis"
         );
         $aTempIstoric = array(
            "actiune_cod" => CONSTANTS::ACT_STATUS_DT_AVIZARE,
            "crc" => $aLucrare["crc"],
            "ist_avizare_status_admis" => "Admis",
            "ist_avizare_data_admis" => $aLucrare["data_atp"]
         );
      }

      if (isset($aLucrare['motiv_resp_atp']) && ($aLucrare['motiv_resp_atp'] != "")) {
         $aTemp = array(
            "data_resp_atp" => $aLucrare["data_resp_atp"],
            "motiv_resp_atp" => $aLucrare["motiv_resp_atp"],
            "data_atp" => null,
            "status_atp" => "Respins"
         );
         $aTempIstoric = array(
            "crc" => $aLucrare["crc"],
            "ist_avizare_status_admis" => "Respins",
            "actiune_cod" => CONSTANTS::ACT_STATUS_DT_AVIZARE,
            "ist_avizare_motiv_respingere" => $aLucrare["motiv_resp_atp"],
            "ist_avizare_data_respingere" => $aLucrare["data_resp_atp"]
         );
      }

      if (count($aLucrare) > 0) {
         $this -> db -> where("crc", $aLucrare["crc"]);
         $this -> db -> update("lucrare_doc", $aTemp);
         //inserare istoric avizare
         $this -> db -> insert("ist_avizare", $aTempIstoric);
      }
      return TRUE;
   }

   function addStatusDTvalidare($aLucrare) {
      if (!isset($aLucrare['motiv_resp_zatp']) || ($aLucrare['motiv_resp_zatp'] == "")) {
         $aTemp = array(
            "data_zatp" => $aLucrare["data_zatp"],
            "status_zatp" => "Admis"
         );
         $aTempIstoric = array(
            "actiune_cod" => CONSTANTS::ACT_STATUS_DT_VALIDARE,
            "crc" => $aLucrare["crc"],
            "ist_avizare_status_admis" => "Admis",
            "ist_avizare_data_admis" => $aLucrare["data_zatp"]
         );
      }

      if (isset($aLucrare['motiv_resp_zatp']) && ($aLucrare['motiv_resp_zatp'] != "")) {
         $aTemp = array(
            "data_resp_zatp" => $aLucrare["data_resp_zatp"],
            "motiv_resp_zatp" => $aLucrare["motiv_resp_zatp"],
            "data_zatp" => null,
            "status_zatp" => "Respins"
         );
         $aTempIstoric = array(
            "crc" => $aLucrare["crc"],
            "ist_avizare_status_admis" => "Respins",
            "actiune_cod" => CONSTANTS::ACT_STATUS_DT_VALIDARE,
            "ist_avizare_motiv_respingere" => $aLucrare["motiv_resp_zatp"],
            "ist_avizare_data_respingere" => $aLucrare["data_resp_zatp"]
         );
      }

      if (count($aLucrare) > 0) {
         $this -> db -> where("crc", $aLucrare["crc"]);
         $this -> db -> update("lucrare_doc", $aTemp);

         //inserare istoric avizare
         $this -> db -> insert("ist_avizare", $aTempIstoric);
      }
      return TRUE;
   }

   function addStatusCRI($aLucrare) {

      if (!isset($aLucrare['motiv_resp_cri']) || ($aLucrare['motiv_resp_cri'] == "")) {
         $aTemp = array(
            "data_cri" => $aLucrare["data_cri"],
            "status_cri" => "Admis"
         );
         $aTempIstoric = array(
            "actiune_cod" => CONSTANTS::ACT_STATUS_CRI,
            "crc" => $aLucrare["crc"],
            "ist_avizare_status_admis" => "Admis",
            "ist_avizare_data_admis" => $aLucrare["data_cri"]
         );
      }

      if (isset($aLucrare['motiv_resp_cri']) && ($aLucrare['motiv_resp_cri'] != "")) {
         $aTemp = array(
            "data_resp_cri" => $aLucrare["data_resp_cri"],
            "motiv_resp_cri" => $aLucrare["motiv_resp_cri"],
            "data_cri" => null,
            "status_cri" => 'Respins'
         );
         $aTempIstoric = array(
            "crc" => $aLucrare["crc"],
            "ist_avizare_status_admis" => "Respins",
            "actiune_cod" => CONSTANTS::ACT_STATUS_CRI,
            "ist_avizare_motiv_respingere" => $aLucrare["motiv_resp_cri"],
            "ist_avizare_data_respingere" => $aLucrare["data_resp_cri"]
         );
      }

      if (count($aLucrare) > 0) {
         $this -> db -> where("crc", $aLucrare["crc"]);
         $this -> db -> update("lucrare_doc", $aTemp);

         //inserare istoric avizare
         $this -> db -> insert("ist_avizare", $aTempIstoric);
      }
      return TRUE;
   }

   function addStatusULF($aLucrare) {

      //daca vine pe ulf data preconizata lucrare atunci se actualizeaza
      if ($aLucrare['data_prec_incep_lucr'] != "") {
         $this -> db -> where("crc", $aLucrare["crc"]);
         $this -> db -> set("data_prec_incep_lucr", $aLucrare['data_prec_incep_lucr']);
         $this -> db -> update("lucrare_date");
      }

      //tratare ULF in functie de  de motiv
      if (!isset($aLucrare['motiv_resp_ulf']) || ($aLucrare['motiv_resp_ulf'] == "")) {
         $aTemp = array(
            "data_ulf" => $aLucrare["data_ulf"],
            "status_ulf" => "In asteptare"
         );
         $aTempIstoric = array(
            "actiune_cod" => CONSTANTS::ACT_STATUS_ULF,
            "crc" => $aLucrare["crc"],
            "ist_avizare_status_admis" => "In asteptare",
            "ist_avizare_data_admis" => $aLucrare["data_ulf"]
         );
      }

      if (isset($aLucrare['motiv_resp_ulf']) && ($aLucrare['motiv_resp_ulf'] != "")) {
         $aTemp = array(
            "data_resp_ulf" => $aLucrare["data_resp_ulf"],
            "motiv_resp_ulf" => $aLucrare["motiv_resp_ulf"],
            "data_ulf" => null,
            "status_ulf" => 'Respins'
         );
         $aTempIstoric = array(
            "crc" => $aLucrare["crc"],
            "ist_avizare_status_admis" => "Respins",
            "actiune_cod" => CONSTANTS::ACT_STATUS_ULF,
            "ist_avizare_motiv_respingere" => $aLucrare["motiv_resp_ulf"],
            "ist_avizare_data_respingere" => $aLucrare["data_resp_ulf"]
         );
      }

      if (count($aLucrare) > 0) {
         $this -> db -> where("crc", $aLucrare["crc"]);
         $this -> db -> update("lucrare_doc", $aTemp);

         //inserare istoric avizare
         $this -> db -> insert("ist_avizare", $aTempIstoric);
      }
      return TRUE;
   }

   function addReziliere($aLucrare) {
      $this -> db -> where("crc", $aLucrare["crc"]);
      $this -> db -> update("lucrare", $aLucrare);
      return TRUE;
   }

   function addTerminare($aLucrare) {
      $this -> db -> where("crc", $aLucrare["crc"]);
      $this -> db -> update("lucrare", $aLucrare);
      return TRUE;
   }

   function addSuspendare($aLucrare) {
      $this -> db -> where("crc", $aLucrare["crc"]);
      $this -> db -> update("lucrare", $aLucrare);
      return TRUE;
   }

   /*functie generala de update informatii a unei lucrari pentru data exec lucrare*/
   function sendDataRealaExecutie($aLucrare) {

      $DB1 = $this -> load -> database('hr', TRUE);
      $DB1 -> where("ID_PERSONAL", $aLucrare["responsabil_ag"]);
      $aDiriginte = $DB1 -> get('PERSONAL') -> row_array();

      $aLucrare["data_diriginte"] = $aDiriginte["NUME"] . ' ' . $aDiriginte["PRENUME"];
      $aLucrare["marca_diriginte"] = $aLucrare["responsabil_ag"];
      unset($aLucrare["responsabil_ag"]);
      $this -> db -> where("crc", $aLucrare["crc"]);
      $this -> db -> update("lucrare_date", $aLucrare);

      //numai daca se trimite data reala executie se pune status Admis
      if ($aLucrare['data_reala_exec_lucr'] != "") {
         $aTemp = array(
            "data_ulf" => $aLucrare['data_reala_exec_lucr'],
            "status_ulf" => "Admis"
         );
         $this -> db -> where("crc", $aLucrare["crc"]);
         $this -> db -> update("lucrare_doc", $aTemp);

         $aTemp = array(
            "status_2_data" => date("Y-m-d H:i:s"),
            "status_2_id" => CONSTANTS::STAT_ULF_AVIZAT
         );
         $this -> db -> where("crc", $aLucrare["crc"]);
         $this -> db -> update("lucrare", $aTemp);

         $aTempIstoric = array(
            "crc" => $aLucrare["crc"],
            "ist_avizare_status_admis" => "Admis",
            "actiune_cod" => CONSTANTS::ACT_STATUS_ULF,
            "ist_avizare_data_admis" => $aLucrare['data_reala_exec_lucr']
         );
         $this -> db -> insert("ist_avizare", $aTempIstoric);
      }

      //daca e cazul se reia lucrarea
      $this -> db -> where("crc", $aLucrare["crc"]);
      $this -> db -> set('data_reluare_lucrare', null);
      $this -> db -> update("lucrare");

      return TRUE;
   }

   function addRaspunsRabt($sCrc, $sRaspuns, $iRabt) {
      $aUserDetails = $this -> auth -> getUserDetails();
      $this -> db -> where("crc", $sCrc);
      $this -> db -> where("rabt_id", $iRabt);
      $this -> db -> set("obiectiuni_rabt_temp", $sRaspuns);
      $this -> db -> set("rabt_obiectiuni", 0);
      $this -> db -> set("obiectiuni_rabt_temp_user_id", $aUserDetails["user_id"]);
      $this -> db -> update("lucrare_rabt");
      return TRUE;
   }

   function addDataDepunereCU($sCrc, $aDataDepunereCu, $aDataDepunereCuReal) {

      if ($aDataDepunereCuReal == 0) {
         $aDataDepunereCu = null;
      }

      $this -> db -> where("crc", $sCrc);
      $this -> db -> set("data_depunere_cu_real", $aDataDepunereCuReal);
      $this -> db -> set("data_depunere_cu", $aDataDepunereCu);
      $this -> db -> update("lucrare_date");
      return TRUE;
   }

   function addDataDepunereAC($aData) {
      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> set("data_depunere_ac", $aData["data_depunere_ac"]);
      $this -> db -> update("lucrare_date");
      return TRUE;
   }

   function addDataObtinereAC($aData) {
      $this -> db -> where("crc", $aData['crc']);
      $this -> db -> set("data_obtinere_ac", $aData["data_obtinere_ac"]);
      $this -> db -> update("lucrare_date");

      $aInsert = array(
         "rasp_neces_ref_pavaj" => $aData["rasp_neces_ref_pavaj"],
         "nume_firma_ref_pavaj" => $aData["nume_firma_ref_pavaj"],
         "rasp_neces_valid_as" => $aData["rasp_neces_valid_as"]
      );
      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> update("lucrare_raspunsuri", $aInsert);
      return TRUE;
   }

   function addDataDepunereAS($sCrc, $aDataDepunereAS) {
      $this -> db -> where("crc", $sCrc);
      $this -> db -> set("data_depunere_as", $aDataDepunereAS);
      $this -> db -> update("lucrare_date");
      return TRUE;
   }

   function addDataObtinereAS($sCrc, $aDataObtinereAS) {
      $this -> db -> where("crc", $sCrc);
      $this -> db -> set("data_obtinere_as", $aDataObtinereAS);
      $this -> db -> update("lucrare_date");
      return TRUE;
   }

   function addIncepereLucrare($aData) {
      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> set("data_sol_incep_lucr", $aData["data_sol_incep_lucr"]);
      $this -> db -> set("data_prec_incep_lucr", $aData["data_prec_incep_lucr"]);
      $this -> db -> update("lucrare_date");
   }

   function addSolicitareRT($aData) {
      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> set("data_solicitare_rt", $aData["data_solicitare_rt"]);
      $this -> db -> update("lucrare_date");
   }

   function addMotivIntarziere($aData) {
      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> set("motiv_intarziere_lucrare", $aData["motiv_intarziere_lucrare"]);
      $this -> db -> update("lucrare");
   }

   function addDataObtinereCU($aData) {

      if ($aData["necesita_avize"] != 1) {
         $aData["avize"] = array();
      }
      $aNomAvize = $this -> db -> get("nom_avize") -> result_array();
      foreach ($aNomAvize as $key => $value) {
         $aAvize[$value["aviz_id"]] = $value["aviz_nume"];
      }

      $aInsert = array("data_obtinere_cu" => $aData["data_obtinere_cu"]);
      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> update("lucrare_date", $aInsert);

      $aInsert = array(
         //"rasp_neces_ref_pavaj" => $aData["rasp_neces_ref_pavaj"],
         //"nume_firma_ref_pavaj" => $aData["nume_firma_ref_pavaj"],
         "rasp_neces_valid_dt" => $aData["rasp_neces_valid_dt"]);
      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> update("lucrare_raspunsuri", $aInsert);

      $aInsert = array("necesita_avize" => $aData['necesita_avize']);
      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> update("lucrare_avize", $aInsert);

      //resetare denumire avize 21-25
      for ($i = 21; $i <= 25; $i++) {
         $this -> db -> set("cuavz{$i}_denumire", null);
         $this -> db -> where("crc", $aData["crc"]);
         $this -> db -> update("lucrare_avize");
      }

      //resetare  avize 1-25
      for ($i = 1; $i <= 25; $i++) {
         $index = str_pad($i, 2, "0", STR_PAD_LEFT);
         $this -> db -> set("cuavz{$index}", null);
         $this -> db -> where("crc", $aData["crc"]);
         $this -> db -> update("lucrare_avize");
      }

      if (count($aData["avize"]) > 0) {

         foreach ($aData["avize"] as $key => $aAviz) {
            $sAvizBifa = null;
            if ($aAviz["aviz_sel"] == TRUE) {
               $sAvizBifa = "X";
            }

            $iAviz = $aAviz["aviz_id"];
            $this -> db -> set(strtolower($iAviz), $sAvizBifa);
            $this -> db -> where("crc", $aData["crc"]);
            $this -> db -> update("lucrare_avize");

            //actualizare pentru avizele 21-25 noile denumiri daca e cazul
            for ($i = 21; $i <= 25; $i++) {
               if (($aAviz["aviz_id"] == "CUAVZ" . $i) && ($aAviz["aviz_sel"] == 1)) {
                  $this -> db -> set("cuavz{$i}_denumire", $aAviz["aviz_nume"]);
                  $this -> db -> where("crc", $aData["crc"]);
                  $this -> db -> update("lucrare_avize");
               }
            }

         }

      }
      //extragere avize
   }

   /**
    * returnare avize din nomenclator cu salvarile din lucrare
    */
   function getAvizeComplet($sCrc) {
      $aAvizeNomenclator = $this -> db -> get("nom_avize") -> result_array();
      $totalCount = $this -> db -> count_all_results("nom_avize");

      $this -> db -> where("crc", $sCrc);
      $aAvizeLucrare = $this -> db -> get("lucrare_avize") -> row_array();

      foreach ($aAvizeNomenclator as $key => $value) {
         $aAvizeNomenclator[$key]["aviz_sel"] = 0;
         if ($aAvizeLucrare[strtolower($value["aviz_id"])] != "") {
            $aAvizeNomenclator[$key]["aviz_sel"] = 1;
         }
         //completare denumire avize 21-25 pe resultat
         for ($i = 21; $i <= 25; $i++) {
            if (($value["aviz_id"] == "CUAVZ" . $i) && ($aAvizeLucrare["cuavz{$i}_denumire"] != "")) {
               $aAvizeNomenclator[$key]["aviz_nume"] = $aAvizeLucrare["cuavz{$i}_denumire"];
            }
         }
      }

      $data = array(
         'totalCount' => 0,
         'data' => $aAvizeNomenclator
      );
      return $data;
   }

   /**
    * returnare avize din nomenclator cu salvarile din lucrare
    */
   function getAvizeSelectate($sCrc) {
      $aAvizeNomenclator = $this -> db -> get("nom_avize") -> result_array();
      //$totalCount = $this -> db -> count_all_results("nom_avize");
      // e($aAvizeNomenclator);

      $this -> db -> where("crc", $sCrc);
      $aAvizeLucrare = $this -> db -> get("lucrare_avize") -> row_array();
      //e($aAvizeLucrare);

      $this -> db -> where("crc", $sCrc);
      $aDataAvize = $this -> db -> get("lucrare_date") -> row_array();
      // e($aDataAvize);

      $aAvizeObtinere = $this -> getDocumenteLucrare($sCrc);

      foreach ($aAvizeNomenclator as $key => $value) {
         //completare denumire avize 21-25 pe resultat
         for ($i = 21; $i <= 25; $i++) {
            if (($value["aviz_id"] == "CUAVZ" . $i) && ($aAvizeLucrare["cuavz{$i}_denumire"] != "")) {
               $aAvizeNomenclator[$key]["aviz_nume"] = $aAvizeLucrare["cuavz{$i}_denumire"];
            }
         }

         if ($aAvizeLucrare[strtolower($value["aviz_id"])] != "X") {
            unset($aAvizeNomenclator[$key]);
         }
         else {
            $aAvizeNomenclator[$key]["aviz_data_depunere"] = $aDataAvize["data_dep_" . strtolower($value["aviz_id"])];
            $aAvizeNomenclator[$key]["aviz_data_obtinere"] = $aDataAvize["data_obt_" . strtolower($value["aviz_id"])];

            $aAvizeNomenclator[$key]["document_obtinere"] = 0;
            foreach ($aAvizeObtinere as $key1 => $value1) {
               if (strtolower($value["aviz_id"]) == strtolower($value1["document_tip"])) {
                  $aAvizeNomenclator[$key]["document_obtinere"] = 1;
               }
            }
         }

      }

      $data = array(
         'totalCount' => 0,
         'data' => array_merge($aAvizeNomenclator, array())
      );
      return $data;
   }

   function addDepunereAvizePrimarie($aData) {
      $aAvize = $aData["avize"];
      $i = 0;
      foreach ($aAvize as $key => $value) {
         //la editare crc se trimit si documentele AC, CU si AS care trebuiesc ignorate
         if (stripos($value["aviz_id"], "cuavz") === FALSE) {
            continue;
         }
         $aDataDepunere = ($value["aviz_data_depunere"] != "") ? $value["aviz_data_depunere"] : null;
         if ($aDataDepunere != "") {
            $i++;
         }
         $this -> db -> set("data_dep_" . strtolower($value["aviz_id"]), $aDataDepunere);
         $this -> db -> where("crc", $aData["crc"]);
         $this -> db -> update("lucrare_date");
      }

      foreach ($aAvize as $key => $value) {
         //la editare crc se trimit si documentele AC, CU si AS care trebuiesc ignorate
         if (stripos($value["aviz_id"], "cuavz") === FALSE) {
            continue;
         }
         $aDataObtinere = ($value["aviz_data_obtinere"] != "") ? $value["aviz_data_obtinere"] : null;
         $this -> db -> set("data_obt_" . strtolower($value["aviz_id"]), $aDataObtinere);
         $this -> db -> where("crc", $aData["crc"]);
         $this -> db -> update("lucrare_date");
      }

      //la editare lucrare se trimit aici si modificarile de date CU, AC si AS;
      foreach ($aAvize as $key => $value) {
         $aDataObtinere = ($value["aviz_data_obtinere"] != "") ? $value["aviz_data_obtinere"] : null;
         $aDataDepunere = ($value["aviz_data_depunere"] != "") ? $value["aviz_data_depunere"] : null;

         if ($value["aviz_id"] == "document_obtinere_CU") {
            $this -> db -> set("data_depunere_cu", $aDataDepunere);
            $this -> db -> set("data_obtinere_cu", $aDataObtinere);
            $this -> db -> where("crc", $aData["crc"]);
            $this -> db -> update("lucrare_date");
         }
         if ($value["aviz_id"] == "document_obtinere_AC") {
            $this -> db -> set("data_depunere_ac", $aDataDepunere);
            $this -> db -> set("data_obtinere_ac", $aDataObtinere);
            $this -> db -> where("crc", $aData["crc"]);
            $this -> db -> update("lucrare_date");
         }
         if ($value["aviz_id"] == "document_obtinere_AS") {
            $this -> db -> set("data_depunere_as", $aDataDepunere);
            $this -> db -> set("data_obtinere_as", $aDataObtinere);
            $this -> db -> where("crc", $aData["crc"]);
            $this -> db -> update("lucrare_date");
         }

      }

      if (count($aAvize) == $i) {
         return TRUE;
      }
      return FALSE;
   }

   function addObtinereAvizePrimarie($aData) {
      $aAvize = $aData["avize"];
      $i = 0;
      foreach ($aAvize as $key => $value) {
         $aDataObtinere = ($value["aviz_data_obtinere"] != "") ? $value["aviz_data_obtinere"] : null;
         if ($aDataObtinere != "") {
            $i++;
         }
         $this -> db -> set("data_obt_" . strtolower($value["aviz_id"]), $aDataObtinere);
         $this -> db -> where("crc", $aData["crc"]);
         $this -> db -> update("lucrare_date");
      }
      return TRUE;
   }

   /**
    * returneaza ultimul raport RABT activ pe o anumita actiune;
    */
   function getRabtActiv($sCrc, $sActiune) {

      $sActiune = str_replace("RABT", "", $sActiune);
      $sActiune = str_pad($sActiune, 2, "0", STR_PAD_LEFT);
      $sActiune = "RABT" . $sActiune;

      $this -> db -> select("lucrare_rabt.*,nom_rabt_tip.rabt_tip_nume ", false);
      $this -> db -> where("crc", $sCrc);
      $this -> db -> where("motiv_pozitie_rabt", $sActiune);
      $this -> db -> where("rabt_obiectiuni", 1);
      $this -> db -> join("nom_rabt_tip", "nom_rabt_tip.rabt_tip_id= lucrare_rabt.motiv_pozitie_rabt", "left");
      $aQuery = $this -> db -> get("lucrare_rabt");

      if ($aQuery -> num_rows() == 0) {
         return FALSE;
      }
      return $aQuery -> row_array();
   }

   /**
    * returneaza ultimul raport RABT activ pe o anumita actiune;
    */
   function getRabtUltim($sCrc, $sActiune) {

      $sActiune = str_replace("RABT", "", $sActiune);
      $sActiune = str_pad($sActiune, 2, "0", STR_PAD_LEFT);
      $sActiune = "RABT" . $sActiune;

      $this -> db -> where("crc", $sCrc);
      $this -> db -> where("motiv_pozitie_rabt", $sActiune);
      $this -> db -> order_by("rabt_id desc");
      $aQuery = $this -> db -> get("lucrare_rabt");
      return $aQuery -> row_array();
   }

   /**
    * returneaza raport de RABT ce trebuie transmis catre SAP;
    */
   function getRabtDeTransmis($sCrc, $iRabt) {
      $this -> db -> where("crc", $sCrc);
      $this -> db -> where("rabt_id", $iRabt);
      $this -> db -> where("obiectiuni_rabt_temp is not null", null, false);
      $aQuery = $this -> db -> get("lucrare_rabt");

      if ($aQuery -> num_rows() == 0) {
         return FALSE;
      }
      return $aQuery -> row_array();
   }

   /**
    * returneaza toate rapoartele de pe un crc
    */
   function getRabtTotal($sCrc) {
      $this -> db -> start_cache();
      $this -> db -> where("crc", $sCrc);
      $this -> db -> join("nom_rabt_tip", "nom_rabt_tip.rabt_tip_id = lucrare_rabt.motiv_pozitie_rabt", "left");
      $this -> db -> order_by("rabt_id desc");
      $aQuery = $this -> db -> get("lucrare_rabt");
      $totalCount = $this -> db -> count_all_results("lucrare_rabt");
      $this -> db -> flush_cache();
      $this -> db -> stop_cache();
      $aData = $aQuery -> result_array();

      $data = array(
         'totalCount' => $totalCount,
         'data' => $aData
      );
      return $data;
   }

   function getRabtCom($sCrc, $iRabt) {
      $sSort = (isset($aData["sort"]) && $aData["sort"] != "") ? $aData["sort"] : "rabt_ist_data";
      $sSir = (isset($aData["dir"]) && $aData["dir"] != "") ? $aData["dir"] : "desc";
      $sStart = (isset($aData["start"]) && $aData["start"] != "") ? $aData["start"] : "0";
      $sLimit = (isset($aData["limit"]) && $aData["limit"] != "") ? $aData["limit"] : "500";

      $this -> db -> start_cache();
      $this -> db -> order_by($sSort, $sSir);
      $this -> db -> limit($sLimit, $sStart);
      $this -> db -> select("lucrare_rabt_com.*, user.user_alias", false);
      $this -> db -> join("user", "user.user_id=lucrare_rabt_com.rabt_ist_user", "left");
      $this -> db -> where("crc", $sCrc);
      $this -> db -> where("rabt_id", $iRabt);
      $query = $this -> db -> get("lucrare_rabt_com");
      $totalCount = $this -> db -> count_all_results("lucrare_rabt_com");
      $aData = $query -> result_array();
      $this -> db -> flush_cache();
      $this -> db -> stop_cache();

      $data = array(
         'totalCount' => $totalCount,
         'data' => $aData
      );
      return $data;
   }

   function addRaportRabtRaspunsAg($iRabt, $aLucrare) {
      $aUserDetails = $this -> auth -> getUserDetails();
      $this -> db -> where("rabt_id", $iRabt);
      $this -> db -> set("rasp_ob_rabt", $aLucrare["rasp_ob_rabt"]);
      $this -> db -> set("data_remediere", $aLucrare["data_remediere"]);
      $this -> db -> update("lucrare_rabt");

      $this -> db -> insert("lucrare_rabt_com", array(
         "rabt_id" => $iRabt,
         "crc" => $aLucrare["crc"],
         "raspuns_rabt" => $aLucrare["rasp_ob_rabt"],
         "rabt_ist_tip" => CONSTANTS::OP_RAPORT_RABT_RASPUNS_RESP,
         "rabt_ist_user" => null
      ));
      return TRUE;
   }

   /**
    * Creare pozitie rabt noua; se inactiveaza restul de pozitii de acelasi motiv
    */
   function addRaportRabt($aLucrare) {

      $aLucrare["rabt_status"] = "nou";

      $this -> db -> set("rabt_obiectiuni", 0);
      $this -> db -> set("rabt_status", "inchis");
      $this -> db -> where("motiv_pozitie_rabt", $aLucrare["motiv_pozitie_rabt"]);
      $this -> db -> where("crc", $aLucrare["crc"]);
      $this -> db -> update("lucrare_rabt");

      //creare pozitie noua
      $this -> db -> where("rabt_tip_id", $aLucrare["motiv_pozitie_rabt"]);
      $bObiectiuni = $this -> db -> get("nom_rabt_tip") -> row_array();

      $aLucrare["rabt_obiectiuni"] = ($bObiectiuni["rabt_tip_are_obiectiuni"] == 1) ? 1 : 0;
      $this -> db -> insert("lucrare_rabt", $aLucrare);
   }

   /*
    * Functie de inchidere raporte RABT active pentru un crc dat; se inchide doar raportul de abatere asociat actiunii ; vezi nomenclatorul nom_rabt_tip
    */
   function closeRaportRabt($sCrc, $sActiune, $iCron) {

      $aPozitii = array();

      $this -> db -> where("rabt_actiune_inchidere", $sActiune);
      $aQuery = $this -> db -> get("nom_rabt_tip");

      if ($aQuery -> num_rows() > 0) {

         foreach ($aQuery->result_array() as $key => $value) {
            $aPozitii[] = $value["rabt_tip_id"];
         }

         $this -> db -> set("rabt_obiectiuni", 0);
         $this -> db -> set("rabt_status", "inchis");
         $this -> db -> set("cron_id", $iCron);
         $this -> db -> set("rabt_data_inchidere", date("Y-m-d H:i:s"));
         $this -> db -> where("crc", $sCrc);
         $this -> db -> where('rabt_status', "nou");
         $this -> db -> where_in("motiv_pozitie_rabt", $aPozitii);
         $this -> db -> update("lucrare_rabt");
      }
      return TRUE;
   }

   function addFirmaNoua($aData) {
      //resetare constructor
      $this -> db -> set("constructor", $aData["constructor"]);
      $this -> db -> where("crc", $aData["crc"]);
      $this -> db -> update('lucrare');
   }

   function getSarbatori() {
      $aData = array();
      $aSarbatori = $this -> db -> get("nom_sarbatori") -> result_array();
      foreach ($aSarbatori as $key => $value) {
         $aData[] = $value["sarb_data"];
      }
      return $aData;
   }

   function getJudet() {
      $aData = $aJudet = array();

      $aRoles = $this -> session -> userdata("user_roles");
      $aUserDetails = $this -> auth -> getUserDetails();

      if (in_array("constructor", $aRoles)) {
         $this -> db -> select(' GROUP_CONCAT(DISTINCT(judet_loc_consum)) as judet ');
         $this -> db -> where('constructor', $aUserDetails["partener_id"]);
         $aJudet = $this -> db -> get("lucrare") -> row_array();

         $aJudet = explode(",", $aJudet["judet"]);

      }
      if (count($aJudet) > 0) {
         $this -> db -> where_in('jud_id', $aJudet);
      }

      $aJudete = $this -> db -> get("nom_judete") -> result_array();
      $aData = array(
         'totalCount' => 100,
         'data' => $aJudete
      );
      return $aData;
   }

   function getResponsabilAG($aData) {
      $aResponse = array();
      if (isset($aData['query']) && ($aData['query'] != "")) {
         $this -> db -> like("responsabil_ag_nume", $aData['query']);
      }
      $this -> db -> select("DISTINCT(responsabil_ag) as responsabil_ag, responsabil_ag_nume", false);
      $aResponsabil = $this -> db -> get("lucrare") -> result_array();
      $aResponse = array(
         'totalCount' => 1000,
         'data' => $aResponsabil
      );
      return $aResponse;
   }

   function getDocumenteLucrare($sCrc) {
      $this -> db -> select("document_id, document_tip");
      $this -> db -> where("crc", $sCrc);
      return $aAvizeObtinere = $this -> db -> get("lucrare_documente") -> result_array();
   }

   function getMotivIntarziere($aData) {
      $this -> db -> order_by("motiv_intarziere_nume", 'ASC');
      $aQuery = $this -> db -> get("nom_motiv_intarziere") -> result_array();

      $data = array(
         'totalCount' => 100,
         'data' => $aQuery
      );
      return $data;
   }

   function getStatus($aData) {

      //creare prefix tabel de status
      $sStatus = 1;
      if ($aData["status"] == 2) {
         $sStatus = 2;
      }

      $this -> db -> order_by("status_" . $sStatus . "_nume", 'ASC');
      $aQuery = $this -> db -> get("nom_status_" . $sStatus) -> result_array();

      $data = array(
         'totalCount' => 100,
         'data' => $aQuery
      );
      return $data;
   }

   function getMotivComanda($aData) {
      $this -> db -> order_by("motiv_comanda_nume", 'ASC');
      $this -> db -> select("motiv_comanda_id, CONCAT(motiv_comanda_id, ' ', motiv_comanda_nume) as motiv_comanda_nume  ", false);
      $aQuery = $this -> db -> get("nom_motiv_comanda") -> result_array();

      $data = array(
         'totalCount' => 100,
         'data' => $aQuery
      );
      return $data;
   }

   function getMotivRespingere($aData) {

      if (isset($aData["query"]) && $aData["query"] != "") {
         $this -> db -> like("motiv_nume", $aData["query"]);
      }

      $this -> db -> order_by("motiv_nume", 'ASC');
      $aQuery = $this -> db -> get("nom_motiv_respingere") -> result_array();

      $data = array(
         'totalCount' => 100,
         'data' => $aQuery
      );
      return $data;
   }

   function getAngajat($aData) {

      $aResponse = array();
      $DB1 = $this -> load -> database('hr', TRUE);

      $DB1 -> select("ID_PERSONAL as marca, CONCAT(NUME,' ', PRENUME) as nume", false);
      $DB1 -> limit(100);
      $DB1 -> order_by(" NUME ASC, PRENUME ASC");
      $DB1 -> where("ID_COMPANIE", 1000);
      if (isset($aData['query']) && ($aData['query'] != "")) {
         $sQuery = $this -> db -> escape($aData['query']);
         $sWhere = " (NUME REGEXP {$sQuery} OR PRENUME REGEXP {$sQuery})";
         $DB1 -> where($sWhere, null, false);
      }
      $aResponsabil = $DB1 -> get("PERSONAL") -> result_array();
      //e($DB1 -> last_query());
      $aResponse = array(
         'totalCount' => 1000,
         'data' => $aResponsabil
      );
      return $aResponse;

   }

   function getAvizareValidare($sCrc) {
      $this -> db -> select("ist_avizare.*, nom_actiune.actiune_descriere, motiv_nume");
      $this -> db -> join("nom_actiune", 'nom_actiune.actiune_cod=ist_avizare.actiune_cod', "inner");
      $this -> db -> join("nom_motiv_respingere", 'nom_motiv_respingere.motiv_id=ist_avizare.ist_avizare_motiv_respingere', "left");
      $this -> db -> where("crc", $sCrc);
      $this -> db -> order_by("ist_avizare_id", 'desc');
      $aData = $this -> db -> get("ist_avizare") -> result_array();

      $aResponse = array(
         'totalCount' => 1000,
         'data' => $aData
      );
      return $aResponse;
   }

   function getLockedSalvare($sCrc) {
      $this -> db -> select("user_alias,crc,locked_date");
      $this -> db -> where("crc", $sCrc);
      $this -> db -> where("locked", 1);
      $this -> db -> join("user", "user.user_id=lucrare_lock.user_id", "left");
      $aResult = $this -> db -> get("lucrare_lock");
      if ($aResult -> num_rows() > 0) {
         return $aResult -> row_array();
      }
      return FALSE;
   }

}

<?

//obiect idoc cu mapare pe campuri de array
function getMapareIdocCron($aLucrare, $aActiune, $aRabt) {

	switch ($aActiune) {
		case CONSTANTS::ACT_DATA_DEPUNERE_CU :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"           => $aLucrare["crc"],
				"ACTIUNE"       => $aActiune,
				"Z1ISU_ML_DATE" => array("DATA_DEP_CU" => ($aLucrare["data_depunere_cu"] == "") ? "99990101" : $aLucrare["data_depunere_cu"])
			));
			break;

		case CONSTANTS::ACT_DATA_OBTINERE_CU :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"                 => $aLucrare["crc"],
				"ACTIUNE"             => $aActiune,
				"Z1ISU_ML_DATE"       => array("DATA_OBTINERE_CU" => $aLucrare["data_obtinere_cu"]),
				"Z1ISU_ML_RASPUNSURI" => array(
					//"RASP_NECES_REF_PAVAJ" => $aLucrare["rasp_neces_ref_pavaj"],
					//"NUME_FIRMA_REF_PAVAJ" => $aLucrare["nume_firma_ref_pavaj"],
					"RASP_NECES_VALID_DT" => $aLucrare["rasp_neces_valid_dt"]),
				"Z1ISU_ML_AVIZE"      => array(
					"CUAVZ01"     => $aLucrare['cuavz01'],
					"CUAVZ02"     => $aLucrare['cuavz02'],
					"CUAVZ03"     => $aLucrare['cuavz03'],
					"CUAVZ04"     => $aLucrare['cuavz04'],
					"CUAVZ05"     => $aLucrare['cuavz05'],
					"CUAVZ06"     => $aLucrare['cuavz06'],
					"CUAVZ07"     => $aLucrare['cuavz07'],
					"CUAVZ08"     => $aLucrare['cuavz08'],
					"CUAVZ09"     => $aLucrare['cuavz09'],
					"CUAVZ10"     => $aLucrare['cuavz10'],
					"CUAVZ11"     => $aLucrare['cuavz11'],
					"CUAVZ12"     => $aLucrare['cuavz12'],
					"CUAVZ13"     => $aLucrare['cuavz13'],
					"CUAVZ14"     => $aLucrare['cuavz14'],
					"CUAVZ15"     => $aLucrare['cuavz15'],
					"CUAVZ16"     => $aLucrare['cuavz16'],
					"CUAVZ17"     => $aLucrare['cuavz17'],
					"CUAVZ18"     => $aLucrare['cuavz18'],
					"CUAVZ19"     => $aLucrare['cuavz19'],
					"CUAVZ20"     => $aLucrare['cuavz20'],
					"CUAVZ21"     => $aLucrare['cuavz21'],
					"CUAVZ22"     => $aLucrare['cuavz22'],
					"CUAVZ23"     => $aLucrare['cuavz23'],
					"CUAVZ24"     => $aLucrare['cuavz24'],
					"CUAVZ25"     => $aLucrare['cuavz25'],
					"DEN_CUAVZ21" => $aLucrare['cuavz21_denumire'],
					"DEN_CUAVZ22" => $aLucrare['cuavz22_denumire'],
					"DEN_CUAVZ23" => $aLucrare['cuavz23_denumire'],
					"DEN_CUAVZ24" => $aLucrare['cuavz24_denumire'],
					"DEN_CUAVZ25" => $aLucrare['cuavz25_denumire']
				)
			));

		case  CONSTANTS::ACT_DEPUNERE_AVIZE_PRIMARIE :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"           => $aLucrare["crc"],
				"ACTIUNE"       => $aActiune,
				"Z1ISU_ML_DATE" => array(
					"DATA_DEP_CUAVZ01" => $aLucrare['data_dep_cuavz01'],
					"DATA_DEP_CUAVZ02" => $aLucrare['data_dep_cuavz02'],
					"DATA_DEP_CUAVZ03" => $aLucrare['data_dep_cuavz03'],
					"DATA_DEP_CUAVZ04" => $aLucrare['data_dep_cuavz04'],
					"DATA_DEP_CUAVZ05" => $aLucrare['data_dep_cuavz05'],
					"DATA_DEP_CUAVZ06" => $aLucrare['data_dep_cuavz06'],
					"DATA_DEP_CUAVZ07" => $aLucrare['data_dep_cuavz07'],
					"DATA_DEP_CUAVZ08" => $aLucrare['data_dep_cuavz08'],
					"DATA_DEP_CUAVZ09" => $aLucrare['data_dep_cuavz09'],
					"DATA_DEP_CUAVZ10" => $aLucrare['data_dep_cuavz10'],
					"DATA_DEP_CUAVZ11" => $aLucrare['data_dep_cuavz11'],
					"DATA_DEP_CUAVZ12" => $aLucrare['data_dep_cuavz12'],
					"DATA_DEP_CUAVZ13" => $aLucrare['data_dep_cuavz13'],
					"DATA_DEP_CUAVZ14" => $aLucrare['data_dep_cuavz14'],
					"DATA_DEP_CUAVZ15" => $aLucrare['data_dep_cuavz15'],
					"DATA_DEP_CUAVZ16" => $aLucrare['data_dep_cuavz16'],
					"DATA_DEP_CUAVZ17" => $aLucrare['data_dep_cuavz17'],
					"DATA_DEP_CUAVZ18" => $aLucrare['data_dep_cuavz18'],
					"DATA_DEP_CUAVZ19" => $aLucrare['data_dep_cuavz19'],
					"DATA_DEP_CUAVZ20" => $aLucrare['data_dep_cuavz20'],
					"DATA_DEP_CUAVZ21" => $aLucrare['data_dep_cuavz21'],
					"DATA_DEP_CUAVZ22" => $aLucrare['data_dep_cuavz22'],
					"DATA_DEP_CUAVZ23" => $aLucrare['data_dep_cuavz23'],
					"DATA_DEP_CUAVZ24" => $aLucrare['data_dep_cuavz24'],
					"DATA_DEP_CUAVZ25" => $aLucrare['data_dep_cuavz25']
				)
			));
			break;

		case  CONSTANTS::ACT_OBTINERE_AVIZE_PRIMARIE :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"           => $aLucrare["crc"],
				"ACTIUNE"       => $aActiune,
				"Z1ISU_ML_DATE" => array(
					"DATA_OBT_CUAVZ01" => $aLucrare['data_obt_cuavz01'],
					"DATA_OBT_CUAVZ02" => $aLucrare['data_obt_cuavz02'],
					"DATA_OBT_CUAVZ03" => $aLucrare['data_obt_cuavz03'],
					"DATA_OBT_CUAVZ04" => $aLucrare['data_obt_cuavz04'],
					"DATA_OBT_CUAVZ05" => $aLucrare['data_obt_cuavz05'],
					"DATA_OBT_CUAVZ06" => $aLucrare['data_obt_cuavz06'],
					"DATA_OBT_CUAVZ07" => $aLucrare['data_obt_cuavz07'],
					"DATA_OBT_CUAVZ08" => $aLucrare['data_obt_cuavz08'],
					"DATA_OBT_CUAVZ09" => $aLucrare['data_obt_cuavz09'],
					"DATA_OBT_CUAVZ10" => $aLucrare['data_obt_cuavz10'],
					"DATA_OBT_CUAVZ11" => $aLucrare['data_obt_cuavz11'],
					"DATA_OBT_CUAVZ12" => $aLucrare['data_obt_cuavz12'],
					"DATA_OBT_CUAVZ13" => $aLucrare['data_obt_cuavz13'],
					"DATA_OBT_CUAVZ14" => $aLucrare['data_obt_cuavz14'],
					"DATA_OBT_CUAVZ15" => $aLucrare['data_obt_cuavz15'],
					"DATA_OBT_CUAVZ16" => $aLucrare['data_obt_cuavz16'],
					"DATA_OBT_CUAVZ17" => $aLucrare['data_obt_cuavz17'],
					"DATA_OBT_CUAVZ18" => $aLucrare['data_obt_cuavz18'],
					"DATA_OBT_CUAVZ19" => $aLucrare['data_obt_cuavz19'],
					"DATA_OBT_CUAVZ20" => $aLucrare['data_obt_cuavz20'],
					"DATA_OBT_CUAVZ21" => $aLucrare['data_obt_cuavz21'],
					"DATA_OBT_CUAVZ22" => $aLucrare['data_obt_cuavz22'],
					"DATA_OBT_CUAVZ23" => $aLucrare['data_obt_cuavz23'],
					"DATA_OBT_CUAVZ24" => $aLucrare['data_obt_cuavz24'],
					"DATA_OBT_CUAVZ25" => $aLucrare['data_obt_cuavz25'],
				)
			));
			break;

		case  CONSTANTS::ACT_DATA_DEPUNERE_AC :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"           => $aLucrare["crc"],
				"ACTIUNE"       => $aActiune,
				"Z1ISU_ML_DATE" => array("DATA_DEPUNERE_AC" => $aLucrare["data_depunere_ac"])
			));
			break;
		case  CONSTANTS::ACT_DATA_OBTINERE_AC :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"                 => $aLucrare["crc"],
				"ACTIUNE"             => $aActiune,
				"Z1ISU_ML_DATE"       => array("DATA_OBTINERE_AC" => $aLucrare["data_obtinere_ac"]),
				"Z1ISU_ML_RASPUNSURI" => array(
					"RASP_NECES_REF_PAVAJ" => $aLucrare["rasp_neces_ref_pavaj"],
					"NUME_FIRMA_REF_PAVAJ" => $aLucrare["nume_firma_ref_pavaj"],
					"RASP_NECES_VALID_AS"  => $aLucrare["rasp_neces_valid_as"]
				),
			));
			break;

		case  CONSTANTS::ACT_DATA_DEPUNERE_AS :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"           => $aLucrare["crc"],
				"ACTIUNE"       => $aActiune,
				"Z1ISU_ML_DATE" => array("DATA_DEPUNERE_AS" => $aLucrare["data_depunere_as"])
			));
			break;
		case  CONSTANTS::ACT_DATA_OBTINERE_AS :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"           => $aLucrare["crc"],
				"ACTIUNE"       => $aActiune,
				"Z1ISU_ML_DATE" => array("DATA_OBTINERE_AS" => $aLucrare["data_obtinere_as"])
			));
			break;

		case  CONSTANTS::ACT_INCEPERE_LUCRARE :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"           => $aLucrare["crc"],
				"ACTIUNE"       => $aActiune,
				"Z1ISU_ML_DATE" => array(
					"DATA_SOL_INCEP_LUCR"  => $aLucrare["data_sol_incep_lucr"],
					"DATA_PREC_INCEP_LUCR" => $aLucrare["data_prec_incep_lucr"]
				)
			));
			break;
		case  CONSTANTS::ACT_SOLICITARE_RT :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"           => $aLucrare["crc"],
				"ACTIUNE"       => $aActiune,
				"Z1ISU_ML_DATE" => array("DATA_SOLICITARE_RT" => $aLucrare["data_solicitare_rt"])
			));
			break;

		case  CONSTANTS::ACT_RASPUNS_RABT :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"                       => $aLucrare["crc"],
				"ACTIUNE"                   => $aActiune,
				"Z1ISU_ML_DETALII_ALTE_DOC" => array("OBIECTIUNI_RABT" => $aRabt["obiectiuni_rabt_temp"], "MOTIV_POZITIE_RABT" => $aRabt["motiv_pozitie_rabt"],),
			));
			break;

		case  CONSTANTS::ACT_MOTIV_INTARZIERE :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"                  => $aLucrare["crc"],
				"ACTIUNE"              => $aActiune,
				"Z1ISU_ML_DETALII_CRC" => array("MOTIV_INTARZIERE_LUC" => $aLucrare["motiv_intarziere_lucrare"])
			));
			break;

		default :
			return array("Z1ISU_ML_HEADER" => array(
				"CRC"                       => $aLucrare["crc"],
				"ACTIUNE"                   => $aActiune,
				"Z1ISU_ML_DETALII_ALTE_DOC" => array(
					/* "ID_AVZ"=>  "id_avz",
					   "ID_ATP"=>  "id_atp",
					   "ID_ZAVZ"=>  "id_zavz",
					   "ID_ZATP"=>  "id_zatp",
					   "ID_ZCU"=>  "id_zcu",
					   "STATUS_ULF"=>  "status_ulf",
					   "MOTIV_RESP_ULF"=>  "motiv_resp_ulf",
					   "DATA_RESP_ULF"=>  "data_resp_ulf",
					   "STATUS_CRI"=>  "status_cri",
					   "MOTIV_RESP_CRI"=>  "motiv_resp_cri",
					   "DATA_RESP_CRI"=>  "data_resp_cri",
					   "RASP_OB_RABT" =>  "rasp_ob_rabt",
					   "MOTIV_POZITIE_RABT" => $aRabt["motiv_pozitie_rabt"],*/
					"OBIECTIUNI_RABT" => $aRabt["obiectiuni_rabt_temp"],
					/*"DATA_REMEDIERE" => dateMysqlToSap($aRabt["data_remediere"]),
					   /*"STATUS_ATP"=>  "status_atp",
					   "MOTIV_RESP_ATP"=>  "motiv_resp_atp",
					   "STATUS_ZATP"=>  "status_zatp",
					   "MOTIV_RESP_ZATP"=>  "motiv_resp_zatp",  */
				),
				"Z1ISU_ML_DATE"             => array(
					"DATA_DEP_CU"          => $aLucrare["data_depunere_cu"],
					"DATA_OBTINERE_CU"     => $aLucrare["data_obtinere_cu"],
					"DATA_DEPUNERE_AC"     => $aLucrare["data_depunere_ac"],
					"DATA_OBTINERE_AC"     => $aLucrare["data_obtinere_ac"],
					"DATA_DEPUNERE_AS"     => $aLucrare["data_depunere_as"],
					"DATA_OBTINERE_AS"     => $aLucrare["data_obtinere_as"],
					"DATA_SOL_INCEP_LUCR"  => $aLucrare["data_sol_incep_lucr"],
					"DATA_PREC_INCEP_LUCR" => $aLucrare["data_prec_incep_lucr"],
					"DATA_REALA_EXEC_LUCR" => $aLucrare["data_reala_exec_lucr"],
					"DATA_SOLICITARE_RT"   => $aLucrare["data_solicitare_rt"],

					"DATA_DEP_CUAVZ01"     => $aLucrare['data_dep_cuavz01'],
					"DATA_DEP_CUAVZ02"     => $aLucrare['data_dep_cuavz02'],
					"DATA_DEP_CUAVZ03"     => $aLucrare['data_dep_cuavz03'],
					"DATA_DEP_CUAVZ04"     => $aLucrare['data_dep_cuavz04'],
					"DATA_DEP_CUAVZ05"     => $aLucrare['data_dep_cuavz05'],
					"DATA_DEP_CUAVZ06"     => $aLucrare['data_dep_cuavz06'],
					"DATA_DEP_CUAVZ07"     => $aLucrare['data_dep_cuavz07'],
					"DATA_DEP_CUAVZ08"     => $aLucrare['data_dep_cuavz08'],
					"DATA_DEP_CUAVZ09"     => $aLucrare['data_dep_cuavz09'],
					"DATA_DEP_CUAVZ10"     => $aLucrare['data_dep_cuavz10'],
					"DATA_DEP_CUAVZ11"     => $aLucrare['data_dep_cuavz11'],
					"DATA_DEP_CUAVZ12"     => $aLucrare['data_dep_cuavz12'],
					"DATA_DEP_CUAVZ13"     => $aLucrare['data_dep_cuavz13'],
					"DATA_DEP_CUAVZ14"     => $aLucrare['data_dep_cuavz14'],
					"DATA_DEP_CUAVZ15"     => $aLucrare['data_dep_cuavz15'],
					"DATA_DEP_CUAVZ16"     => $aLucrare['data_dep_cuavz16'],
					"DATA_DEP_CUAVZ17"     => $aLucrare['data_dep_cuavz17'],
					"DATA_DEP_CUAVZ18"     => $aLucrare['data_dep_cuavz18'],
					"DATA_DEP_CUAVZ19"     => $aLucrare['data_dep_cuavz19'],
					"DATA_DEP_CUAVZ20"     => $aLucrare['data_dep_cuavz20'],
					"DATA_DEP_CUAVZ21"     => $aLucrare['data_dep_cuavz21'],
					"DATA_DEP_CUAVZ22"     => $aLucrare['data_dep_cuavz22'],
					"DATA_DEP_CUAVZ23"     => $aLucrare['data_dep_cuavz23'],
					"DATA_DEP_CUAVZ24"     => $aLucrare['data_dep_cuavz24'],
					"DATA_DEP_CUAVZ25"     => $aLucrare['data_dep_cuavz25'],

					"DATA_OBT_CUAVZ01"     => $aLucrare['data_obt_cuavz01'],
					"DATA_OBT_CUAVZ02"     => $aLucrare['data_obt_cuavz02'],
					"DATA_OBT_CUAVZ03"     => $aLucrare['data_obt_cuavz03'],
					"DATA_OBT_CUAVZ04"     => $aLucrare['data_obt_cuavz04'],
					"DATA_OBT_CUAVZ05"     => $aLucrare['data_obt_cuavz05'],
					"DATA_OBT_CUAVZ06"     => $aLucrare['data_obt_cuavz06'],
					"DATA_OBT_CUAVZ07"     => $aLucrare['data_obt_cuavz07'],
					"DATA_OBT_CUAVZ08"     => $aLucrare['data_obt_cuavz08'],
					"DATA_OBT_CUAVZ09"     => $aLucrare['data_obt_cuavz09'],
					"DATA_OBT_CUAVZ10"     => $aLucrare['data_obt_cuavz10'],
					"DATA_OBT_CUAVZ11"     => $aLucrare['data_obt_cuavz11'],
					"DATA_OBT_CUAVZ12"     => $aLucrare['data_obt_cuavz12'],
					"DATA_OBT_CUAVZ13"     => $aLucrare['data_obt_cuavz13'],
					"DATA_OBT_CUAVZ14"     => $aLucrare['data_obt_cuavz14'],
					"DATA_OBT_CUAVZ15"     => $aLucrare['data_obt_cuavz15'],
					"DATA_OBT_CUAVZ16"     => $aLucrare['data_obt_cuavz16'],
					"DATA_OBT_CUAVZ17"     => $aLucrare['data_obt_cuavz17'],
					"DATA_OBT_CUAVZ18"     => $aLucrare['data_obt_cuavz18'],
					"DATA_OBT_CUAVZ19"     => $aLucrare['data_obt_cuavz19'],
					"DATA_OBT_CUAVZ20"     => $aLucrare['data_obt_cuavz20'],
					"DATA_OBT_CUAVZ21"     => $aLucrare['data_obt_cuavz21'],
					"DATA_OBT_CUAVZ22"     => $aLucrare['data_obt_cuavz22'],
					"DATA_OBT_CUAVZ23"     => $aLucrare['data_obt_cuavz23'],
					"DATA_OBT_CUAVZ24"     => $aLucrare['data_obt_cuavz24'],
					"DATA_OBT_CUAVZ25"     => $aLucrare['data_obt_cuavz25'],
				),
				"Z1ISU_ML_RASPUNSURI"       => array(
					"RASP_NECES_REF_PAVAJ" => $aLucrare["rasp_neces_ref_pavaj"],
					"NUME_FIRMA_REF_PAVAJ" => $aLucrare["nume_firma_ref_pavaj"],
					"RASP_NECES_VALID_DT"  => $aLucrare["rasp_neces_valid_dt"],
					"RASP_NECES_VALID_AS"  => $aLucrare["rasp_neces_valid_as"],
				),
				"Z1ISU_ML_AVIZE"            => array(
					"CUAVZ01"     => $aLucrare['cuavz01'],
					"CUAVZ02"     => $aLucrare['cuavz02'],
					"CUAVZ03"     => $aLucrare['cuavz03'],
					"CUAVZ04"     => $aLucrare['cuavz04'],
					"CUAVZ05"     => $aLucrare['cuavz05'],
					"CUAVZ06"     => $aLucrare['cuavz06'],
					"CUAVZ07"     => $aLucrare['cuavz07'],
					"CUAVZ08"     => $aLucrare['cuavz08'],
					"CUAVZ09"     => $aLucrare['cuavz09'],
					"CUAVZ10"     => $aLucrare['cuavz10'],
					"CUAVZ11"     => $aLucrare['cuavz11'],
					"CUAVZ12"     => $aLucrare['cuavz12'],
					"CUAVZ13"     => $aLucrare['cuavz13'],
					"CUAVZ14"     => $aLucrare['cuavz14'],
					"CUAVZ15"     => $aLucrare['cuavz15'],
					"CUAVZ16"     => $aLucrare['cuavz16'],
					"CUAVZ17"     => $aLucrare['cuavz17'],
					"CUAVZ18"     => $aLucrare['cuavz18'],
					"CUAVZ19"     => $aLucrare['cuavz19'],
					"CUAVZ20"     => $aLucrare['cuavz20'],
					"CUAVZ21"     => $aLucrare['cuavz21'],
					"CUAVZ22"     => $aLucrare['cuavz22'],
					"CUAVZ23"     => $aLucrare['cuavz23'],
					"CUAVZ24"     => $aLucrare['cuavz24'],
					"CUAVZ25"     => $aLucrare['cuavz25'],
					"DEN_CUAVZ21" => $aLucrare['cuavz21_denumire'],
					"DEN_CUAVZ22" => $aLucrare['cuavz22_denumire'],
					"DEN_CUAVZ23" => $aLucrare['cuavz23_denumire'],
					"DEN_CUAVZ24" => $aLucrare['cuavz24_denumire'],
					"DEN_CUAVZ25" => $aLucrare['cuavz25_denumire']
				)
			));
			break;
	}

}

//obiect idoc cu mapare pe campuri in tabela MYSQL "lucrare"
function getMapareIdoc() {
	return array("Z1ISU_ML_HEADER" => array(
		"CRC"                       => "crc",
		"ACTIUNE"                   => "actiune",
		"EROARE"                    => "",
		"DOCREF"                    => "",
		"Z1ISU_ML_DETALII_ALTE_DOC" => array(
			"ID_AVZ"             => "id_avz",
			"ID_ATP"             => "id_atp",
			"ID_ZAVZ"            => "id_zavz",
			"ID_ZATP"            => "id_zatp",
			"ID_ZCU"             => "id_zcu",
			"STATUS_ULF"         => "status_ulf",
			"MOTIV_RESP_ULF"     => "motiv_resp_ulf",
			"DATA_RESP_ULF"      => "data_resp_ulf",
			"STATUS_CRI"         => "status_cri",
			"MOTIV_RESP_CRI"     => "motiv_resp_cri",
			"DATA_RESP_CRI"      => "data_resp_cri",
			"RASP_OB_RABT"       => "rasp_ob_rabt",
			"MOTIV_POZITIE_RABT" => "motiv_pozitie_rabt",
			"OBIECTIUNI_RABT"    => "obiectiuni_rabt",
			"DATA_REMEDIERE"     => "data_remediere",
			"STATUS_ATP"         => "status_atp",
			"MOTIV_RESP_ATP"     => "motiv_resp_atp",
			"STATUS_ZATP"        => "status_zatp",
			"MOTIV_RESP_ZATP"    => "motiv_resp_zatp",
			"DATA_RESP_ATP"      => "data_resp_atp",
			"DATA_RESP_ZATP"     => "data_resp_zatp",
			"DATA_ATP"           => "data_atp",
			"DATA_ZATP"          => "data_zatp",
			'DATA_ADMIT_CRI'     => "data_cri",
			'DATA_ADMIT_ULF'     => "data_ulf",
		),
		"Z1ISU_ML_DETALII_CRC"      => array(
			"ACA"                  => "aca",
			"COF"                  => "cof",
			"DATA_SEMN_CONTR_DGSR" => "data_semn_contr_dgsr",
			"DATA_SEMN_CLIENT"     => "data_semn_client",
			"CONSTRUCTOR"          => "constructor",
			"RESPONSABIL_AG"       => "responsabil_ag",
			"DATA_EMITERE_OL"      => "data_emitere_ol",
			"DATA_SEMNARE_OL"      => "data_semnare_ol",
			"MOTIV_INTARZIERE_LUC" => "motiv_intarziere_lucrare",
			"ID_CLIENT"            => "id_client",
			"NUME_CLIENT"          => "nume_client",
			"ADRESA"               => "adresa",
			"ID_LOC_CONSUM"        => "id_loc_consum",
			"JUDET_LOC_CONSUM"     => "judet_loc_consum",
			"ADRESA_LOC_CONSUM"    => "adresa_loc_consum",
			"GRUP_VANZARI"         => "grup_vanzari",
			"LUNGIME_BR"           => "lungime_br",
			"DIAMETRU,_BR"         => "diametru_br",
			"LUNGIME_CONDUCTA"     => "lungime_conducta",
			"DIAMETRU_CONDUCTA"    => "diametru_conducta",
			"MOTIV_COMANDA_ACA"    => "motiv_comanda_aca",
			"SUMA_CRC"             => "suma_crc",
			"SUMA_COF"             => "suma_cof",
			"STATUS_CRC_ANTET"     => "status_crc_antet",
			"STAT_CRC_POZ_INFSUPL" => "status_crc_pozitie_infsupl",
			"MOTIV_COMANDA_CRC"    => "motiv_comanda_crc",
			"DATA_REZILIERE"       => "data_reziliere",
			"DATA_TERMINARE_LUCR"  => "data_terminare_lucrare",
			"DATA_RELUARE_LUCRARE" => "data_reluare_lucrare",
		),
		"Z1ISU_ML_DATE"             => array(
			"DATA_DEP_CU"          => "data_depunere_cu",
			"DATA_OBTINERE_CU"     => "data_obtinere_cu",
			"DATA_DEPUNERE_AC"     => "data_depunere_ac",
			"DATA_OBTINERE_AC"     => "data_obtinere_ac",
			"DATA_DEPUNERE_AS"     => "data_depunere_as",
			"DATA_OBTINERE_AS"     => "data_obtinere_as",
			"DATA_SOL_INCEP_LUCR"  => "data_sol_incep_lucr",
			"DATA_PREC_INCEP_LUCR" => "data_prec_incep_lucr",
			"DATA_REALA_EXEC_LUCR" => "data_reala_exec_lucr",
			"DATA_SOLICITARE_RT"   => "data_solicitiare",

			"DATA_DEP_CUAVZ01"     => "data_dep_cuavz01",
			"DATA_DEP_CUAVZ02"     => "data_dep_cuavz02",
			"DATA_DEP_CUAVZ03"     => "data_dep_cuavz03",
			"DATA_DEP_CUAVZ04"     => "data_dep_cuavz04",
			"DATA_DEP_CUAVZ05"     => "data_dep_cuavz05",
			"DATA_DEP_CUAVZ06"     => "data_dep_cuavz06",
			"DATA_DEP_CUAVZ07"     => "data_dep_cuavz07",
			"DATA_DEP_CUAVZ08"     => "data_dep_cuavz08",
			"DATA_DEP_CUAVZ09"     => "data_dep_cuavz09",
			"DATA_DEP_CUAVZ10"     => "data_dep_cuavz10",
			"DATA_DEP_CUAVZ11"     => "data_dep_cuavz11",
			"DATA_DEP_CUAVZ12"     => "data_dep_cuavz12",
			"DATA_DEP_CUAVZ13"     => "data_dep_cuavz13",
			"DATA_DEP_CUAVZ14"     => "data_dep_cuavz14",
			"DATA_DEP_CUAVZ15"     => "data_dep_cuavz15",
			"DATA_DEP_CUAVZ16"     => "data_dep_cuavz16",
			"DATA_DEP_CUAVZ17"     => "data_dep_cuavz17",
			"DATA_DEP_CUAVZ18"     => "data_dep_cuavz18",
			"DATA_DEP_CUAVZ19"     => "data_dep_cuavz19",
			"DATA_DEP_CUAVZ20"     => "data_dep_cuavz20",
			"DATA_DEP_CUAVZ21"     => "data_dep_cuavz21",
			"DATA_DEP_CUAVZ22"     => "data_dep_cuavz22",
			"DATA_DEP_CUAVZ23"     => "data_dep_cuavz23",
			"DATA_DEP_CUAVZ24"     => "data_dep_cuavz24",
			"DATA_DEP_CUAVZ25"     => "data_dep_cuavz25",

			"DATA_OBT_CUAVZ01"     => "data_obt_cuavz01",
			"DATA_OBT_CUAVZ02"     => "data_obt_cuavz02",
			"DATA_OBT_CUAVZ03"     => "data_obt_cuavz03",
			"DATA_OBT_CUAVZ04"     => "data_obt_cuavz04",
			"DATA_OBT_CUAVZ05"     => "data_obt_cuavz05",
			"DATA_OBT_CUAVZ06"     => "data_obt_cuavz06",
			"DATA_OBT_CUAVZ07"     => "data_obt_cuavz07",
			"DATA_OBT_CUAVZ08"     => "data_obt_cuavz08",
			"DATA_OBT_CUAVZ09"     => "data_obt_cuavz09",
			"DATA_OBT_CUAVZ10"     => "data_obt_cuavz10",
			"DATA_OBT_CUAVZ11"     => "data_obt_cuavz11",
			"DATA_OBT_CUAVZ12"     => "data_obt_cuavz12",
			"DATA_OBT_CUAVZ13"     => "data_obt_cuavz13",
			"DATA_OBT_CUAVZ14"     => "data_obt_cuavz14",
			"DATA_OBT_CUAVZ15"     => "data_obt_cuavz15",
			"DATA_OBT_CUAVZ16"     => "data_obt_cuavz16",
			"DATA_OBT_CUAVZ17"     => "data_obt_cuavz17",
			"DATA_OBT_CUAVZ18"     => "data_obt_cuavz18",
			"DATA_OBT_CUAVZ19"     => "data_obt_cuavz19",
			"DATA_OBT_CUAVZ20"     => "data_obt_cuavz20",
			"DATA_OBT_CUAVZ21"     => "data_obt_cuavz21",
			"DATA_OBT_CUAVZ22"     => "data_obt_cuavz22",
			"DATA_OBT_CUAVZ23"     => "data_obt_cuavz23",
			"DATA_OBT_CUAVZ24"     => "data_obt_cuavz24",
			"DATA_OBT_CUAVZ25"     => "data_obt_cuavz25",
		),
		"Z1ISU_ML_RASPUNSURI"       => array(
			"RASP_NECES_REF_PAVAJ" => "rasp_neces_ref_pavaj",
			"NUME_FIRMA_REF_PAVAJ" => "nume_firma_ref_pavaj",
			"RASP_NECES_VALID_DT"  => "rasps_neces_valid_dt",
			"RASP_NECES_VALID_AS"  => "rasp_neces_valid_as",
		),
		"Z1ISU_ML_AVIZE"            => array(
			"CUAVZ01"     => "cuavz01",
			"CUAVZ02"     => "cuavz02",
			"CUAVZ03"     => "cuavz03",
			"CUAVZ04"     => "cuavz04",
			"CUAVZ05"     => "cuavz05",
			"CUAVZ06"     => "cuavz06",
			"CUAVZ07"     => "cuavz07",
			"CUAVZ08"     => "cuavz08",
			"CUAVZ09"     => "cuavz09",
			"CUAVZ10"     => "cuavz10",
			"CUAVZ11"     => "cuavz11",
			"CUAVZ12"     => "cuavz12",
			"CUAVZ13"     => "cuavz13",
			"CUAVZ14"     => "cuavz14",
			"CUAVZ15"     => "cuavz15",
			"CUAVZ16"     => "cuavz16",
			"CUAVZ17"     => "cuavz17",
			"CUAVZ18"     => "cuavz18",
			"CUAVZ19"     => "cuavz19",
			"CUAVZ20"     => "cuavz20",
			"CUAVZ21"     => "cuavz21",
			"CUAVZ22"     => "cuavz22",
			"CUAVZ23"     => "cuavz23",
			"CUAVZ24"     => "cuavz24",
			"CUAVZ25"     => "cuavz25",
			"DEN_CUAVZ21" => "cuavz21_denumire",
			"DEN_CUAVZ22" => "cuavz22_denumire",
			"DEN_CUAVZ23" => "cuavz23_denumire",
			"DEN_CUAVZ24" => "cuavz24_denumire",
			"DEN_CUAVZ25" => "cuavz25_denumire",
		)
	));
}

//atunci cand se ttrimit datele din Xi campurile de tip data vin sub forma Ymd si trebuiesc reformatate sub Y-m-d
function reformatComArray($aData, $tip = "xi") {
	$aSchimbaredata = array(
		"data_semn_contr_dgsr",
		"data_semn_client",
		"data_emitere_ol",
		"data_semnare_ol",
		"data_reziliere",
		"data_terminare_lucrare",
		"data_reluare_lucrare",
		"data_resp_ulf",
		'data_resp_cri',
		'data_remediere',
		"data_depunere_cu",
		"data_obtinere_cu",
		"data_depunere_ac",
		"data_obtinere_ac",
		"data_depunere_as",
		"data_obtinere_as",
		"data_sol_incep_lucr",
		"data_prec_incep_lucr",
		"data_solicitare_rt",
		"data_dep_cuavz01",
		"data_dep_cuavz02",
		"data_dep_cuavz03",
		"data_dep_cuavz04",
		"data_dep_cuavz05",
		"data_dep_cuavz06",
		"data_dep_cuavz07",
		"data_dep_cuavz08",
		"data_dep_cuavz09",
		"data_dep_cuavz10",
		"data_dep_cuavz11",
		"data_dep_cuavz12",
		"data_dep_cuavz13",
		"data_dep_cuavz14",
		"data_dep_cuavz15",
		"data_dep_cuavz16",
		"data_dep_cuavz17",
		"data_dep_cuavz18",
		"data_dep_cuavz19",
		"data_dep_cuavz20",
		"data_dep_cuavz21",
		"data_dep_cuavz22",
		"data_dep_cuavz23",
		"data_dep_cuavz24",
		"data_dep_cuavz25",
		"data_obt_cuavz01",
		"data_obt_cuavz02",
		"data_obt_cuavz03",
		"data_obt_cuavz04",
		"data_obt_cuavz05",
		"data_obt_cuavz06",
		"data_obt_cuavz07",
		"data_obt_cuavz08",
		"data_obt_cuavz09",
		"data_obt_cuavz10",
		"data_obt_cuavz11",
		"data_obt_cuavz12",
		"data_obt_cuavz13",
		"data_obt_cuavz14",
		"data_obt_cuavz15",
		"data_obt_cuavz16",
		"data_obt_cuavz17",
		"data_obt_cuavz18",
		"data_obt_cuavz19",
		"data_obt_cuavz20",
		"data_obt_cuavz21",
		"data_obt_cuavz22",
		"data_obt_cuavz23",
		"data_obt_cuavz24",
		"data_obt_cuavz25",
		"data_reala_exec_lucr",
		"data_resp_atp",
		"data_atp",
		'data_resp_zatp',
		"data_zatp",
		'data_cri',
		"data_ulf"
	);
	foreach ($aSchimbaredata as $key => $value) {
		if (isset($aData[$value])) {
			if ($tip == "xi") {
				if (($aData[$value] == "00000000") || ($aData[$value] == "") || ($aData[$value] == "99990101")) {
					$aData[$value] = NULL;
				}
				else {
					$aData[$value] = dateSapToMysql($aData[$value]);
				}
			}
			else {
				$aData[$value] = dateMysqlToSap($aData[$value]);
			}
		}
	}

	return $aData;
}

//functie ce transforma un multiarray intr-un array
function array_flatten($array) {
	if (! is_array($array)) {
		return FALSE;
	}
	$result = array();
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$result = array_merge($result, array_flatten($value));
		}
		else {
			$result[$key] = $value;
		}
	}

	return $result;
}

function objectToArray($object) {
	if (! is_object($object) && ! is_array($object)) {
		return $object;
	}
	if (is_object($object)) {
		$object = get_object_vars($object);
	}

	return array_map('objectToArray', $object);
}

function arrayToObject($array) {
	if (! is_array($array)) {
		return $array;
	}

	$object = new stdClass();
	if (is_array($array) && count($array) > 0) {
		foreach ($array as $name => $value) {
			$name = strtolower(trim($name));
			if (! empty($name)) {
				$object->$name = arrayToObject($value);
			}
		}

		return $object;
	}
	else {
		return FALSE;
	}
}

/**
 * returns the values  from a multi array that matches the values found in the $aValues array on a certain key $key
 * @param $aValue - array
 * @param $key
 * @param $array
 * @return int|null|string
 */
function searcharray($aValue, $key, $array, $sOp = 'EQ') {
	$aResult = array();

	if (count($array) == 0) {
		return $aResult;
	}

	foreach ($array as $k => $val) {

		if (! array_key_exists($key, $val)) {
			return $aResult;
		}

		if ($sOp == 'EQ') {
			if (in_array($val[$key], $aValue)) {
				$aResult[] = $array[$k];
			}
		}

		if ($sOp == 'NE') {
			if (! in_array($val[$key], $aValue)) {
				$aResult[] = $array[$k];
			}
		}
	}

	return (count($aResult) > 0) ? $aResult : NULL;
}


function sortCompozitii($aData) {

	if(count($aData) == 0) {
		return $aData;
	}

	foreach ($aData as $key => $row) {
		$volume[$key]  = $row['dp_id'];
	}
	array_multisort($volume, SORT_ASC,  $aData);

	return $aData;

}


function sortArray($aData, $sCol= null, $sCol2 = null) {

	if(count($aData) == 0) {
		return $aData;
	}

	foreach ($aData as $key => $row) {
		$volume[$key]  = $row[$sCol];
		$volume1[$key]  = $row[$sCol2];
	}
	array_multisort($volume, SORT_ASC, $volume1,  SORT_ASC, $aData);

	return $aData;

}

?>
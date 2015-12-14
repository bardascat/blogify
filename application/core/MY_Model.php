<?php

class MY_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function gridFilters($sSortBy, $sDir, $sStart, $sLimit, $aFilters, $bIsExport, $aColumnMapping) {

		if (is_array($aColumnMapping) && (count($aColumnMapping) > 0)) {
			foreach ($aColumnMapping as $key => $value) {
				if ($value == $sDir) {
					$sSortBy = $key.".".$sSortBy;
					break;
				}
			}
		}

		if (is_array($aFilters)) {
			foreach ($aFilters as $oFilter) {
				$sFieldname = $oFilter->field;

				if (is_array($aColumnMapping) && (count($aColumnMapping) > 0)) {
					foreach ($aColumnMapping as $key => $value) {
						if ($value == $sFieldname) {
							$sFieldname = $key.".".$sFieldname;
							break;
						}
					}
				}

				$sValue = $oFilter->value;
				$sCompare = isset($oFilter->comparison) ? $oFilter->comparison : NULL;
				$sFilterType = $oFilter->type;
				switch ($sFilterType) {
					case 'string' :
						$this->db->like($sFieldname, $sValue);
						break;
					case 'list' :
						if (strstr($sValue, ',')) {
							$aValues = explode(',', $sValue);
							$this->db->where_in($sFieldname, $aValues);
						}
						else {
							$this->db->where($sFieldname, $sValue);
						}
						break;
					case 'boolean' :
						$this->db->where($sFieldname, $sValue);
						break;
					case 'combo' :
						$this->db->where($sFieldname, $sValue);
						break;
					case 'numeric' :
						switch ($sCompare) {
							case 'eq' :
								$this->db->where($sFieldname, $sValue);
								break;
							case 'lt' :
								$this->db->where($sFieldname.' <', $sValue);
								break;
							case 'gt' :
								$this->db->where($sFieldname.' >', $sValue);
								break;
							case 'gte' :
								$this->db->where($sFieldname.' >=', $sValue);
								break;
							case 'lte' :
								$this->db->where($sFieldname.' <=', $sValue);
								break;
						}
						break;
					case 'date' :
						switch ($sCompare) {
							case 'eq' :
								$this->db->where($sFieldname, date('Y-m-d', strtotime($sValue)));
								break;
							case 'lt' :
								$this->db->where($sFieldname.' <=', date('Y-m-d', strtotime($sValue)));
								break;
							case 'gt' :
								$this->db->where($sFieldname.' >=', date('Y-m-d', strtotime($sValue)));
								break;
						}
						break;
				}

			}
		}
		if (! $bIsExport) {
			$this->db->order_by($sSortBy, $sDir);
			$this->db->limit($sLimit, $sStart);
		}
		else {
			//Limita maxima de inregistrari
			$this->db->limit(50000);
		}
	}

	function now() {
		return date('Y-m-d H:i:s');
	}


	function getEvaluareDataFilter($aPost) {
		if (isset($aPost["status_cod"])) {

			switch ($aPost["status_cod"]) {
				case "ev_simulare" :
					$this->db->where("ext_document.status_id", Constants::EV_STATUS_SIMULARE);
					break;
				case "ev_lucru" :
					$this->db->where("ext_document.status_id", Constants::EV_STATUS_LUCRU );
					break;
				case "ev_finalizat" :
					$this->db->where_in("ext_document.status_id", array(
							Constants::EV_STATUS_FINALIZAT
						)
					);
					break;
				case "ev_validat_ol" :
					$this->db->where_in("ext_document.status_id", array(
							Constants::EV_STATUS_VALIDAT_OL
						)
					);
					break;
				case "ev_retrase" :
					$this->db->where_in("ext_document.status_id", array(
							Constants::EV_STATUS_STERS,
							Constants::EV_STATUS_INACTIV,
							Constants::EV_STATUS_ANULAT_SIMULARE,
							Constants::EV_STATUS_ANULAT_FINALIZAT,
							Constants::EV_STATUS_VALIDAT_ANULAT_ERONAT,
							Constants::EV_STATUS_VALIDAT_EXPIRAT,

							Constants::EV_STATUS_VALIDAT_ANULAT_MOTIV,
							Constants::EV_STATUS_VALIDAT_REEVALUAT,
							Constants::EV_STATUS_EXPIRAT_FARA_OL,
							Constants::EV_STATUS_CONTRACT_SUSPENDAT,
							Constants::EV_STATUS_CONTRACT_REZILIAT,
							Constants::EV_STATUS_CONTRACT_REINCREDINTAT
						)
					);
					break;
				default :
					$this->db->where("1=2", NULL, FALSE);
			}
		}
		else {
			$this->db->where("1=2", NULL, FALSE);
		}
	}

	/** filtrari pe griduri la SPF
	 * @param $aPost
	 */
	function getSPFDataFilter($aPost) {
		if (isset($aPost["status_cod"])) {

			switch ($aPost["status_cod"]) {
				case "spf_lucru" :
					$this->db->where("ext_spf.status_id", Constants::SPF_STATUS_NOU);
					break;
				case "spf_spre_validare" :
					$this->db->where("ext_spf.status_id", Constants::SPF_STATUS_SPRE_APROBARE);
					break;
				case "spf_spre_validare_ss" :
					$this->db->where("ext_spf.status_id", Constants::SPF_STATUS_SPRE_APROBARE_SEF);
					break;
				case "spf_retrase" :
					$this->db->where_in("ext_spf.status_id", array(
							Constants::SPF_STATUS_ANULAT_NOU,
							Constants::SPF_STATUS_INACTIV,
							Constants::SPF_STATUS_STERS,
							Constants::SPF_STATUS_RESPINS,
							Constants::SPF_STATUS_APROBAT_ANULAT,
							Constants::SPF_STATUS_REEVALUAT,
							Constants::SPF_STATUS_EXPIRAT_FARA_OL,
							Constants::SPF_STATUS_CONTRACT_SUSPENDAT,
							Constants::SPF_STATUS_CONTRACT_REZILIAT
						)
					);
					break;
				case "spf_aprobat" :
					$this->db->where_in("ext_spf.status_id", array(
							Constants::SPF_STATUS_APROBAT)
					);
					break;
				case "spf_ol" :
					$this->db->where("ext_spf.status_id", Constants::SPF_STATUS_VALIDAT_OL);
					break;

				default :
					$this->db->where("1=2", NULL, FALSE);
			}

		}
	}


	function getCELDataFilter($aPost) {
		if (isset($aPost["status_cod"])) {

			switch ($aPost["status_cod"]) {
				case "sl_lucrari_dir" :
					$this->db->where_in("sl_lucrare.status_id", array(Constants::SL_LUCRARE_LA_CONSTR, Constants::SL_LUCRARE_LA_DIRIG ));
					break;
				case "sl_lucrari_constr" :
					$this->db->where_in("sl_lucrare.status_id", array(Constants::SL_LUCRARE_LA_CONSTR, Constants::SL_LUCRARE_LA_DIRIG ));
					break;
				case "sl_lucrari_finalizate" :
					$this->db->where_in("sl_lucrare.status_id", array(Constants::SL_ANULATA_EXTRA_CAIET , Constants::SL_LUCRARE_VALIDATA_CU_DEPASIRE, Constants::SL_LUCRARE_VALIDATA_FARA_DEPASIRE));
					break;

				default :
					$this->db->where("1=2", NULL, FALSE);
			}
		}
		else {
			$this->db->where("1=2", NULL, FALSE);
		}
	}



	function gridFiltersExt($sSortBy, $sDir, $sStart, $sLimit, $aFilters, $bIsExport, $aColumnMapping) {

		if (is_array($aColumnMapping) && (count($aColumnMapping) > 0)) {
			foreach ($aColumnMapping as $value) {
				if ($value["ref"] == $sSortBy) {
					$sSortBy = $value["table"].".".$value["col"];
					break;
				}
			}
		}

		if (is_array($aFilters)) {
			foreach ($aFilters as $oFilter) {
				$sFieldname = $oFilter->field;

				if (is_array($aColumnMapping) && (count($aColumnMapping) > 0)) {
					foreach ($aColumnMapping as  $value) {
						if ($value["ref"] == $sFieldname) {
							/**
							 * Update Catalin:
							 * Exista situatii cand nu am nevoie de tabel.coloana ex cand folosesc functii gen GROUP_CONCAT
							 */
							if(!$value["table"])
							$sFieldname =$value["col"];
								else
							$sFieldname = $value["table"].".".$value["col"];

							break;
						}
					}
				}

				$sValue = $oFilter->value;
				$sCompare = isset($oFilter->comparison) ? $oFilter->comparison : NULL;
				$sFilterType = $oFilter->type;
				switch ($sFilterType) {
					case 'string' :
						$this->db->like($sFieldname, $sValue);
						break;
					case 'list' :
						if (strstr($sValue, ',')) {
							$aValues = explode(',', $sValue);
							$this->db->where_in($sFieldname, $aValues);
						}
						else {
							$this->db->where($sFieldname, $sValue);
						}
						break;
					case 'boolean' :
						$this->db->where($sFieldname, $sValue);
						break;
					case 'combo' :
						$this->db->where($sFieldname, $sValue);
						break;
					case 'numeric' :
						switch ($sCompare) {
							case 'eq' :
								$this->db->where($sFieldname, $sValue);
								break;
							case 'lt' :
								$this->db->where($sFieldname.' <', $sValue);
								break;
							case 'gt' :
								$this->db->where($sFieldname.' >', $sValue);
								break;
							case 'gte' :
								$this->db->where($sFieldname.' >=', $sValue);
								break;
							case 'lte' :
								$this->db->where($sFieldname.' <=', $sValue);
								break;
						}
						break;
					case 'date' :
						switch ($sCompare) {
							case 'eq' :
								$this->db->where($sFieldname, date('Y-m-d', strtotime($sValue)));
								break;
							case 'lt' :
								$this->db->where($sFieldname.' <=', date('Y-m-d', strtotime($sValue)));
								break;
							case 'gt' :
								$this->db->where($sFieldname.' >=', date('Y-m-d', strtotime($sValue)));
								break;
						}
						break;
				}

			}
		}
		if (! $bIsExport) {
			$this->db->order_by($sSortBy, $sDir);
			$this->db->limit($sLimit, $sStart);
		}
		else {
			//Limita maxima de inregistrari
			$this->db->limit(50000);
		}
	}
}

/* end */

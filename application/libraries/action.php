<?php

if (! defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * This class ensures action validation, history for any action from a workflow.
 */
class Action {

	/**  Stores the document record as it is before the action.  */
	var $aRec;
	var $aWorkflow;
	var $aActiune;
	var $oCI;

	/**
	 * Constructor
	 * @param  array $aOpt Array with three keys: workflow, action, document ID.
	 */
	function Action($aOpt = array('workflow' => NULL, 'action' => NULL, 'id' => NULL)) {
		$this->oCI = & get_instance();
		if (! empty($aOpt['workflow'])) {
			$this->load($aOpt);
		}
	}

	/**
	 * @param  array $aOpt Array with three keys: workflow, action, document ID.
	 * @return void
	 */
	function load($aOpt = array('workflow' => NULL, 'action' => NULL, 'id' => NULL)) {

		//actiuni pentru care nu se verifica existenta documentului sau urmatoarea stare; PENTRU INSERARI !
		$aSafeAction = array("ext_ev_adaugare", "ext_spf_adaugare",
			"ext_ev_suspendare_contract", "ext_spf_suspendare_contract",
			"ext_ev_reziliere_contract", "ext_spf_reziliere_contract",
			"ext_ev_reincredintare_contract", "ext_spf_reincredintare_contract",
			"sl_situatie_in_lucru","sl_lucrare_extra_caiet", "sl_anulat_extra_caiet",
			"sl_validat_cu_depasire", "sl_validat_fara_depasire",
			"sl_aprobare", "sl_respinge", "sl_retrimite_la_constr"
		);

		if (empty($aOpt['workflow']) || empty($aOpt['action'])) {
			show_er('Precizati workflow-ul si actiunea!');
		}
		/*if (empty($aOpt['id']) && 'create' !== $aOpt['action']) {
			show_er('Precizati ID-ul documentului!');
		}*/
		$this->aWorkflow = $this->oCI->db->get_where('workflow', array('wf_cod' => $aOpt['workflow']))->row_array();
		if (empty($this->aWorkflow)) {
			show_er('Nu a fost gasit workflow-ul!');
		}
		$this->aActiune = $this->oCI->db->get_where('workflow_actiune', array('wf_id' => $this->aWorkflow['wf_id'], 'actiune_cod' => $aOpt['action']))->row_array();
		if (empty($this->aActiune)) {
			show_er('Nu a fost gasita actiunea!');
		}

		if (! in_array($aOpt['action'], $aSafeAction)) {
			$this->aRec = $this->oCI->db->get_where($this->aWorkflow['wf_tabel_document'], array($this->aWorkflow['wf_documentpk'] => $aOpt['id']))->row_array();
			if (empty($this->aRec)) {
				show_er('Nu a fost gasit documentul!');
			}
			if ($this->aRec['status_id'] !== $this->aActiune['status_id_prev']) {
				show_er('Status-ul documentului este invalid pentru aceasta actiune!');
			}
		}
		else {
			$this->aRec = $this->oCI->db->get_where($this->aWorkflow['wf_tabel_document'], array($this->aWorkflow['wf_documentpk'] => $aOpt['id']))->row_array();
		}
	}

	/**
	 * Launch the action, modifying record, history
	 * @param  array $aNew New values for record.
	 * @return boolean true when action successfully executed.
	 * @access public
	 */
	function exec($aNew) {
		$aUserData = $this->oCI->auth->getUserDetails();
		$aRoles = $this->oCI->session->userdata("user_roles");

		$aSafeAction = array("ext_ev_adaugare", "ext_spf_adaugare", "sl_situatie_in_lucru");
		$aNew = array_merge($this->aRec, $aNew);
		$sKey = $this->aWorkflow['wf_documentpk'];

		$aNew['status_id'] = $this->aActiune['status_id_next'];

		if ($this->aActiune["actiune_rol"] != "") {
			if (($this->aActiune["actiune_rol"] == "-") and (count($aRoles) != 0)) {
				show_er('Nu aveti rol pentru aceasta actiune!');
			}
			else {
				$bFoundRol = FALSE;
				$aActiuneRol = explode(",", $this->aActiune["actiune_rol"]);
				foreach ($aActiuneRol as $aRol) {
					if (in_array($aRol, $aRoles)) {
						$bFoundRol = TRUE;
					}
				}

				if ($bFoundRol !== TRUE) {
					show_er('Nu aveti rol pentru aceasta actiune!');
				}

			}
		}

		if (in_array($this->aActiune['actiune_cod'], $aSafeAction)) {
			$this->oCI->db->insert($this->aWorkflow['wf_tabel_document'], $aNew);
			$aNew[$sKey] = $this->oCI->db->insert_id();
		}
		else {
			$this->oCI->db->where($this->aWorkflow['wf_documentpk'], $this->aRec[$sKey]);
			$this->oCI->db->update($this->aWorkflow['wf_tabel_document'], $aNew);
		}

		$aNew['actiune_id'] = $this->aActiune['actiune_id'];
		$aNew['user_id'] = isset($aUserData["user_id"]) ? $aUserData["user_id"] : 0;
		$aNew['data_modificare'] = date("Y-m-d H:i:s");

		$this->oCI->db->insert($this->aWorkflow['wf_tabel_istoric'], $aNew);

		return $aNew[$sKey];
	}
}
/* end */
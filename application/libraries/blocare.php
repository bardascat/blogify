<?php

/**
 * Aceasta clasa permite blocare la nivel de procese
 * Atributele unei blocari sunt: proces, entitate blocata, user blocant, timeout
 */
class Blocare {

    /**
     * Referinta locala la instanta CI
     * @var    object
     * @access protected
     */
    protected $_oCI;

    /**
     *
     * User-ul curent
     * @var    int
     * @access protected
     */
    protected $_iUser;

    /**  Nume user curent
     * @var string
     */
    protected $_sUserNume  ;

    /**
     * Configuratia curenta
     *
     * @var    array
     * @access protected
     */
    protected $_aConfig;

    /**
     * Constructor
     *
     * @return void
     * @access public
     */
	function __construct($aCfg = array(
        'table_name' => 'blocare',
        'process_field' => 'bloc_proces',
        'expiry_field' => 'bloc_data_expirare',
        'entity_field' => 'bloc_id_entitate',
        'user_field' => 'userid' ,
        'user_name_field' =>"bloc_user_nume"
    ))
	{
        $this->_oCI = &get_instance();
        $aUser = $this->_oCI->auth->getUserDetails();
	    $this->_iUser = empty($aUser['user_id']) ? 0 : $aUser['user_id'];
        $this->_sUserNume = empty($aUser['user_alias']) ? "Don Ion" : $aUser['user_alias'];
        $this->_aConfig = $aCfg;
	}

    /**
     * Verifica daca exista o blocare pe procesul si la entitatea specificata
     *
     * @param    $iEntity ID entitate blocata
	 * @param    $sProces Procesul de verificat blocare (optional)
     * @return mixed  cu detaliile blocarii, true daca nu exista blocare
     * @access public
     */
	public function verificaBlocare($iEntitate, $sProces = FALSE)
	{
        $aCfg = &$this->_aConfig;
        // se foloseste referinta timp PHP
        $sNow = date('Y-m-d H:i:s');
		$sWhere = '';
		if ($sProces !== FALSE) {
			$sProces = $this->_oCI->db->escape($sProces);
			$sWhere = " {$aCfg['process_field']} = {$sProces} AND ";
		}
        $sQuery = "
            SELECT
				{$aCfg['expiry_field']} AS data_expirare,
				{$aCfg['user_field']} AS user_id  ,
				GROUP_CONCAT({$aCfg['user_name_field']} SEPARATOR ',') AS user_nume,
				{$aCfg['process_field']} AS bloc_proces
            FROM {$aCfg['table_name']}
            WHERE {$sWhere} {$aCfg['entity_field']} = ?
                AND {$aCfg['expiry_field']} > '{$sNow}' AND  {$aCfg['user_field']} != {$this->_iUser}
            GROUP BY {$aCfg['entity_field']}
			ORDER BY  data_expirare DESC
            LIMIT 1
        ";
        $oResult = $this->_oCI->db->query($sQuery, array($iEntitate));
		// se returneaza pentru a obtine detalii despre cine blocheaza si pana cand
        if ($oResult->num_rows()) {
            return $oResult->row_array();
        } else {
            return true;
        }
    }

    /**
     * Seteaza o blocare daca este posibil sau intoarce blocarea existenta
     *
     * @param    $sProces Procesul de blocat
     * @param    $iEntity ID entiatate blocata
     * @param    $iTimeout Timeout in minute la care expira blocarea
     * @param    $iUser ID User-ul care seteaza blocarea, implicit user-ul curent
     * @return mixed  true daca blocarea a fost setata cu succes, cu detaliile blocarii daca exista deja o blocare
     * @access public
     */
	public function startBlocare($sProces, $iEntitate, $iTimeout = 0, $iUser = 0)
	{
        // se verifica mai intai daca exista Blocare pe proces + entitate
        // daca exista se returneaza cu userul si timpul expirarii
        $aBlocare = $this->verificaBlocare($iEntitate);
        if (is_array($aBlocare)) { return $aBlocare; }
        // se adauga un :) Blocare
        $aCfg = &$this->_aConfig;
        if (!$iUser) { $iUser = $this->_iUser; }
        $sUserNume = $this->_sUserNume;

        //daca nu este definit timeout se ia din low_level_config pe procesul $sProces
        if (!$iTimeout) {
             $iTimeout = $this->_oCI->config->item($sProces);
             if (!$iTimeout)  $iTimeout = 0;
        }

        // se foloseste referinta timp PHP
        $sNow = date('Y-m-d H:i:s');
        $sQuery = "
            INSERT INTO {$aCfg['table_name']}
                ({$aCfg['process_field']}, {$aCfg['entity_field']}, {$aCfg['expiry_field']}, {$aCfg['user_field']}, bloc_user_nume)
            VALUES
                (?, ?, '{$sNow}' + INTERVAL ? MINUTE, ?, ?)
            ON DUPLICATE KEY UPDATE
                {$aCfg['expiry_field']} = '{$sNow}' + INTERVAL ? MINUTE, {$aCfg['user_field']} = ? ,   bloc_user_nume = ?

        ";
        $this->_oCI->db->query($sQuery, array($sProces, $iEntitate, $iTimeout, $iUser, $sUserNume, $iTimeout, $iUser, $sUserNume));
        return true;
    }

    /**
     * Elimina o blocare existenta setata de un anumit user
     *
     * @param    $sProces Procesul blocat
     * @param    $iEntity ID entiatate blocata
     * @param    $iUser ID User-ul care a setat blocarea, implicit user-ul curent
     * @return bool  True daca s-a ridicat blocajul, false altfel
     * @access public
     */
	public function endBlocare($sProces, $iEntitate, $iUser = 0)
	{
        $aCfg = &$this->_aConfig;
        if (!$iUser) { $iUser = $this->_iUser; }
        $sQuery = "
            DELETE FROM {$aCfg['table_name']}
            WHERE {$aCfg['process_field']} = ?
                AND {$aCfg['entity_field']} = ?
                AND {$aCfg['user_field']} = ?
        ";
        $this->_oCI->db->query($sQuery, array($sProces, $iEntitate, $iUser));
        $iAfectat = $this->_oCI->db->affected_rows();
        // se apleleaza 'garbage colector'
        $this->_gc();
        return ($iAfectat > 0);
    }

    /**
     * Curata blocajele vechi
     *
     * @return void
     * @access protected
     */
    protected function _gc()
    {
        // se curata inregistrarile vechi cu probabilitate 1/10
        $n = rand(1, 10);
        if (5 != $n) { return; }
        $aCfg = &$this->_aConfig;
        // se foloseste referinta timp PHP
        $sNow = date('Y-m-d H:i:s');
        $sQuery = "
            DELETE FROM {$aCfg['table_name']}
            WHERE {$aCfg['expiry_field']} < '{$sNow}'
        ";
        $this->_oCI->db->query($sQuery);
    }

}

/* end */
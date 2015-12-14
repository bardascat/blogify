<?php

class Constants {
	/** TIPURI OPERATII **/

	//Id modificare detalii incident - neconfirmat

	const OP_COD_NECUNOSCUT = "cod_actiune_necunoscut";
	const OP_LUCRARE_NECUNOSCUTA = "lucrare_necunoscuta";

	const ACT_UPLOAD_DOCUMENT = "fis99";
	const ACT_UPLOAD_DOCUMENT_OBS = "Incarcare document";

	const ACT_DELETE_DOCUMENT = "fis98";
	const ACT_DELETE_DOCUMENT_OBS = "Stergere document";

	const OP_UPLOAD_DOCUMENT_STERGERE = "upload_document_stergere";

	//costante tip raspuns RABT
	const OP_RAPORT_RABT_RASPUNS_RESP = "responsabil";
	const OP_RAPORT_RABT_RASPUNS_CONSTR = "constructor";

	//RAPORT ABATERE
	const ACT_RASPUNS_RABT = "61";
	const ACT_RASPUNS_RABT_OBS = "Adaugare obiectiuni pe raport vizita lucrare";

	const ACT_RASPUNS_AG_RABT = "06";
	const ACT_RASPUNS_AG_RABT_OBS = "Primire observatii AG la raport vizita lucru";

	const ACT_RABT = "14";
	const ACT_RABT_OBS = "S-a primit un nou raport vizita lucrare";

	const ACT_FIRMA_NOUA = "10";
	const ACT_FIRMA_NOUA_OBS = "Lucrarea a fost alocata la constructor";

	const ACT_DATE_INITIALE = "01";
	const ACT_DATE_INITIALE_OBS = "S-au primit datele initiale";

	const ACT_SEMNARE_OL = "02";
	const ACT_SEMNARE_OL_OBS = "S-a repartizat lucrarea";

	const ACT_DATA_DEPUNERE_CU = "51";
	const ACT_DATA_DEPUNERE_CU_OBS = "S-a depus la CU";

	const ACT_DATA_OBTINERE_CU = "52";
	const ACT_DATA_OBTINERE_CU_OBS = "S-a obtinut CU";

	const ACT_DATA_DEPUNERE_AC = "55";
	const ACT_DATA_DEPUNERE_AC_OBS = "S-a depus la AC";

	const ACT_DATA_OBTINERE_AC = "56";
	const ACT_DATA_OBTINERE_AC_OBS = "S-a obtinut AC";

	const ACT_DATA_DEPUNERE_AS = "57";
	const ACT_DATA_DEPUNERE_AS_OBS = "S-a depus la AS";

	const ACT_DATA_OBTINERE_AS = "58";
	const ACT_DATA_OBTINERE_AS_OBS = "S-a obtinut la AS";

	const ACT_INCEPERE_LUCRARE = "59";
	const ACT_INCEPERE_LUCRARE_OBS = "S-a transmis solicitarea incepere lucrare catre DGSR";

	const ACT_SOLICITARE_RT = "60";
	const ACT_SOLICITARE_RT_OBS = "S-a transmis solicitare de RT catre DGSR";

	const ACT_DEPUNERE_AVIZE_PRIMARIE = "53";
	const ACT_DEPUNERE_AVIZE_PRIMARIE_OBS = "S-a depus la avize";
	const ACT_OBTINERE_AVIZE_PRIMARIE = "54";
	const ACT_OBTINERE_AVIZE_PRIMARIE_OBS = "S-au obtinut avizele";

	//ACTIUNI SAP

	const ACT_STATUS_DT_AVIZARE = "03";
	const ACT_STATUS_DT_AVIZARE_OBS_AVIZAT = "S-a admis avizarea DT";
	const ACT_STATUS_DT_AVIZARE_OBS_RESPINS = "S-a respins avizarea DT";

	const ACT_STATUS_DT_VALIDARE = "20";
	const ACT_STATUS_DT_VALIDARE_OBS_AVIZAT = "S-a admis validarea DT";
	const ACT_STATUS_DT_VALIDARE_OBS_RESPINS = "S-a respins validarea DT";

	const ACT_STATUS_CRI = "08";
	const ACT_STATUS_CRI_OBS_AVIZAT = "S-a admis solicitarea de receptie tehnica";
	const ACT_STATUS_CRI_OBS_RESPINS = "S-a respins solicitarea de receptie tehnica";

	const ACT_STATUS_ULF = "09";
	const ACT_STATUS_ULF_OBS_AVIZAT = "S-a admis solicitarea de incepere lucrare";
	const ACT_STATUS_ULF_OBS_RESPINS = "S-a respins solicitarea de incepere lucrare";

	const ACT_DATA_REALA_EXECUTIE = "04";
	const ACT_DATA_REALA_EXECUTIE_OBS = "S-a alocat diriginte de santier";

	const ACT_TERMINARE_LUCRARE = "12";
	const ACT_TERMINARE_LUCRARE_OBS = "Lucrarea s-a incheiat";

	const ACT_SUSPENDARE_LUCRARE = "13";
	const ACT_SUSPENDARE_LUCRARE_OBS = "Lucrarea s-a suspendat";

	const ACT_REZILIERE_LUCRARE = "11";
	const ACT_REZILIERE_LUCRARE_OBS = "Lucrarea a fost reziliata";

	const ACT_MOTIV_INTARZIERE = "62";
	const ACT_MOTIV_INTARZIERE_OBS = "S-a transmis motiv intarziere";

	const ACT_RESPINGERE_VALIDARE_DT = "65";
	const ACT_RESPINGERE_VALIDARE_DT_OBS = "se inchide respingerea validare DT";

	const ACT_RESPINGERE_AVIZARE_DT = "66";
	const ACT_RESPINGERE_AVIZARE_DT_OBS = "se inchide respingerea avizarea DT";

	const ACT_NEDEPUNERE_AVIZARE_DT = "21";
	const ACT_NEDEPUNERE_AVIZARE_DT_OBS = "nedepunere avizare DT";

	const ACT_NEDEPUNERE_VALIDARE_DT = "22";
	const ACT_NEDEPUNERE_VALIDARE_DT_OBS = "nedepunere validare DT";

	const ACT_LUCRARE_MIGRARE = "23";
	const ACT_LUCRARE_MIGRARE_OBS = " lucrare migrata";

	/* STATUSURI CRONURI */
	const CRON_SUSPENDAT = 11;
	const CRON_NOU = 0;
	const CRON_SUCCES = 10;

	/* LISTA STATUSURI */
	const STAT_OL_EMIS = 1;

	const STAT_CU_DEPUS = 2;
	const STAT_CU_NU_E_NECESAR = 3;
	const STAT_CU_INCEPERE_LUCRARE = 4;
	const STAT_AC_DEPUS = 6;
	const STAT_AC_OBTINUT = 7;

	const STAT_DT_AVIZARE = 5;
	const STAT_DT_VALIDARE = 21;

	const STAT_DT_AVIZAT = 8;
	const STAT_DT_NEAVIZAT = 9;

	const STAT_DT_VALIDAT = 10;
	const STAT_DT_NEVALIDAT = 11;

	const STAT_LUCRARE_MIGRATA = 23;
	const STAT_LUCRARE_NOUA = 24;


	const STAT_CRI_AVIZAT = 22;
	const STAT_CRI_NEAVIZAT = 23;


	const STAT_ULF_AVIZAT = 24;
	const STAT_ULF_NEAVIZAT = 25;
	const STAT_ULF_IN_ASTEPTARE = 26;

	const STAT_CU_OBTINUT = 9;
	const STAT_OBTINUT_CU_AVIZE_0 = 20;
	const STAT_AVIZ_DEPUS = 11;
	const STAT_AVIZ_OBTINUT = 12;
	const STAT_AS_DEPUS = 15;
	const STAT_AS_OBTINUT = 16;
	const STAT_INCEPERE_LUCRARE = 17;
	const STAT_EXECUTIE = 18;
	const STAT_RECEPTIE_TEHN_EXECUTATA = 19;
	const STAT_REZILIAT = 21;
	const STAT_SUSPENDAT = 22;

	const CRC_MOTIV_COMANDA_PR = "R21";

	const ER_LIPSA_ARHIVA_DOCUMENTUM = "lipsa_arhiva";
	const ER_STERGERE_FORM_DOCUMENTUM = "doc_lipsa";
	const ER_TRIMITERE_FORM_DOCUMENTUM = "nu merge transmiterea in DOCUMENTUM";
	const ACT_STERGERE_FORM_DOCUMENTUM = "doc_sters";


	/**
	 * PORTAL PARTENERI EV SI SPF
	 *
	 */

	const EV_ACTIUNE_NOTIFICARE = "EV01"; //creare notificare
	const EV_ACTIUNE_SD = "EV03"; //actiune de primire date SD
	const EV_ACTIUNE_COMANDA_MM = "EV02"; //actiune de primire date MM + notificare
	const EV_ACTIUNE_COMANDA_MM_OBS = "S-a primit o comanda MM";

	const EV_ACTIUNE_CN41 = "EV04"; //actiune de primire valoare cn41
	const EV_ACTIUNE_CN41_OBS = "S-au primit valori CN41";




	const EV_ACTIUNE_NOTIFICARE_OBS = "S-a primit notificare";
	const EV_ACTIUNE_SD_OBS = "S-a primit lucrare SL";
	const EV_ACTIUNE_NOTIF_VALIDATOR = "EV50"; //trimitere mail de validare BRST
	const EV_ACTIUNE_NOTIF_RESPINGERE = "EV53"; //respingere spf
	const EV_ACTIUNE_NOTIF_APROBAT = "EV54"; //aprobare spf
	const EV_ACTIUNE_NOTIF_AUTOMATIZARI = "EV55"; //automatizari
	const EV_ACTIUNE_NOTIF_ACTIVARE_LUCRARE = "EV56"; //automatizari
	const EV_ACTIUNE_NOTIF_SPRE_VALIDARE_LUCRARE = "EV57"; //automatizari
	const EV_ACTIUNE_NOTIF_RESPINGERE_LUCRARE = "EV58"; //automatizari
	const EV_ACTIUNE_NOTIF_ANULARE_LUCRARE = "EV59"; //automatizari
	const EV_ACTIUNE_NOTIF_REGULARIZARE_AG = "EV60"; //automatizari
	const EV_ACTIUNE_NOTIF_RESPINGERE_ANULARE = "EV61"; //automatizari
	const EV_ACTIUNE_NOTIF_AVERTIZARE_REFACERE = "EV62"; //automatizari
	const EV_ACTIUNE_NOTIF_APROBARE_ANULARE = "EV63"; //automatizari
    const EV_ACTIUNE_NOTIF_VALIDARE_ANULARE = "EV64"; //automatizari
	const EV_ACTIUNE_NOTIF_AG_PUNCTE_PR = "EV65"; //automatizari




	const ER_EV_NOTIFICARE_FARA_NR = "EXT01";
	const ER_SL_LUCRARE_SD = "SL01";

	const  DELTA = 0.00001;
	const  ROL_EV_APROBARE = 11;


	const EV_STATUS_SIMULARE = 1;
	const EV_STATUS_LUCRU = 2;
	const EV_STATUS_FINALIZAT = 3;
	const EV_STATUS_STERS = 4;
	const EV_STATUS_INACTIV = 5;

	const EV_STATUS_ANULAT_SIMULARE = 9;
	const EV_STATUS_ANULAT_FINALIZAT = 10;
	CONST EV_STATUS_VALIDAT_ANULAT_ERONAT = 11;
	CONST EV_STATUS_VALIDAT_EXPIRAT = 12;
	CONST EV_STATUS_VALIDAT_ANULAT_MOTIV = 13;
	CONST EV_STATUS_VALIDAT_REEVALUAT = 14;
	CONST EV_STATUS_VALIDAT_OL = 15;
	CONST EV_STATUS_EXPIRAT_FARA_OL = 16;
	CONST EV_STATUS_CONTRACT_SUSPENDAT = 17;
	CONST EV_STATUS_CONTRACT_REZILIAT = 18;
	CONST EV_STATUS_CONTRACT_REINCREDINTAT = 19;


	const SPF_STATUS_NOU = 50;
	const SPF_STATUS_ANULAT_NOU = 52;
	const SPF_STATUS_SPRE_APROBARE = 53;
	const SPF_STATUS_INACTIV = 54;
	const SPF_STATUS_STERS = 55;
	const SPF_STATUS_RESPINS = 56;
	const SPF_STATUS_APROBAT = 57;
	const SPF_STATUS_APROBAT_ANULAT = 58;
	const SPF_STATUS_REEVALUAT = 59;
	const SPF_STATUS_VALIDAT_OL = 60;
	const SPF_STATUS_EXPIRAT_FARA_OL = 61;
	const SPF_STATUS_CONTRACT_SUSPENDAT = 62;
	const SPF_STATUS_CONTRACT_REZILIAT = 63;
	const SPF_STATUS_CONTRACT_REINCREDINTAT = 64;
	const SPF_STATUS_SPRE_APROBARE_SEF = 65;


	const  SL_LUCRARE_NOUA = 100;
	const  SL_LUCRARE_LA_CONSTR = 101;
	const  SL_LUCRARE_LA_DIRIG= 102;
	const  SL_ANULATA_EXTRA_CAIET= 103;
	const  SL_LUCRARE_VALIDATA_CU_DEPASIRE= 104;
	const  SL_LUCRARE_VALIDATA_FARA_DEPASIRE= 105;

	const  SL_IN_LUCRU = 150;
	const  SL_ANULAT = 151;
	const  SL_SPRE_APROBARE = 152;
	const  SL_APROBAT = 153;
	const  SL_RESPINS = 155;
	const  SL_VALIDAT = 157;

	const PCT_REDIMENSIONARE_PR = 100;




	static function intrebariSecuritate() {
		return array(
			"Care este prenumele mamei?",
			"Care este anul nasterii tatalui?",
		);
	}


	static function getSpfStruct($iVers) {
		if ($iVers == 1) {
			return array(
				array(
					"st_text" => "Studiul a fost demarat in baza ....... cereri de acord",
					"st_cod"  => "spf_cerere"
				),
				array(
					"st_text" => "prin care ........ , solicita alimentarea ",
					"st_cod"  => "spf_solicitant"
				),
				array(
					"st_text" => "solicita alimentarea cu gaze naturale a obiectivului .....(stadiu constructie)",
					"st_cod"  => "spf_obiectiv"
				),
				array(
					"st_text" => "debit total solicitat ...........mc/h",
					"st_cod"  => "spf_debit"
				),
				array(
					"st_text"    => "este conform consumului mediu estimat pentru",
					"st_cod"     => "spf_consum",
					"st_valoare" => "pentru incalzire, prepararea apei calde menajere si a hranei"
				),

                array(
                    "st_text"    => "Din conducta propusa se pot racorda si alte imobile in curs de constructie si viitoare",
                    "st_cod"     => "spf_racordare",
                    "st_valoare" => "Din conducta propusa se pot racorda si alte imobile in curs de constructie si viitoare."
                ),
				array(
					"st_text" => "se va racorda la conducta existenta pe strada",
					"st_cod"  => "spf_cnd_existenta_strada"
				),
				array(
					"st_text" => "se va racorda la conducta existenta cu diametru",
					"st_cod"  => "spf_cnd_existenta_diametru"
				),
				array(
					"st_text"    => "Se vor monta vane conform planului anexat, respectiv",
					"st_cod"     => "spf_vane_plan",
					"st_valoare" => "Nu se impune necesitatea montarii de vane pe reteaua propusa"
				),
				array(
					"st_text" => "Propuneri specifice pentru situatia proiectata",
					"st_cod"  => "spf_propuneri"
				),
				array(
					"st_text" => "Reteaua propusa se va executa pe drumuri apartinand domeniului..",
					"st_cod"  => "spf_domeniu"
				),
				array(
					"st_text" => "partea investitionala  rentabila pentru DGS lei",
					"st_cod"  => "spf_parte_inv_lei"
				),
				array(
					"st_text" => "partea investitionala rentabila pentru DGS proc",
					"st_cod"  => "spf_parte_inv_proc"
				),
				/*array(
					"st_text" => "partea investitionala suportata de solicitant lei",
					"st_cod"  => "spf_parte_solicitant_lei"
				),
				array(
					"st_text" => "partea investitionala suportata de solicitant proc",
					"st_cod"  => "spf_parte_solicitant_proc"
				),*/
				array(
					"st_text" => "esalonarea investitiei anul 2 - lungime(m)",
					"st_cod"  => "spf_esalonare_an2_lung"
				),
				array(
					"st_text" => "esalonarea investitiei anul 2 - valoare",
					"st_cod"  => "spf_esalonare_an2_lei"
				),
				array(
					"st_text" => "Se propune durata de realizarea a investitiei ...... luni",
					"st_cod"  => "spf_realizare_luni"
				),
			);
		}
	}


	static function getSpfRezultatStruct($iVers) {
		if ($iVers == 1) {
			return array(
				array(
					"st_text" => "Descriere proiect (imobil rezidential, ansamblu rezidential, agent economic, OSC, agent industrial)",
					"st_cod"  => "spf_rez_descr_proj"
				),
				array(
					"st_text" => "Presiune solicitata",
					"st_cod"  => "spf_rez_presiune"
				),
				array(
					"st_text" => "Debit de perspectiva luat in calcul (mc/h)",
					"st_cod"  => "spf_rez_debit"
				),
				array(
					"st_text" => "Material (OL, PE100)",
					"st_cod"  => "spf_rez_material"
				),
				array(
					"st_text" => "Tip teren (zona verde, macadam, asfalt....)",
					"st_cod"  => "spf_rez_teren"
				)
			);
		}
	}

	static function getSpfStructAltele() {
		return array(
			array("st_id" => 1),
			array("st_id" => 2),
			array("st_id" => 3),
			array("st_id" => 4),
			array("st_id" => 5),
			array("st_id" => 6),
			array("st_id" => 7)
		);
	}


}

?>
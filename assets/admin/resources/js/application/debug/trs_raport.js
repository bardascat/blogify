/*global APP,Ext,fnCompleteForm,console*/


/*global Ext, APP*/
Ext.ns('APP.trs_raport');
Ext.apply(APP.trs_raport, {


	id: "raportTerasamente",

	downloadRaport: function(url, gridRaport){
		var gridData = new Array();
		gridRaport.getStore().each(function(record) {
			gridData.push(record.data);
		});

		//console.log(gridData);
		var data = {
			gridDate: gridData,
			export_type: "excel"
		};
		APP.workflow.exportDoc(url, data);
	},

	gridFluxLucrari: function(config) {
		//constructie formular
		var oWind;
		var oFiltreCentralizatorFL = new Ext.FormPanel({
			autoHeight: true,
			labelWidth: 200,
			frame: true,
			layout: "form",
			bodyStyle : {
				padding : "5px"
			},
			defaults : {
				anchor : "100%"
			},
			iconCls : 'icon-fugue-report',
			items: [
				{
					xtype: "uxSFielDdate",
					fieldLabel: "Data Interval Comanda PM",
					name: "data_interval_pm"
				},
				{
					fieldLabel: "Constructor",
					xtype: "uxFCombo",
					name: "partener_id",
					allowBlank: !valueOfArrayinArray(["trs_constructor"], APP.user_rol),
					listWidth: 300,
					easyConfig: {
						baseParams: {
							furncl_activ: 1
						},
						readerFields: [
							{
								name: "id",
								mapping: "partener_id"
							},
							{
								name: "name",
								mapping: "partener_nume"
							}
						],
						proxyUrl: 'terasamente/raport/getConstructor'
					}
				},
				{
					id: "FOLCombo",
					fieldLabel: "FOL",
					xtype: "uxFCombo",
					name: "centru_cost",
					allowBlank: !valueOfArrayinArray(["trs_sef_fol"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "centru_cost"
							},
							{
								name: "name",
								mapping: "fol"
							}
						],
						proxyUrl:  'terasamente/raport/getFOL'
					}
				},
				{
					id: "sectorCombo",
					fieldLabel: "Sector",
					xtype: "uxFCombo",
					name: "sector",
					allowBlank: !valueOfArrayinArray(["trs_sef_sector"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "sector_id"
							},
							{
								name: "name",
								mapping: "sector"
							}
						],
						proxyUrl:  'terasamente/raport/getSector'
					}
				},
				{
					id: "directiiCombo",
					fieldLabel: "Directie Regionala",
					xtype: "uxFCombo",
					name: "regionala",
					allowBlank: !valueOfArrayinArray(["trs_sef_sector"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "regionala"
							},
							{
								name: "name",
								mapping: "regionala"
							}
						],
						proxyUrl:  'terasamente/raport/getDirectiiRegionale'
					}
				},
				{
					id: "comandaCombo",
					fieldLabel: "Comanda PM",
					xtype: "uxFCombo",
					name: "comanda_id",
					allowBlank: true,
					forceSelection: false,
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "comanda_id"
							},
							{
								name: "name",
								mapping: "sap_id_comanda"
							}
						],
						proxyUrl:  'terasamente/raport/getComenziPM'
					}
				},
				{
					id: "tipLucrareCombo",
					fieldLabel: "Tip Lucrare",
					xtype: "uxFCombo",
					name: "tip_lucrare_id",
					allowBlank: true,
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "tip_lucrare_id"
							},
							{
								name: "name",
								mapping: "lucrare_nume"
							}
						],
						proxyUrl:  'terasamente/lucrari/getLucrariDisponibileComanda'
					}
				},
				{
					fieldLabel: 'Categorie Lucrare',
					xtype: "uxFCombo",
					allowBlank: true,
					name: "categorie_lucrare",
					easyConfig: {
						mode: "local",
						localData: [
							['urgenta', 'URGENTA'],
							['programata', 'PROGRAMATA']
						]
					}
				},
				{
					fieldLabel: 'Status Lucrare',
					xtype: "uxFCombo",
					allowBlank: true,
					name: "status_lucrare",
					easyConfig: {
						mode: "local",
						localData: [
							['trs_lucrare_noua', 'Noua'],
							['trs_lucrare_demarata', 'Demarata'],
							['trs_lucrare_anulata', 'Anulata'],
							['trs_lucrare_neonorata', 'Neonorata'],
							['trs_lucrare_finalizata_in_teren', 'Finalizata in Teren'],
							['trs_lucrare_finalizata_cu_sl', 'Finalizata cu SL'],
							['trs_lucrare_finalizata_cu_sl_registratura', 'Finalizata cu SL Registratura'],
							['trs_lucrare_finalizata_cu_migo', 'Finalizata cu MIGO'],
							['trs_lucrare_fara_pvrf', 'Fara PVRF'],
							['trs_lucrare_cu_pvrf', 'Cu PVRF']
						]
					}
				}
			],
			buttons: [
				{
					text:'Genereaza Raport',
					id:"genereazaRaportFL",
					handler: function () {
						if (!oFiltreCentralizatorFL.getForm().isValid()) {
							return;
						}
						var filtre=oFiltreCentralizatorFL.getItemsValues() ;
						//reglementat/nereglementat
						filtre.type=config.type;
						APP.trs_raport.getGridFluxLucrari({
							config:config,
							filtre:filtre
						});
						oWind.close();
					}
				}

			]
		});

		//constructie fereastra
		oWind = new Ext.Window({
			title: "Selectii Raport",
			width: 550,
			layout: "fit",
			modal: true,
			closeAction: "close",
			items: [oFiltreCentralizatorFL]
		}).show();

	},

	getGridFluxLucrari: function (data) {
		//APP.trs_raport.id=config.tab_id;

		if (!Ext.getCmp(APP.trs_raport.id)) {

			var oCmLucrari = new Ext.grid.ColumnModel({
				columns: [
					{
						header: "Partener",
						dataIndex: 'partener_nume',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "FOL",
						dataIndex: 'fol',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Sector",
						dataIndex: 'sector',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Directie",
						dataIndex: 'regionala',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Nr. Comanda PM",
						dataIndex: 'sap_id_comanda',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Data Intrare Comanda PM in WEB",
						dataIndex: 'data_creare_comanda',
						width: 250,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Data Creare Lucrare",
						dataIndex: 'data_creare_lucrare',
						width: 200,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Tip Lucrare",
						dataIndex: 'lucrare_nume',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Status Lucrare",
						dataIndex: 'status_lucrare',
						width: 150
					},
					{
						header: "Selectie Lucrare",
						dataIndex: 'selectie_lucrare',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Categorie Lucrare",
						dataIndex: 'urgenta',
						width: 150,
						renderer: function (value, metadata, record, rowIndex, columnIndex, store) {
							if(record.get('urgenta')==1)
								return "urgenta";
							else
								return "programata";
						}
					},
					{
						header: "Localitate",
						dataIndex: 'sap_localitate',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Strada",
						dataIndex: 'sap_strada',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Nr. Strada",
						dataIndex: 'sap_strada_nr',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Judet",
						dataIndex: 'sap_judet',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "ID SEL",
						dataIndex: 'sel_nr_inregistrare',
						width: 150
					},
					{
						header: "Data transmitere SEL",
						dataIndex: 'data_transmitere_sel',
						width: 150
					},
					{
						header: "Data Atasare PV",
						dataIndex: 'data_atasare_pv',
						width: 150
					},
					{
						header: "Data Atasare PVRT",
						dataIndex: 'data_atasare_pvrt',
						width: 150
					},
					{
						header: "Data Creare SL",
						dataIndex: 'data_creare_sl',
						width: 150
					},
					{
						header: "Data Validare SL",
						dataIndex: 'data_validare_sl',
						width: 150
					},
					{
						header: "Data Atasare PVRF",
						dataIndex: 'data_atasare_pvrf',
						width: 150
					},
					{
						header: "Data Generare Centralizator MM",
						dataIndex: 'data_generare_centralizator_mm',
						width: 250
					},
					{
						header: "Data Creare Comanda MM",
						dataIndex: 'data_creare_comanda_mm',
						width: 250
					},
					{
						header: "Data MIGO",
						dataIndex: 'data_migo',
						width: 150
					}
				],
				defaults: {
					sortable: false
				}
			});


			var oLucrariGrid = new Ext.ux.fatGrid({
				xtype: "uxFatGrid",
				layout: 'fit',
				itemId: "gridFluxLucrari",
				region: 'center',
				viewConfig: {
					forceFit: false
				},
				gridConfig: {
					filterable: true,
					resetFilterButton : true,
					exportable : true,
					exportButton : true,
					url: 'terasamente/raport/getRaportFluxLucrari',
					sortField: "lucrare_id",
					idProperty:"lucrare_id",
					storeBaseParams:data.filtre,
					fields: ["partener_nume", "fol", "sector", "regionala",  "sap_id_comanda", "data_creare_comanda", "data_creare_lucrare", "lucrare_nume", "selectie_lucrare", "urgenta", "sap_localitate", "sap_strada", "sap_strada_nr", "sap_judet", "sel_nr_inregistrare", "data_transmitere_sel", "status_lucrare", "data_atasare_pv", "data_atasare_pvrt", "data_creare_sl", "data_validare_sl", "data_atasare_pvrf", "data_generare_centralizator_mm", "data_creare_comanda_mm", "data_migo"]
				},
				cm: oCmLucrari,
				tbar: []
			});


			var oNomTab = new Ext.Panel({
				title:data.config.title,
				id: "gridFluxLucrari",
				iconCls: 'icon-fugue-table-excel',
				closable: true,
				layout: 'border',
				items: [oLucrariGrid]
			});


			//adaugare tab la tabpanel-ul principal
			APP.oCenterRegion.add(oNomTab);
			APP.oCenterRegion.doLayout();
		}

			//activare tab
			APP.oCenterRegion.setActiveTab("gridFluxLucrari");

	},

	gridSelNeonorat: function(config) {
		//constructie formular
		var oWind;

		var oFiltreCentralizatorSN = new Ext.FormPanel({
			autoHeight: true,
			labelWidth: 200,
			frame: true,
			layout: "form",
			bodyStyle : {
				padding : "5px"
			},
			defaults : {
				anchor : "100%"
			},
			iconCls : 'icon-fugue-report',
			items: [
				{
					xtype: "uxSFielDdate",
					fieldLabel: "Data Interval SEL",
					name: "data_interval_sel"
				},
				{
					xtype: "uxSFielDdate",
					fieldLabel: "Data Interval Comanda PM",
					name: "data_interval_pm"
				},
				{
					fieldLabel: "Constructor",
					xtype: "uxFCombo",
					name: "partener_id",
					allowBlank: !valueOfArrayinArray(["trs_constructor"], APP.user_rol),
					listWidth: 300,
					easyConfig: {
						baseParams: {
							furncl_activ: 1
						},
						readerFields: [
							{
								name: "id",
								mapping: "partener_id"
							},
							{
								name: "name",
								mapping: "partener_nume"
							}
						],
						proxyUrl: 'terasamente/raport/getConstructor'
					}
				},
				{
					id: "FOLCombo",
					fieldLabel: "FOL",
					xtype: "uxFCombo",
					name: "centru_cost",
					allowBlank: !valueOfArrayinArray(["trs_sef_fol"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "centru_cost"
							},
							{
								name: "name",
								mapping: "fol"
							}
						],
						proxyUrl:  'terasamente/raport/getFOL'
					}
				},
				{
					id: "sectorCombo",
					fieldLabel: "Sector",
					xtype: "uxFCombo",
					name: "sector",
					allowBlank: !valueOfArrayinArray(["trs_sef_sector"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "sector_id"
							},
							{
								name: "name",
								mapping: "sector"
							}
						],
						proxyUrl:  'terasamente/raport/getSector'
					}
				},
				{
					id: "directiiCombo",
					fieldLabel: "Directie Regionala",
					xtype: "uxFCombo",
					name: "regionala",
					allowBlank: !valueOfArrayinArray(["trs_sef_sector"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "regionala"
							},
							{
								name: "name",
								mapping: "regionala"
							}
						],
						proxyUrl:  'terasamente/raport/getDirectiiRegionale'
					}
				},
				{
					id: "comandaCombo",
					fieldLabel: "Comanda PM",
					xtype: "uxFCombo",
					name: "comanda_id",
					allowBlank: true,
					forceSelection: false,
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "comanda_id"
							},
							{
								name: "name",
								mapping: "sap_id_comanda"
							}
						],
						proxyUrl:  'terasamente/raport/getComenziPM'
					}
				},
				{
					id: "tipLucrareCombo",
					fieldLabel: "Tip Lucrare",
					xtype: "uxFCombo",
					name: "tip_lucrare_id",
					allowBlank: true,
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "tip_lucrare_id"
							},
							{
								name: "name",
								mapping: "lucrare_nume"
							}
						],
						proxyUrl:  'terasamente/lucrari/getLucrariDisponibileComanda'
					}
				},
				{
					fieldLabel: 'Categorie Lucrare',
					xtype: "uxFCombo",
					allowBlank: true,
					name: "categorie_lucrare",
					easyConfig: {
						mode: "local",
						localData: [
							['urgenta', 'URGENTA'],
							['programata', 'PROGRAMATA']
						]
					}
				},
				{
					fieldLabel: 'Status SEL',
					xtype: "uxFCombo",
					allowBlank: true,
					name: "status_sel",
					easyConfig: {
						mode: "local",
						localData: [
							['trs_sel_neonorat', 'Neonorat'],
							['trs_sel_anulat', 'Anulat']
						]
					}
				}
			],
			buttons: [
				{
					text:'Genereaza Raport',
					id:"genereazaRaportFL",
					handler: function () {
						if (!oFiltreCentralizatorSN.getForm().isValid()) {
							return;
						}
						var filtre=oFiltreCentralizatorSN.getItemsValues() ;
						//reglementat/nereglementat
						filtre.type=config.type;
						APP.trs_raport.getGridSelNeonorat({
							config:config,
							filtre:filtre
						});
						oWind.close();
					}
				}

			]
		});

		//constructie fereastra
		oWind = new Ext.Window({
			title: "Selectii Raport",
			width: 550,
			layout: "fit",
			modal: true,
			closeAction: "close",
			items: [oFiltreCentralizatorSN]
		}).show();

	},

	getGridSelNeonorat: function (data) {
		//APP.trs_raport.id=config.tab_id;

		if (!Ext.getCmp(APP.trs_raport.id)) {

			var oCmSelNeonorat = new Ext.grid.ColumnModel({
				columns: [
					{
						header: "Partener",
						dataIndex: 'partener_nume',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "FOL",
						dataIndex: 'fol',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Sector",
						dataIndex: 'sector',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Directie",
						dataIndex: 'regionala',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Nr. Comanda PM",
						dataIndex: 'sap_id_comanda',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Data Intrare Comanda PM in WEB",
						dataIndex: 'data_creare_comanda',
						width: 250,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Data Creare Lucrare",
						dataIndex: 'data_creare_lucrare',
						width: 150,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Tip Lucrare",
						dataIndex: 'lucrare_nume',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Categorie Lucrare",
						dataIndex: 'urgenta',
						width: 150,
						renderer: function (value, metadata, record, rowIndex, columnIndex, store) {
							if(record.get('urgenta')==1)
								return "urgenta";
							else
								return "programata";
						}
					},
					{
						header: "Localitate",
						dataIndex: 'sap_localitate',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Strada",
						dataIndex: 'sap_strada',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Nr. Strada",
						dataIndex: 'sap_strada_nr',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Judet",
						dataIndex: 'sap_judet',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "ID SEL",
						dataIndex: 'sel_nr_inregistrare',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Data transmitere SEL",
						dataIndex: 'data_transmitere_sel',
						width: 150
					},
					{
						header: "Data Marcare Lucrare Neonorata / Anulata",
						dataIndex: 'data_marcare_lucrare_neonorata',
						width: 300
					},
					{
						header: "User Marcare Lucrare Neonorata / Anulata",
						dataIndex: 'user_marcare_lucrare_neonorata',
						width: 300
					},
					{
						header: "Motiv Neefectuare Lucrare",
						dataIndex: 'motiv_neefectuare_lucrare',
						width: 200
					},
					{
						header: "Observatii Anulare / Neonorare",
						dataIndex: 'observatii_anulare',
						width: 200
					}
				],
				defaults: {
					sortable: false
				}
			});


			var oSelNeonoratGrid = new Ext.ux.fatGrid({
				xtype: "uxFatGrid",
				layout: 'fit',
				itemId: "gridSelNeonorat",
				region: 'center',
				viewConfig: {
					forceFit: false
				},
				gridConfig: {
					filterable: true,
					resetFilterButton : true,
					exportable : true,
					exportButton : true,
					url: 'terasamente/raport/getRaportSelNeonorat',
					sortField: "sel_nr_inregistrare",
					idProperty:"sel_nr_inregistrare",
					storeBaseParams:data.filtre,
					fields: ["partener_nume", "fol", "sector", "regionala",  "sap_id_comanda", "data_creare_comanda", "data_creare_lucrare", "lucrare_nume", "urgenta", "sap_localitate", "sap_strada", "sap_strada_nr", "sap_judet", "sel_nr_inregistrare", "data_transmitere_sel", "data_marcare_lucrare_neonorata", "user_marcare_lucrare_neonorata", "motiv_neefectuare_lucrare", "observatii_anulare"]
				},
				cm: oCmSelNeonorat,
				tbar: []
			});


			var oNomTab = new Ext.Panel({
				title:data.config.title,
				id: "gridSelNeonorat",
				iconCls: 'icon-fugue-table-excel',
				closable: true,
				layout: 'border',
				items: [oSelNeonoratGrid]
			});


			//adaugare tab la tabpanel-ul principal
			APP.oCenterRegion.add(oNomTab);
			APP.oCenterRegion.doLayout();
		}


		//activare tab
		APP.oCenterRegion.setActiveTab("gridSelNeonorat");

	},

	gridStandard: function(config) {
		//constructie formular
		var oWind;

		var oFiltreCentralizatorSt = new Ext.FormPanel({
			autoHeight: true,
			labelWidth: 200,
			frame: true,
			layout: "form",
			bodyStyle : {
				padding : "5px"
			},
			defaults : {
				anchor : "100%"
			},
			iconCls : 'icon-fugue-report',
			items: [
				{
					xtype: "uxSFielDdate",
					fieldLabel: "Data Interval Atacare Lucrare Desfacere",
					name: "data_interval"
				},
				{
					fieldLabel: "Constructor",
					xtype: "uxFCombo",
					name: "partener_id",
					allowBlank: !valueOfArrayinArray(["trs_constructor"], APP.user_rol),
					listWidth: 300,
					easyConfig: {
						baseParams: {
							furncl_activ: 1
						},
						readerFields: [
							{
								name: "id",
								mapping: "partener_id"
							},
							{
								name: "name",
								mapping: "partener_nume"
							}
						],
						proxyUrl: 'terasamente/raport/getConstructor'
					}
				},
				{
					id: "FOLCombo",
					fieldLabel: "FOL",
					xtype: "uxFCombo",
					name: "centru_cost",
					allowBlank: !valueOfArrayinArray(["trs_sef_fol"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "centru_cost"
							},
							{
								name: "name",
								mapping: "fol"
							}
						],
						proxyUrl:  'terasamente/raport/getFOL'
					}
				},
				{
					id: "sectorCombo",
					fieldLabel: "Sector",
					xtype: "uxFCombo",
					name: "sector",
					allowBlank: !valueOfArrayinArray(["trs_sef_sector"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "sector_id"
							},
							{
								name: "name",
								mapping: "sector"
							}
						],
						proxyUrl:  'terasamente/raport/getSector'
					}
				},
				{
					id: "directiiCombo",
					fieldLabel: "Directie Regionala",
					xtype: "uxFCombo",
					name: "regionala",
					allowBlank: !valueOfArrayinArray(["trs_sef_sector"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "regionala"
							},
							{
								name: "name",
								mapping: "regionala"
							}
						],
						proxyUrl:  'terasamente/raport/getDirectiiRegionale'
					}
				},
				{
					id: "comandaCombo",
					fieldLabel: "Comanda PM",
					xtype: "uxFCombo",
					name: "comanda_id",
					allowBlank: true,
					forceSelection: false,
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "comanda_id"
							},
							{
								name: "name",
								mapping: "sap_id_comanda"
							}
						],
						proxyUrl:  'terasamente/raport/getComenziPM'
					}
				}
			],
			buttons: [
				{
					text:'Genereaza Raport',
					id:"genereazaRaportFL",
					handler: function () {
						if (!oFiltreCentralizatorSt.getForm().isValid()) {
							return;
						}
						var filtre=oFiltreCentralizatorSt.getItemsValues() ;
						//reglementat/nereglementat
						filtre.type=config.type;
						APP.trs_raport.getGridStandard({
							config:config,
							filtre:filtre
						});
						oWind.close();
					}
				}

			]
		});

		//constructie fereastra
		oWind = new Ext.Window({
			title: "Selectii Raport",
			width: 550,
			layout: "fit",
			modal: true,
			closeAction: "close",
			items: [oFiltreCentralizatorSt]
		}).show();

	},

	getGridStandard: function(data) {
		//APP.trs_raport.id=config.tab_id;

		if (!Ext.getCmp(APP.trs_raport.id)) {

			var oCmStandard = new Ext.grid.ColumnModel({
				columns: [
					{
						header: "Partener",
						dataIndex: 'partener_nume',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "FOL",
						dataIndex: 'fol',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Sector",
						dataIndex: 'sector',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Directie",
						dataIndex: 'regionala',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Nr. Comanda PM",
						dataIndex: 'sap_id_comanda',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Data Intrare Comanda PM in WEB",
						dataIndex: 'data_creare_comanda',
						width: 250,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Localitate",
						dataIndex: 'sap_localitate',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Strada",
						dataIndex: 'sap_strada',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Nr. Strada",
						dataIndex: 'sap_strada_nr',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Judet",
						dataIndex: 'sap_judet',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Data Atacare Lucrare de Desfacere",
						dataIndex: 'data_atacare_desfacere',
						width: 250,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Data Atacare Lucrare de Desfacere DGSR",
						dataIndex: 'data_atacare_desfacere_dgsr',
						width: 250,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Data Finalizare Lucrare de Refacere",
						dataIndex: 'data_finalizare_refacere',
						width: 250,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Data Atacare Lucrare de Refacere",
						dataIndex: 'data_atacare_refacere',
						width: 250,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Calcul termen",
						dataIndex: 'calcul_termen',
						width: 150,
						sortable: false,
						filter: {
							type: 'string'
						}
					}
				],
				defaults: {
					sortable: false
				}
			});


			var oStandardGrid = new Ext.ux.fatGrid({
				xtype: "uxFatGrid",
				layout: 'fit',
				itemId: "gridStandard",
				region: 'center',
				viewConfig: {
					forceFit: false
				},
				gridConfig: {
					filterable: true,
					resetFilterButton : true,
					exportable : true,
					exportButton : true,
					url: 'terasamente/raport/getRaportStandard',
					sortField: "sap_id_comanda",
					idProperty:"sap_id_comanda",
					storeBaseParams:data.filtre,
					fields: ["partener_nume", "fol", "sector", "regionala",  "sap_id_comanda", "data_creare_comanda", "sap_localitate", "sap_strada", "sap_strada_nr", "sap_judet", "data_atacare_desfacere", "data_atacare_desfacere_dgsr", "data_finalizare_refacere", "data_atacare_refacere", "calcul_termen"]
				},
				cm: oCmStandard,
				tbar: []
			});


			var oNomTab = new Ext.Panel({
				title:data.config.title,
				id: "gridStandard",
				iconCls: 'icon-fugue-table-excel',
				closable: true,
				layout: 'border',
				items: [oStandardGrid]
			});


			//adaugare tab la tabpanel-ul principal
			APP.oCenterRegion.add(oNomTab);
			APP.oCenterRegion.doLayout();
		}

		//activare tab
		APP.oCenterRegion.setActiveTab("gridStandard");
	},

	gridTermeneExecutie: function(config) {
		//constructie formular
		var oWind;

		var oFiltreCentralizatorTE = new Ext.FormPanel({
			autoHeight: true,
			labelWidth: 200,
			frame: true,
			layout: "form",
			bodyStyle : {
				padding : "5px"
			},
			defaults : {
				anchor : "100%"
			},
			iconCls : 'icon-fugue-report',
			items: [
				{
					xtype: "uxSFielDdate",
					fieldLabel: "Data Interval solicitare SEL",
					name: "data_interval"
				},
				{
					fieldLabel: "Constructor",
					xtype: "uxFCombo",
					name: "partener_id",
					allowBlank: !valueOfArrayinArray(["trs_constructor"], APP.user_rol),
					listWidth: 300,
					easyConfig: {
						baseParams: {
							furncl_activ: 1
						},
						readerFields: [
							{
								name: "id",
								mapping: "partener_id"
							},
							{
								name: "name",
								mapping: "partener_nume"
							}
						],
						proxyUrl: 'terasamente/raport/getConstructor'
					}
				},
				{
					id: "FOLCombo",
					fieldLabel: "FOL",
					xtype: "uxFCombo",
					name: "centru_cost",
					allowBlank: !valueOfArrayinArray(["trs_sef_fol"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "centru_cost"
							},
							{
								name: "name",
								mapping: "fol"
							}
						],
						proxyUrl:  'terasamente/raport/getFOL'
					}
				},
				{
					id: "sectorCombo",
					fieldLabel: "Sector",
					xtype: "uxFCombo",
					name: "sector",
					allowBlank: !valueOfArrayinArray(["trs_sef_sector"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "sector_id"
							},
							{
								name: "name",
								mapping: "sector"
							}
						],
						proxyUrl:  'terasamente/raport/getSector'
					}
				},
				{
					id: "directiiCombo",
					fieldLabel: "Directie Regionala",
					xtype: "uxFCombo",
					name: "regionala",
					allowBlank: !valueOfArrayinArray(["trs_sef_sector"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "regionala"
							},
							{
								name: "name",
								mapping: "regionala"
							}
						],
						proxyUrl:  'terasamente/raport/getDirectiiRegionale'
					}
				},
				{
					id: "comandaCombo",
					fieldLabel: "Comanda PM",
					xtype: "uxFCombo",
					name: "comanda_id",
					allowBlank: true,
					forceSelection: false,
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "comanda_id"
							},
							{
								name: "name",
								mapping: "sap_id_comanda"
							}
						],
						proxyUrl:  'terasamente/raport/getComenziPM'
					}
				},
				{
					id: "tipLucrareCombo",
					fieldLabel: "Tip Lucrare",
					xtype: "uxFCombo",
					name: "tip_lucrare_id",
					allowBlank: true,
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "tip_lucrare_id"
							},
							{
								name: "name",
								mapping: "lucrare_nume"
							}
						],
						proxyUrl:  'terasamente/lucrari/getLucrariDisponibileComanda'
					}
				},
				{
					fieldLabel: 'Categorie Lucrare',
					xtype: "uxFCombo",
					allowBlank: true,
					name: "categorie_lucrare",
					easyConfig: {
						mode: "local",
						localData: [
							['urgenta', 'URGENTA'],
							['programata', 'PROGRAMATA']
						]
					}
				},
				{
					fieldLabel: 'Status Lucrare',
					xtype: "uxFCombo",
					allowBlank: true,
					name: "status_lucrare",
					easyConfig: {
						mode: "local",
						localData: [
							['trs_lucrare_noua', 'Noua'],
							['trs_lucrare_demarata', 'Demarata'],
							['trs_lucrare_anulata', 'Anulata'],
							['trs_lucrare_neonorata', 'Neonorata'],
							['trs_lucrare_finalizata_in_teren', 'Finalizata in Teren'],
							['trs_lucrare_finalizata_cu_sl', 'Finalizata cu SL'],
							['trs_lucrare_finalizata_cu_sl_registratura', 'Finalizata cu SL Registratura'],
							['trs_lucrare_finalizata_cu_migo', 'Finalizata cu MIGO'],
							['trs_lucrare_fara_pvrf', 'Fara PVRF'],
							['trs_lucrare_cu_pvrf', 'Cu PVRF']
						]
					}
				}
			],
			buttons: [
				{
					text:'Genereaza Raport',
					id:"genereazaRaportFL",
					handler: function () {
						if (!oFiltreCentralizatorTE.getForm().isValid()) {
							return;
						}
						var filtre=oFiltreCentralizatorTE.getItemsValues() ;
						//reglementat/nereglementat
						filtre.type=config.type;
						APP.trs_raport.getGridTermeneExecutie({
							config:config,
							filtre:filtre
						});
						oWind.close();
					}
				}

			]
		});

		//constructie fereastra
		oWind = new Ext.Window({
			title: "Selectii Raport",
			width: 550,
			layout: "fit",
			modal: true,
			closeAction: "close",
			items: [oFiltreCentralizatorTE]
		}).show();

	},

	getGridTermeneExecutie: function(data) {
		//APP.trs_raport.id=config.tab_id;

		if (!Ext.getCmp(APP.trs_raport.id)) {

			var oCmTermeneExecutie = new Ext.grid.ColumnModel({
				columns: [
					{
						header: "Partener",
						dataIndex: 'partener_nume',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "FOL",
						dataIndex: 'fol',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Sector",
						dataIndex: 'sector',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Directie",
						dataIndex: 'regionala',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Nr. Comanda PM",
						dataIndex: 'sap_id_comanda',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Data Intrare Comanda PM in WEB",
						dataIndex: 'data_creare_comanda',
						width: 250,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Data Creare Lucrare",
						dataIndex: 'data_creare_lucrare',
						width: 150,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Tip Lucrare",
						dataIndex: 'lucrare_nume',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Categorie Lucrare",
						dataIndex: 'urgenta',
						width: 150,
						renderer: function (value, metadata, record, rowIndex, columnIndex, store) {
							if(record.get('urgenta')==1)
								return "urgenta";
							else
								return "programata";
						}
					},
					{
						header: "Selectie lucrare",
						dataIndex: 'selectie_lucrare',
						width: 150
					},
					{
						header: "Localitate",
						dataIndex: 'sap_localitate',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Strada",
						dataIndex: 'sap_strada',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Nr. Strada",
						dataIndex: 'sap_strada_nr',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Judet",
						dataIndex: 'sap_judet',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "ID SEL",
						dataIndex: 'sel_nr_inregistrare',
						width: 150
					},
					{
						header: "Data transmitere SEL",
						dataIndex: 'data_transmitere_sel',
						width: 150
					},
					{
						header: "Lucrare depasita",
						dataIndex: 'lucrare_depasita',
						width: 150
					},
					{
						header: "Data Solicitare Lucrare (PV)",
						dataIndex: 'data_solicitare_lucrare_pv',
						width: 250
					},
					{
						header: "Data Atacare Lucrare (PV)",
						dataIndex: 'data_atacare_lucrare_pv',
						width: 250
					},
					{
						header: "Data Solicitare Lucrare (PVRT)",
						dataIndex: 'data_solicitare_lucrare_pvrt',
						width: 250
					},
					{
						header: "Data Atacare Lucrare (PVRT)",
						dataIndex: 'data_atacare_lucrare_pvrt',
						width: 250
					},
					{
						header: "Timp de depasire atacare (min)",
						dataIndex: 'timp_depasire_atacare',
						width: 150
					},
					{
						header: "Data Intocmire PV",
						dataIndex: 'data_intocmire_pv',
						width: 150
					},
					{
						header: "Data Intocmire PVRT",
						dataIndex: 'data_intocmire_pvrt',
						width: 150
					},
					{
						header: "Data Registratura SL",
						dataIndex: 'data_registratura_sl',
						width: 150
					},
					{
						header: "Timp de depasire depunere la Registratura (min)",
						dataIndex: 'timp_depasire_depunere_registratura',
						width: 250
					},
					{
						header: "Nr. puncte penalizare",
						dataIndex: 'nr_pct_penalizare',
						width: 150
					},
					{
						header: "Valoare Penalizare (lei)",
						dataIndex: 'valoare_penalizare',
						width: 150
					},
					{
						header: "Nr. puncte bonusare",
						dataIndex: 'nr_pct_bonusare',
						width: 150
					},
					{
						header: "Valoare Bonus",
						dataIndex: 'valoare_bonus',
						width: 150
					},
					{
						header: "Data atasare PVRF",
						dataIndex: 'data_atasare_pvrf',
						width: 150
					},
					{
						header: "Data MIGO",
						dataIndex: 'data_migo',
						width: 150
					},
					{
						header: "Data validare SL",
						dataIndex: 'data_validare_sl',
						width: 150
					},
					{
						header: "Valoare SL",
						dataIndex: 'valoare_sl',
						width: 150
					}
				],
				defaults: {
					sortable: false
				}
			});


			var oTermeneExecutieGrid = new Ext.ux.fatGrid({
				xtype: "uxFatGrid",
				layout: 'fit',
				itemId: "gridTermeneExecutie",
				region: 'center',
				viewConfig: {
					forceFit: false
				},
				gridConfig: {
					filterable: true,
					resetFilterButton : true,
					exportable : true,
					exportButton : true,
					url: 'terasamente/raport/getRaportTermeneExecutie',
					sortField: "lucrare_id",
					idProperty:"lucrare_id",
					storeBaseParams:data.filtre,
					fields: ["partener_nume", "fol", "sector", "regionala",  "sap_id_comanda", "data_creare_comanda", "data_creare_lucrare", "lucrare_nume", "urgenta", "sap_localitate", "sap_strada", "sap_strada_nr", "sap_judet", "sel_nr_inregistrare", "data_transmitere_sel", "lucrare_depasita", "data_solicitare_lucrare_pv", "data_solicitare_lucrare_pvrt", "data_atacare_lucrare_pv", "data_atacare_lucrare_pvrt", "timp_depasire_atacare", "data_intocmire_pv", "data_intocmire_pvrt", "data_registratura_sl", "timp_depasire_depunere_registratura", "nr_pct_penalizare", "valoare_penalizare", "nr_pct_bonusare", "valoare_bonus", "data_atasare_pvrf", "selectie_lucrare", "data_migo", "data_validare_sl", "valoare_sl"]
				},
				cm: oCmTermeneExecutie,
				tbar: []
			});


			var oNomTab = new Ext.Panel({
				title:data.config.title,
				id: "gridTermeneExecutie",
				iconCls: 'icon-fugue-table-excel',
				closable: true,
				layout: 'border',
				items: [oTermeneExecutieGrid]
			});

			//adaugare tab la tabpanel-ul principal
			APP.oCenterRegion.add(oNomTab);
			APP.oCenterRegion.doLayout();
		}

		//activare tab
		APP.oCenterRegion.setActiveTab("gridTermeneExecutie");
	},

	gridTehnicFinanciar: function(config) {
		//constructie formular
		var oWind;

		var oFiltreCentralizatorTF = new Ext.FormPanel({
			autoHeight: true,
			labelWidth: 200,
			frame: true,
			layout: "form",
			bodyStyle : {
				padding : "5px"
			},
			defaults : {
				anchor : "100%"
			},
			iconCls : 'icon-fugue-report',
			items: [
				{
					xtype: "uxSFielDdate",
					fieldLabel: "Data Interval SL",
					name: "data_interval_sl"
				},
				{
					xtype: "xdatetime",
					fieldLabel: "Data SEL",
					name: "data_sel"
				},
				{
					fieldLabel: "Constructor",
					xtype: "uxFCombo",
					name: "partener_id",
					allowBlank: !valueOfArrayinArray(["trs_constructor"], APP.user_rol),
					listWidth: 300,
					easyConfig: {
						baseParams: {
							furncl_activ: 1
						},
						readerFields: [
							{
								name: "id",
								mapping: "partener_id"
							},
							{
								name: "name",
								mapping: "partener_nume"
							}
						],
						proxyUrl: 'terasamente/raport/getConstructor'
					}
				},
				{
					id: "FOLCombo",
					fieldLabel: "FOL",
					xtype: "uxFCombo",
					name: "centru_cost",
					allowBlank: !valueOfArrayinArray(["trs_sef_fol"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "centru_cost"
							},
							{
								name: "name",
								mapping: "fol"
							}
						],
						proxyUrl:  'terasamente/raport/getFOL'
					}
				},
				{
					id: "sectorCombo",
					fieldLabel: "Sector",
					xtype: "uxFCombo",
					name: "sector",
					allowBlank: !valueOfArrayinArray(["trs_sef_sector"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "sector_id"
							},
							{
								name: "name",
								mapping: "sector"
							}
						],
						proxyUrl:  'terasamente/raport/getSector'
					}
				},
				{
					id: "directiiCombo",
					fieldLabel: "Directie Regionala",
					xtype: "uxFCombo",
					name: "regionala",
					allowBlank: !valueOfArrayinArray(["trs_sef_sector"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "regionala"
							},
							{
								name: "name",
								mapping: "regionala"
							}
						],
						proxyUrl:  'terasamente/raport/getDirectiiRegionale'
					}
				},
				{
					id: "comandaCombo",
					fieldLabel: "Comanda PM",
					xtype: "uxFCombo",
					name: "comanda_id",
					allowBlank: true,
					forceSelection: false,
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "comanda_id"
							},
							{
								name: "name",
								mapping: "sap_id_comanda"
							}
						],
						proxyUrl:  'terasamente/raport/getComenziPM'
					}
				},
				{
					id: "tipLucrareCombo",
					fieldLabel: "Tip Lucrare",
					xtype: "uxFCombo",
					name: "tip_lucrare_id",
					allowBlank: true,
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "tip_lucrare_id"
							},
							{
								name: "name",
								mapping: "lucrare_nume"
							}
						],
						proxyUrl:  'terasamente/lucrari/getLucrariDisponibileComanda'
					}
				}
			],
			buttons: [
				{
					text:'Genereaza Raport',
					id:"genereazaRaportFL",
					handler: function () {
						if (!oFiltreCentralizatorTF.getForm().isValid()) {
							return;
						}
						var filtre=oFiltreCentralizatorTF.getItemsValues() ;
						//reglementat/nereglementat
						filtre.type=config.type;
						APP.trs_raport.getGridTehnicFinanciar({
							config:config,
							filtre:filtre
						});
						oWind.close();
					}
				}

			]
		});

		//constructie fereastra
		oWind = new Ext.Window({
			title: "Selectii Raport",
			width: 550,
			layout: "fit",
			modal: true,
			closeAction: "close",
			items: [oFiltreCentralizatorTF]
		}).show();

	},

	getGridTehnicFinanciar: function(data) {

		//APP.trs_raport.id=config.tab_id;

		if (!Ext.getCmp(APP.trs_raport.id)) {

			var oCmTehnicFinanciar = new Ext.grid.ColumnModel({
				columns: [
					{
						header: "Partener",
						dataIndex: 'partener_nume',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "FOL",
						dataIndex: 'fol',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Sector",
						dataIndex: 'sector',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Directie",
						dataIndex: 'regionala',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Nr. Comanda PM",
						dataIndex: 'sap_id_comanda',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "ID echipament",
						dataIndex: 'sap_id_echipament',
						width: 150,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Tip Defect",
						dataIndex: 'sap_anomalie',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Data Creare Lucrare",
						dataIndex: 'data_creare_lucrare',
						width: 150,
						sortable: true,
						filter: {
							type: 'date'
						}
					},
					{
						header: "Tip Lucrare",
						dataIndex: 'lucrare_nume',
						width: 350,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Categorie Lucrare",
						dataIndex: 'urgenta',
						width: 150,
						renderer: function (value, metadata, record, rowIndex, columnIndex, store) {
							if(record.get('urgenta')==1)
								return "urgenta";
							else
								return "programata";
						}
					},
					{
						header: "Localitate",
						dataIndex: 'sap_localitate',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Strada",
						dataIndex: 'sap_strada',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Nr. Strada",
						dataIndex: 'sap_strada_nr',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "Judet",
						dataIndex: 'sap_judet',
						width: 150,
						sortable: true,
						filter: {
							type: 'string'
						}
					},
					{
						header: "ID SEL",
						dataIndex: 'sel_nr_inregistrare',
						width: 150
					},
					{
						header: "ID SL",
						dataIndex: 'sl_nr_inregistrare',
						width: 150
					},
					{
						header: "Data validare SL",
						dataIndex: 'data_validare_sl',
						width: 150
					},
					{
						header: "Data Generare Centralizator MM",
						dataIndex: 'data_generare_centralizator_mm',
						width: 250
					},
					{
						header: "Data Creare Comanda MM",
						dataIndex: 'data_creare_comanda_mm',
						width: 250
					},
					{
						header: "Data MIGO",
						dataIndex: 'data_migo',
						width: 150
					},
					{
						header: "Nr. puncte SL",
						dataIndex: 'nr_pct_sl',
						width: 150
					},
					{
						header: "Nr. puncte penalizare",
						dataIndex: 'nr_pct_penalizare',
						width: 150
					},
					{
						header: "Valoare SL (RON)",
						dataIndex: 'valoare_sl',
						width: 150
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 1",
						dataIndex: 'suprafata_desfacere_zona_1',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 2",
						dataIndex: 'suprafata_desfacere_zona_2',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 3",
						dataIndex: 'suprafata_desfacere_zona_3',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 4",
						dataIndex: 'suprafata_desfacere_zona_4',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 5",
						dataIndex: 'suprafata_desfacere_zona_5',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 6",
						dataIndex: 'suprafata_desfacere_zona_6',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 7",
						dataIndex: 'suprafata_desfacere_zona_7',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 8",
						dataIndex: 'suprafata_desfacere_zona_8',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 9",
						dataIndex: 'suprafata_desfacer_zona_9',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 10",
						dataIndex: 'suprafata_desfacere_zona_10',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 11",
						dataIndex: 'suprafata_desfacere_zona_11',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 12",
						dataIndex: 'suprafata_desfacere_zona_12',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 13",
						dataIndex: 'suprafata_desfacere_zona_13',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 14",
						dataIndex: 'suprafata_desfacere_zona_14',
						width: 250
					},
					{   // pentru desfacere / refacere
						header: "Suprafata desfacere / refacere Zona 15",
						dataIndex: 'suprafata_desfacere_zona_15',
						width: 250
					},
					{   // SL CV_GN_Macara
						header: "Nr. capace GN / camine / ore ridicare manevrare/ ..",
						dataIndex: 'nr_capace',
						width: 250
					},
					{   // foraj orizontal
						header: "Diametrul Tub Protectie",
						dataIndex: 'diametru_tub_protectie',
						width: 150
					},
					{   // foraj orizontal
						header: "ml",
						dataIndex: 'ml',
						width: 150
					},
					{
						header: "Volum Evacuare Deseuri (MC/T)",
						dataIndex: 'volum_evacuare_deseuri',
						width: 250
					}
				],
				defaults: {
					sortable: false
				}
			});


			var oTehnicFinanciarGrid = new Ext.ux.fatGrid({
				xtype: "uxFatGrid",
				layout: 'fit',
				itemId: "gridTehnicFinanciar",
				region: 'center',
				viewConfig: {
					forceFit: false
				},
				gridConfig: {
					filterable: true,
					resetFilterButton : true,
					exportable : true,
					exportButton : true,
					url: 'terasamente/raport/getRaportTehnicFinanciar',
					sortField: "lucrare_id",
					idProperty:"lucrare_id",
					storeBaseParams:data.filtre,
					fields: ["partener_nume", "fol", "sector", "regionala",  "sap_id_comanda", "sap_id_echipament", "sap_anomalie", "data_creare_lucrare", "lucrare_nume", "urgenta", "sap_localitate", "sap_strada", "sap_strada_nr", "sap_judet", "sel_nr_inregistrare", "sl_nr_inregistrare", "data_validare_sl", "data_generare_centralizator_mm", "data_creare_comanda_mm", "data_migo", "nr_pct_sl", "nr_pct_penalizare", "valoare_sl", "suprafata_desfacere_zona_1", "suprafata_desfacere_zona_2", "suprafata_desfacere_zona_3", "suprafata_desfacere_zona_4", "suprafata_desfacere_zona_5", "suprafata_desfacere_zona_6", "suprafata_desfacere_zona_7", "suprafata_desfacere_zona_8", "suprafata_desfacere_zona_9", "suprafata_desfacere_zona_10", "suprafata_desfacere_zona_11", "suprafata_desfacere_zona_12", "suprafata_desfacere_zona_13", "suprafata_desfacere_zona_14", "suprafata_desfacere_zona_15", "diametru_tub_protectie", "nr_capace", "ml", "volum_evacuare_deseuri"]
				},
				cm: oCmTehnicFinanciar,
				tbar: []
			});

			var oNomTab = new Ext.Panel({
				title:data.config.title,
				id: "gridTehnicFinanciar",
				iconCls: 'icon-fugue-table-excel',
				closable: true,
				layout: 'border',
				items: [oTehnicFinanciarGrid]
			});

			//adaugare tab la tabpanel-ul principal
			APP.oCenterRegion.add(oNomTab);
			APP.oCenterRegion.doLayout();
		}

		//activare tab
		APP.oCenterRegion.setActiveTab("gridTehnicFinanciar");
	},

	getFiltreGraficValoareLucrari: function(config){
//constructie formular
		var oWind;
		var oFiltreGraficValoareLucrari = new Ext.FormPanel({
			autoHeight: true,
			frame: true,
			layout: "form",
			bodyStyle : {
				padding : "5px"
			},
			defaults : {
				anchor : "100%"
			},
			iconCls : 'icon-fugue-report',
			items: [
				{
					xtype: "uxSFielDdate",
					fieldLabel: "Data Interval",
					name: "data_interval",
					format : "m.Y",
					allowBlank:false
				}
			],
			buttons: [
				{
					text:'Genereaza Grafic',
					id:"genereazaGraficSL",
					handler: function () {
						if (!oFiltreGraficValoareLucrari.getForm().isValid()) {
							return;
						}
						var filtre=oFiltreGraficValoareLucrari.getItemsValues() ;
						APP.trs_raport.getGraficValoareLucrari({
							filtre:filtre
						});
						oWind.close();
					}
				}

			]
		});

		//constructie fereastra
		oWind = new Ext.Window({
			title: "Selectii Grafic",
			width: 450,
			layout: "fit",
			modal: true,
			closeAction: "close",
			items: [oFiltreGraficValoareLucrari]
		}).show();
	},

	getGraficValoareLucrari: function(data){
		var oWind;
		if (!Ext.getCmp(APP.trs_raport.id)) {
			Ext.Ajax.request({
				url: "terasamente/raport/getGraficValoareLucrari",
				params: {
					data_interval: data.filtre.data_interval
				},
				success: function (response) {
					var store = new Ext.data.JsonStore({
						fields:['directie', 'total'],
						data: Ext.decode(response.responseText)
					});

					var oCmLucrari = new Ext.grid.ColumnModel({
						columns: [
							{
								header: "Luna",
								dataIndex: 'name',
								width: 150,
								sortable: true,
								filter: {
									type: 'string'
								}
							},
							{
								header: "D. Bucuresti",
								dataIndex: 'Bucuresti',
								width: 150,
								sortable: true,
								filter: {
									type: 'string'
								}
							},
							{
								header: "D. Vest",
								dataIndex: 'Vest',
								width: 150,
								sortable: true,
								filter: {
									type: 'string'
								}
							},
							{
								header: "D. Est",
								dataIndex: 'Est',
								width: 150,
								sortable: true,
								filter: {
									type: 'string'
								}
							},
							{
								header: "D. Centru",
								dataIndex: 'Centru',
								width: 150,
								sortable: true,
								filter: {
									type: 'string'
								}
							},
							{
								header: "DGSR",
								dataIndex: 'dgsr',
								width: 150,
								sortable: true,
								filter: {
									type: 'string'
								}
							}
						],
						defaults: {
							sortable: false
						}
					});


					var oLucrariGrid = new Ext.ux.fatGrid({
						xtype: "uxFatGrid",
						title:"Total Pe Luni",
						layout: 'fit',
						itemId: "gridValoareLucrari",
						region: 'north',
						stateful: false,
						height: 200,
						viewConfig: {
							forceFit: true
						},
						gridConfig: {
							hasBottomBar:false,
							url: 'terasamente/raport/getGridValoareLucrari',
							sortField: "luna",
							idProperty:"luna",
							storeBaseParams:data.filtre,
							fields: ["name", "Bucuresti", "Vest", "Est", "Centru", "dgsr"]
						},
						cm: oCmLucrari,
						tbar: []
					});

					var oNomTab = new Ext.Panel({
						id: "gridGrafic",
						iconCls: 'icon-fugue-table-excel',
						closable: true,
						region: 'center',
						title: 'Grafic Valoare lucrari terasamente',
						layout: 'border',
						items: [ new Ext.Panel(	{
							id: "graficValoareLucrari",
							region: 'center',
							layoutConfig: {
								padding:'5',
								pack:'center',
								align:'middle'
							},
							items:[{
								store: store,
								xtype: 'piechart',
								width:600,
								height:600,
								dataField: 'total',
								categoryField: 'directie',
								//extra styles get applied to the chart defaults
								extraStyle:
								{
									legend:
									{
										display: 'right',
										padding: 5,
										font:
										{
											family: 'Tahoma',
											size: 13
										}
									}
								}
							}]
						})
						]
					});

					var oPanelGrafic = new Ext.Panel({
						iconCls : 'icon-fugue-report',
						layout : 'border',
						height : 650,
						items : [oLucrariGrid,oNomTab]
					});

					//constructie fereastra
					oWind = new Ext.Window({
						title: "Valoare Lucrari Terasamente",
						width: 630,
						height: 840,
						layout: "fit",
						modal: true,
						closeAction: "close",
						items: [oPanelGrafic]
					}).show();
				}
			});
			}
	},

	filtreGraficPuncte: function(config){
		//constructie formular
		var oWind;
		var oFiltreGraficPuncte = new Ext.FormPanel({
			autoHeight: true,
			labelWidth: 200,
			frame: true,
			layout: "form",
			bodyStyle : {
				padding : "5px"
			},
			defaults : {
				anchor : "100%"
			},
			iconCls : 'icon-fugue-report',
			items: [
				{
					xtype: "uxSFielDdate",
					fieldLabel: "Data Interval",
					name: "data_interval",
					format : "m.Y",
					allowBlank:false
				},
				/*{
					fieldLabel: "Constructor",
					xtype: "uxFCombo",
					name: "partener_id",
					allowBlank: !valueOfArrayinArray(["trs_constructor"], APP.user_rol),
					listWidth: 300,
					easyConfig: {
						baseParams: {
							furncl_activ: 1
						},
						readerFields: [
							{
								name: "id",
								mapping: "partener_id"
							},
							{
								name: "name",
								mapping: "partener_nume"
							}
						],
						proxyUrl: 'terasamente/raport/getConstructor'
					}
				},*/
				{
					id: "sectorCombo",
					fieldLabel: "Sector",
					xtype: "uxFCombo",
					name: "sector_id",
					allowBlank: !valueOfArrayinArray(["trs_sef_sector"], APP.user_rol),
					easyConfig: {

						readerFields: [
							{
								name: "id",
								mapping: "sector_id"
							},
							{
								name: "name",
								mapping: "sector"
							}
						],
						proxyUrl:  'terasamente/raport/getSector'
					}
				}
			],
			buttons: [
				{
					text:'Genereaza Grafic',
					id:"genereazaGraficPuncte",
					handler: function () {
						if (!oFiltreGraficPuncte.getForm().isValid()) {
							return;
						}
						var filtre=oFiltreGraficPuncte.getItemsValues() ;
						APP.trs_raport.getGraficPuncte({
							config:config,
							filtre:filtre
						});
						oWind.close();
					}
				}

			]
		});

		//constructie fereastra
		oWind = new Ext.Window({
			title: "Selectii Grafic",
			width: 550,
			layout: "fit",
			modal: true,
			closeAction: "close",
			items: [oFiltreGraficPuncte]
		}).show();

	},

	getGraficPuncte: function(data){
		var oWind;
		if (!Ext.getCmp(APP.trs_raport.id)) {
			Ext.Ajax.request({
				url: "terasamente/raport/getGraficPuncte",
				params: {
					data_interval: data.filtre.data_interval,
					partener_id: data.filtre.partener_id,
					sector_id: data.filtre.sector_id
				},
				success: function (response) {
					var store = new Ext.data.JsonStore({
						fields:['name', 'factor'],
						data: Ext.decode(response.responseText)
					});


					var oCmLucrari = new Ext.grid.ColumnModel({
						columns: [
							{
								header: "Luna",
								dataIndex: 'name',
								width: 150,
								sortable: true,
								filter: {
									type: 'string'
								}
							},
							{
								header: "Valoare",
								dataIndex: 'factor',
								width: 150,
								sortable: true,
								filter: {
									type: 'string'
								}
							}
						],
						defaults: {
							sortable: false
						}
					});


					var oLucrariGrid = new Ext.ux.fatGrid({
						xtype: "uxFatGrid",
						title:"Total Pe Luni",
						layout: 'fit',
						itemId: "gridFluxLucrari",
						region: 'north',
						stateful: false,
						height: 200,
						viewConfig: {
							forceFit: true
						},
						gridConfig: {
							hasBottomBar:false,
							url: 'terasamente/raport/getGridPuncte',
							sortField: "name",
							idProperty:"name",
							storeBaseParams:data.filtre,
							fields: ["name", "factor"]
						},
						cm: oCmLucrari,
						tbar: []
					});

					var oNomTab = new Ext.Panel({
						title:"Grafic Numar Puncte",
						id: "gridGrafic",
						iconCls: 'icon-fugue-table-excel',
						closable: true,
						region: 'center',
						layout: 'border',
						items: [ new Ext.Panel(	{
							id: "newPanel",
							region: 'center',
							items: {
								xtype: 'linechart',
								store: store,
								xField: 'name',
								yField: 'factor'
							}
						})
						]
					});

					var oPanelGrafic = new Ext.Panel({
						iconCls : 'icon-fugue-report',
						layout : 'border',
						height : 550,
						items : [oLucrariGrid,oNomTab]
					});

					//constructie fereastra
					oWind = new Ext.Window({
						title: "Numar puncte/suprafata desfacuta si refacuta",
						width: 650,
						height: 650,
						layout: "fit",
						modal: true,
						closeAction: "close",
						items: [oPanelGrafic]
					}).show();
				}
			});

		}

	},

	getFiltreGraficStatusSL:function(){

		//constructie formular
		var oWind;
		var oFiltreGraficSL = new Ext.FormPanel({
			autoHeight: true,
			frame: true,
			layout: "form",
			bodyStyle : {
				padding : "5px"
			},
			defaults : {
				anchor : "100%"
			},
			iconCls : 'icon-fugue-report',
			items: [
				{
					xtype: "uxSFielDdate",
					fieldLabel: "Data Interval",
					name: "data_interval",
					format : "d.m.Y",
					allowBlank:false
				},
				{
					fieldLabel: "Constructor",
					xtype: "uxFCombo",
					name: "partener_id",
					allowBlank: !valueOfArrayinArray(["trs_constructor"], APP.user_rol),
					listWidth: 300,
					easyConfig: {
						baseParams: {
							furncl_activ: 1
						},
						readerFields: [
							{
								name: "id",
								mapping: "partener_id"
							},
							{
								name: "name",
								mapping: "partener_nume"
							}
						],
						proxyUrl: 'terasamente/raport/getConstructor'
					}
				}
			],
			buttons: [
				{
					text:'Genereaza Grafic',
					id:"genereazaGraficSL",
					handler: function () {
						if (!oFiltreGraficSL.getForm().isValid()) {
							return;
						}
						var filtre=oFiltreGraficSL.getItemsValues() ;
						APP.trs_raport.getGraficStatusValidareSL({
							filtre:filtre
						});
						oWind.close();
					}
				}

			]
		});

		//constructie fereastra
		oWind = new Ext.Window({
			title: "Selectii Grafic",
			width: 450,
			layout: "fit",
			modal: true,
			closeAction: "close",
			items: [oFiltreGraficSL]
		}).show();

	},
	getGraficStatusValidareSL: function(data){
		Ext.Ajax.request({
			url: "terasamente/raport/getGraficSL",
			params: {
				data_interval: data.filtre.data_interval,
				partener_id: data.filtre.partener_id
			},
			success: function (response) {
				var store = new Ext.data.JsonStore({
					fields:['name', 'validate', 'nevalidate'],
					data: Ext.decode(response.responseText)
				});

				var oNomTab = new Ext.Panel({
					id: "gridGrafic",
					iconCls: 'icon-fugue-table-excel',
					closable: true,
					layout: 'border',
					items: [
						new Ext.Panel(	{
						id: "newPanel",
						region: 'center',
							items: {
								xtype: 'columnchart',
								store: store,
								tipRenderer : function(chart, record, index, series){
									if(series.yField == 'validate'){
										return "In sectorul "+ record.data.name+" s-au validat "+Ext.util.Format.number(record.data.validate, '0,0') + ' SL ';
									}else{
										return "In sectorul "+ record.data.name+" sunt nevalidate "+Ext.util.Format.number(record.data.nevalidate, '0,0') + ' SL ';
									}
								},
								series: [
									{
										type: 'column',
										displayName: 'SL Validat',
										yField: 'validate',
										xField: 'name',
										style: {
											color:0x99BBE8
										}
									},
									{
										type: 'column',
										displayName: 'SL Nevalidat',
										yField: 'nevalidate',
										xField: 'name'
									}

								],
								extraStyle: {
									legend: {
										display: 'top'
									}
								}
							}
					})
					]
				});


				//constructie fereastra
				oWind = new Ext.Window({
					title: "Grafic Situatii lucrari Validate/Nevalidate",
					width: 830,
					height: 630,
					layout: "fit",
					modal: true,
					closeAction: "close",
					items: [oNomTab]
				}).show();
			}
		});

	}

});
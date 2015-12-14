/*global APP,Ext,fnCompleteForm,console*/


/*global Ext, APP*/
Ext.ns('APP.trs_sel');
Ext.apply(APP.trs_sel, {


	id: "selPanel",

	getLogSEL: function(config){
		try {

			var oCm = new Ext.grid.ColumnModel({
				columns : [ {
					header : "Actiune",
					dataIndex : 'actiune_nume',
					width : 300
				}, {
					header : "Data",
					width : 150,
					dataIndex : 'ac_stamp',
					renderer : APP.renderer.dateBusiness,
					filter : {
						type : 'date'
					}
				}, {
					header : "User",
					dataIndex : 'user_alias'
				}, {
					header: "Status Nou",
					dataIndex: 'new_status'
				}, {
					header: "Status Vechi",
					dataIndex: 'old_status'
				}
				],
				defaults : {
					sortable : true
				}
			});

			var oGrid = new Ext.ux.fatGrid({

				xtype : "uxFatGrid",
				layout : 'fit',
				itemId : "oGrid",
				region : 'center',
				gridConfig : {
					idProperty : 'ac_id',
					url : 'terasamente/sel/getSelLog',
					sortField : "ac_stamp",
					sortDir : "DESC",
					storeBaseParams : {
						sel_id : config.sel_id
					},
					fields : ["actiune_nume", 'ac_stamp', 'user_alias', 'new_status', 'old_status']
				},
				cm : oCm
			});

			if (!Ext.getCmp("sel_log_" + config.sel_id)) {
				//constructie fereastra
				oWind = new Ext.Window({
					title : "Istoric " + config.nr_inregistrare,
					width : 900,
					height : 500,
					id : "sel_log_" + config.sel_id,
					closable : true,
					layout : 'fit',
					modal : true,
					closeAction : 'close',
					items : [oGrid]
				}).show();
			} else {
				Ext.getCmp("SEL_" + config.sel_id).show();
			}
		} catch (er) {
			console.log(er);
		}
	},

	//popup  de editare SEL. daca se face un sel cu mai multe lucrari atunci pop-upul va cuprinde ambele seluri.
	editareSEL:function(sel_id){

		var oFpPrelucrare = new Ext.FormPanel({
			//autoHeight : true,
			labelWidth: 300,
			title: "Atribute SEL " + sel_id,
			iconCls: 'icon-fugue-blog',
			frame: true,
			autoHeight: true,
			region: "center",
			bodyStyle: {
				padding: "5px"
			},
			layout: 'form',
			defaults: {
				width: 300,
				xtype: "numberfield",
				anchor : "100%"
			},
			items: [

			]
		});

		if(sel_id){
			Ext.MessageBox.wait('Loading');
			Ext.Ajax.request({
				url: "terasamente/lucrari/getLucrareBySel",
				params: {
					sel_id: sel_id
				},
				success: function (response) {
					Ext.MessageBox.hide();


					aLucrari = Ext.decode(response.responseText);

					var disabledForm=false;
					for(i=0;i<aLucrari.nr_lucrari;i++){
						if(aLucrari[i].isSelEditable.error && !(inArray('admin', APP.user_rol)))
							disabledForm=true;

						oFpPrelucrare.add(APP.trs_sel.creazaFormPrelucrareSel(i, aLucrari));

					}
					//adminul are voie sa o editeze mereu
					if(disabledForm){
						oFpPrelucrare.getForm().items.each(function(itm){
							itm.setDisabled(true);
						});
					}

					oFpPrelucrare.doLayout();

					//constructie fereastra
					var editSELWindow = new Ext.Window({
						id:"editSELWindow",
						title: "Editare SEL",
						width:800,
						modal: true,
						closable : true,
						layout : 'fit',
						closeAction: 'close',
						buttons: [
							{
								hidden:(disabledForm),
								text: "Salveaza datele",
								iconCls: 'icon-fugue-pencil',
								region: center,
								listeners: {
									click: function () {

										if (!oFpPrelucrare.getForm().isValid()) {
											return;
										}
										Ext.Ajax.request({
											url: 'terasamente/lucrari/seteazaInformatiiSel',
											params: {
												sel_inf_tehnice: Ext.encode(oFpPrelucrare.getForm().getValues())
											}
										});
									}
								}
							}
						],
						items: [oFpPrelucrare]
					}).show();
				}
				});
		}else{
			APP.events.throwError("Eroare: SEL ID incorect");
			return false;
		}

	},

	getSelGrid:function(config){
		var oSelGrid = new Ext.ux.fatGrid({
			xtype: "uxFatGrid",
			stateful: false,
			height: (config.height ? config.height : "500"),
			title :  (config.title ? config.title  : "Documente SEL" ),
			id:  (config.selGridId ? config.selGridId  : "selGrid" ),
			loadMask:{
				msg:"Incarc lista de documente"
			},
			autoHeight: false,
			layout    : 'fit',
			viewConfig: {
				getRowClass: function(record, rowIndex, rp, ds) {
					var status_id = record.get('status_id');
					//sel nou
					if (status_id==3007) {
						return 'sel_nou_grid_row';
					}
				}
			},
			gridConfig: {
				filterable: true,
				resetFilterButton: true,
				url: 'terasamente/sel/getSels',
				sortField: "data_creare",
				sortDir:"DESC",
				idProperty:"sel_id",
				storeBaseParams:{"lucrare_id":config.lucrare_id,status_id:config.status_id},
				fields: ['sel_id',"status_nume","status_id","status_cod", "data_creare", 'nr_inregistrare',"notificare_constructor","notificare_sef", "solicitare_email",
					"email_citit","atasament_semnat","pv_id","pvrt_id","canUploadSelSemnat","canSendSelEmail","canUploadPv","mesaj_anulare"]
			},
			tbar: (config.tbar ? config.tbar : [
				{
					text: "Adauga SEL",
					iconCls: 'icon-fugue-plus-circle',
					hidden: (inArray('trs_constructor', APP.user_rol) ? true : false),
					handler: function(){
						APP.trs_sel.creareSel(config.comanda_id, oSelGrid);
					}
				},
				{
					text: "Anuleaza SEL",
					iconCls: 'icon-fugue-minus-circle',
					hidden: (inArray('trs_constructor', APP.user_rol) ? true : false),
					handler: function(){
						APP.trs_sel.cancelSel(oSelGrid);
					}
				},
				{
					text: "Neonoreaza SEL",
					iconCls: 'icon-fugue-minus-circle',
					hidden: (inArray('trs_constructor', APP.user_rol) ? true : false),
					handler: function(){

						Ext.MessageBox.confirm("Confirmare",
							"Sigur doriti setarea SEL-ului ca fiind neonorat?", function(btn) {
							if (btn === "yes") {
								APP.trs_sel.selNeonorat(oSelGrid);
							}
						});

					}
				}

			]),

			cm: new Ext.grid.ColumnModel({

				columns : [
					{
						css:"text-align:center;",
						xtype : 'actioncolumn',
						width : 80,
						header : "Document",
						items : [
							{
							tooltip : "Vizualizare  SEL",
							icon: 'resources/img/pdf.png',
							handler : function(grid, rowIndex, colIndex) {
								var rec = grid.store.getAt(rowIndex);
								var sel_id = rec.get('sel_id');
								APP.trs_sel.downloadSel("terasamente/sel/downloadSel",sel_id,grid, rowIndex);
							}
							},
							{
								tooltip : "Editare SEL",
								icon: 'resources/img/note_pencil.png',
								handler : function(grid, rowIndex, colIndex) {
									var rec = grid.store.getAt(rowIndex);
									var sel_id = rec.get('sel_id');
									APP.trs_sel.editareSEL(sel_id);
								}
							},
							{
								icon : 'resources/img/calendar_day.png', // Use a URL in the icon config
								tooltip : "Istoric SEL",
								handler : function(grid, rowIndex, colIndex) {
									var rec = oSelGrid.store.getAt(rowIndex);
									APP.trs_sel.getLogSEL({sel_id:rec.get("sel_id"), nr_inregistrare:rec.get("nr_inregistrare")});
								}
							}]
					},
				{
						header : "Nr. SEL",
						dataIndex : 'nr_inregistrare',
						width : 150,
						filter: {
							type: 'string'
						}
				},
				{
						header : "Status",
						dataIndex : 'status_nume',
						width : 150,
						filter: {
							type: 'string'
						},
						renderer: function (value, metadata, record, rowIndex, columnIndex, store) {
							if(record.get('status_cod')=="trs_sel_anulat"){
								return value+"("+record.get("mesaj_anulare")+")";
							}
							else
								return	value;
						}

				},
				{
						header : "Data Creare",
						dataIndex : 'data_creare',
						width : 150,
						renderer : APP.renderer.dateBusiness,
						filter : {
							type : 'date'
						}
				},
				{
						css:"text-align:center;",
						header : "Notificare contructor",
						dataIndex : 'notificare_constructor',
						hidden: inArray('trs_constructor', APP.user_rol),
						width : 140,
						renderer: function(value, metaData, record, rowIndex, colIndex, store){
							var canSendSelEmail=record.get("canSendSelEmail");
								if(value==1){
									return "&nbsp;<img style='cursor: pointer' src=\"resources/img/valid.png\"/>";
								}else{
								if(canSendSelEmail.error)
									return "&nbsp;<img style='cursor: pointer' src=\"resources/img/delete.png\"/>";
								else
								return "&nbsp;<img style='cursor: pointer' src=\"resources/img/email.png\"/>";
								}
						},
						listeners: {
							click: function(column,grid,rowIndex){

								var record = grid.store.getAt(rowIndex);
								APP.trs_sel.sendSelEmail(record.get("sel_id"),grid,rowIndex,"notificare_constructor");
							}
						}
				},

				{
					css:"text-align:center;",
					header : "Notificare sef",
					dataIndex : 'notificare_sef',
					hidden: (inArray('trs_constructor', APP.user_rol) ? true : false),
					width : 100,
					renderer: function(value, metaData, record, rowIndex, colIndex, store){
						var canSendSelEmail=record.get("canSendSelEmail");
						if(value==1){
							return "&nbsp;<img style='cursor: pointer' src=\"resources/img/valid.png\"/>";
						}else{
							if(canSendSelEmail.error)
								return "&nbsp;<img style='cursor: pointer' src=\"resources/img/delete.png\"/>";
							else
								return "&nbsp;<img style='cursor: pointer' src=\"resources/img/email.png\"/>";
						}
					},
					listeners: {
						click: function(column,grid,rowIndex){

							var record = grid.store.getAt(rowIndex);
							APP.trs_sel.sendSelEmail(record.get("sel_id"),grid,rowIndex,"notificare_sef");
						}
					}
				},

				{
						css:"text-align:center;",
						header : "Email Citit",
						dataIndex : 'email_citit',
						width : 80,
						renderer: function(value, metaData, record, rowIndex, colIndex, store){
							if(value==1){
								return "&nbsp;<img style='cursor: pointer' src=\"resources/img/valid.png\"/>";
							}
							return "&nbsp;<img style='cursor: pointer' src=\"resources/img/delete.png\"/>";
						},
						listeners: {
							click: function(column,grid,rowIndex){

								var store = oSelGrid.getStore();
								var record = store.getAt(rowIndex);
								var email_citit=record.get("email_citit");

								if(email_citit==1){
									APP.events.throwInformation("Constructorul a citit emailul.", true);
								}
								else{
									APP.events.throwInformation("Constructorul inca nu a confirmat citirea emailului.", true);
								}
							}
						}
				},

				{
						css:"text-align:center;",
						width : 80,
						header : "Semnatura",
						dataIndex : 'atasament_semnat',
						renderer: function(value, metaData, record, rowIndex, colIndex, store){
									var canUploadSelSemnat=record.get("canUploadSelSemnat");
									if(value){
										return "&nbsp;<img  style='cursor: pointer' src=\"resources/img/pdf.png\"/>";
									}else{
									if(canUploadSelSemnat.error)
										return "&nbsp;<img style='cursor: pointer' src=\"resources/img/delete.png\"/>";
									else
									return "&nbsp;<img style='cursor: pointer' src=\"resources/img/add.png\"/>";
									}
							},
						listeners: {
							click: function(column,grid,rowIndex){
									var store = oSelGrid.getStore();
									var record = store.getAt(rowIndex);
									var atasament_semnat=record.get("atasament_semnat");
									var canUploadSelSemnat=record.get("canUploadSelSemnat");
									if(!atasament_semnat){
										if(canUploadSelSemnat.error){
											APP.events.throwInformation(canUploadSelSemnat.description, true);
											return false;
										}
										APP.trs_sel.uploadSelSemnat(record.get("sel_id"),grid,rowIndex);
									}
									else{
										APP.trs_sel.downloadSel("terasamente/sel/downloadSelSemnat",record.get("sel_id"),grid,rowIndex);
									}
								}
							}
				},
				{
						css:"text-align:center;",
						header : "",
						dataIndex : 'notificare_constructor',
						width : 70,
						renderer: function(value, metaData, record, rowIndex, colIndex, store){
							var status_cod=record.get("status_cod");
							if(status_cod=="trs_sel_neonorat"){
								return "&nbsp;<img style='cursor: pointer' src=\"resources/img/add.png\"/>";
							}
						},
						listeners: {
							click: function(column,grid,rowIndex){
								var record = grid.store.getAt(rowIndex);
								var status_cod=record.get("status_cod");

								if(status_cod=="trs_sel_neonorat")
									APP.trs_sel.editSel(record);
								else
									return false;
							}
						}
				}
			],
				defaults: {
					sortable: true
				}
			})
		});
		return oSelGrid;
	},

	creazaFormPrelucrareSel: function(indexZona, aLucrari){



		oSel = Ext.decode(aLucrari[indexZona].sel_informatii_tehnice);
		var zona={
			xtype: 'fieldset',
			title: 'Informatii Tehnice Sel: ' + aLucrari[indexZona].lucrare_nume,
			defaults: {
				padding:"10px",
				anchor : "100%"
			},
			items: [
				{
					xtype: "xdatetime",
					timeFormat: "H:i",
					dateFormat: "d.m.Y",
					fieldLabel: "Data Solicitare",
					name: "data_solicitare",
					allowBlank: false,
					value: oSel.data_solicitare
				},
				{
					xtype: "textfield",
					name: 'reprezentant_dgsr',
					fieldLabel: 'Reprezentant DGSR',
					allowBlank: (aLucrari[indexZona].grup_material_id==2 ? true : false),
					value: oSel.reprezentant_dgsr
				},
				{
					xtype: "textfield",
					allowBlank: (aLucrari[indexZona].grup_material_id==2 ? true : false),
					minLength: 10,
					maxLength: 10,
					name: 'telefon_reprezentant',
					fieldLabel: 'Telefon Reprezentant',
					value: oSel.telefon_reprezentant
				},
				{
					xtype: inArray(aLucrari[indexZona].tip_lucrare_id, [1, 2, 3, 17]) ? "hidden" : "numberfield",
					allowBlank: false,
					name: 'cantitate',
					fieldLabel: 'Cantitate (buc / ml / ora)',
					value: oSel.cantitate
				},
				{
					xtype: !inArray(aLucrari[indexZona].tip_lucrare_id, [1, 2, 3, 17]) ? "hidden" : "numberfield",
					allowBlank: false,
					name: 'lungime',
					fieldLabel: 'Lungime (m)',
					value: oSel.lungime
				},
				{
					xtype: !inArray(aLucrari[indexZona].tip_lucrare_id, [1, 2, 3, 17]) ? "hidden" : "numberfield",
					allowBlank: true,
					name: 'latime',
					fieldLabel: 'Latime (m)',
					value: oSel.latime
				},
				{
					xtype: !inArray(aLucrari[indexZona].tip_lucrare_id, [1, 2, 3, 17]) ? "hidden" : "numberfield",
					allowBlank: true,
					name: 'inaltime',
					fieldLabel: 'Inaltime (m)',
					value: oSel.inaltime
				},
				{
					xtype: "textfield",
					allowBlank: false,
					name: 'informatii_suplimentare_locatie',
					fieldLabel: 'Informatii Suplimentare Locatie',
					value: oSel.informatii_suplimentare_locatie,
					maxLength:50
				},
				{
					name: "lucrare_id",
					xtype: "hidden",
					value: aLucrari[indexZona].lucrare_id
				}
			]

		};

		return zona;
	},

	getPrelucrareSelForm:function(oLucrare){

		var oSelForm = new Ext.FormPanel({
			title : "Atribute SEL",
			labelWidth : 200,
			id:"TrsSelForm",
			autoHeight:true,
			layout : 'form',
			frame : true,
			bodyStyle : {
				padding : "5px"
			},
			defaults : {
				anchor : "100%"
			},
			iconCls : 'icon-fugue-report',
			items : [
				{
					xtype: 'fieldset',
					title: 'Informatii Tehnice Sel',
					defaults: {
						padding:"10px",
						anchor : "100%"
					},
					items: [
 									{
										xtype: "xdatetime",
										timeFormat: "H:i",
										dateFormat: "d.m.Y",
										fieldLabel: "Data Solicitare",
										name: "data_solicitare",
										allowBlank: false
									},
									{
										xtype: "textfield",
										name: 'reprezentant_dgsr',
										fieldLabel: 'Reprezentant DGSR',
										//la grupu de material refacere nu e obligatoriu acest camp
										allowBlank: (oLucrare.grup_material_id==2 ? true : false)
									},
									{
										xtype: "textfield",
										//la grupu de material refacere nu e obligatoriu acest camp
										allowBlank: (oLucrare.grup_material_id==2 ? true : false),
										minLength: 10,
										maxLength: 10,
										name: 'telefon_reprezentant',
										fieldLabel: 'Telefon Reprezentant'
									},
									{
										xtype: inArray(oLucrare.tip_lucrare_id, [1, 2, 3, 17]) ? "hidden" : "numberfield",
										allowBlank: false,
										name: 'cantitate',
										fieldLabel: 'Cantitate  (buc / ml / ora)'
									},
									{
										xtype: !inArray(oLucrare.tip_lucrare_id, [1, 2, 3, 17]) ? "hidden" : "numberfield",
										allowBlank: false,
										name: 'lungime',
										fieldLabel: 'Lungime (m)'
									},
									{
										xtype: !inArray(oLucrare.tip_lucrare_id, [1, 2, 3, 17]) ? "hidden" : "numberfield",
										allowBlank: true,
										name: 'latime',
										fieldLabel: 'Latime (m)'
									},
									{
										xtype: !inArray(oLucrare.tip_lucrare_id, [1, 2, 3, 17]) ? "hidden" : "numberfield",
										allowBlank: true,
										name: 'inaltime',
										fieldLabel: 'Inaltime (m)'
									},
									{
										xtype: "textfield",
										allowBlank: false,
										name: 'informatii_suplimentare_locatie',
										fieldLabel: 'Informatii Suplimentare Locatie',
										maxLength:50
									},
									{
										name: "lucrare_id",
										xtype: "hidden",
										value: oLucrare.lucrare_id
									}
							]

						}
				],

				buttons: [
				{
					text:'Salveaza',
					hidden : ((inArray('trs_constructor', APP.user_rol))  ? true : false),
					id:"SalveazaSelBtn",
					handler: function () {
						if (!oSelForm.getForm().isValid()) {
							return;
						}
						oSelForm.getForm().submit({
							url: "terasamente/lucrari/seteazaInformatiiSel",
							waitMsg:'Loading...',
							success: function (form, response) {
								APP.events.throwInformation("Cererea s-a finalizat cu succes. Urmatorul pas este sa creati documentul SEL.", true);
							}
						});
					}
				}

			]
		});


		//daca suntem pe editare, repopulam forma
		if(oLucrare.sel_informatii_tehnice){
			fnCompleteForm(oSelForm,Ext.decode(oLucrare.sel_informatii_tehnice));
		}

		//daca nu se mai poate modifica blocam fieldurile.
		//adminul are voie sa o editeze mereu
		if(oLucrare.isSelEditable.error && !inArray('admin', APP.user_rol)){
			oSelForm.getForm().items.each(function(itm){
				itm.setDisabled(true);
			});
			Ext.getCmp("SalveazaSelBtn").setDisabled(true);
		}

		return oSelForm;
	},

	cancelSel:function(selGrid){

		if (selGrid.selModel.getCount() !== 1) {
			APP.events.throwInformation("Selectati SEL-ul pe care doriti sa il anulati !", true);
			return;
		}
		sel_id = selGrid.selModel.getSelected().id;

		var cancelSelForm = new Ext.FormPanel({
			autoHeight : true,
			frame : true,
			id:"cancelSelForm",
			iconCls : 'icon-fugue-report',
			bodyStyle : {
				padding : "5px"
			},
			defaults : {
				anchor : "100%"
			},

			items: [

				{
					fieldLabel: "Mesaj Anulare",
					xtype: "uxFCombo",
					name: "motiv_id",
					allowBlank: false,
					easyConfig: {
						baseParams: {
							categorie:"sel"
						},
						readerFields: [
							{
								name: "id",
								mapping: "motiv_id"
							},
							{
								name: "name",
								mapping: "mesaj_anulare"
							}
						],
						proxyUrl:  'terasamente/nomenclator/getMotiveAnulare'
					}
				},


				],
			buttons: [
				{
					text:'Anuleaza',
					handler: function () {
						if (!cancelSelForm.getForm().isValid()) {
							return;
						}
						cancelSelForm.getForm().submit({
							params : {
								sel_id :sel_id
							},
							url: "terasamente/sel/anuleazaSel",
							success: function (form, response) {
								var oRes = Ext.decode(response.responseText);
								cancelSelWindow.close();
								selGrid.store.reload();
								APP.trs_lucrare.actualizareLucrare(APP.trs_lucrare.oLucrare.lucrare_id);
							}
						});
					}
				}
			]
		});

		cancelSelForm.doLayout();

		//constructie fereastra
		cancelSelWindow = new Ext.Window({
			title:"Motiv Anulare SEL",
			width: 300,
			layout: 'fit',
			modal: false,
			closeAction: 'close',
			items: [cancelSelForm]
		}).show();

	},

	selNeonorat:function(selGrid){

		if (selGrid.selModel.getCount() !== 1) {
			APP.events.throwInformation("Selectati SEL-ul pe care doriti sa il setati neonorat !", true);
			return;
		}
		sel_id = selGrid.selModel.getSelected().id;

		Ext.MessageBox.wait('Loading');
		Ext.Ajax.request({
			url : 'terasamente/sel/seteazaSelNeonorat',
			params : {
				sel_id : sel_id
			},
			success : function(response) {
				Ext.MessageBox.hide();

				var oRes = Ext.decode(response.responseText);
				APP.trs_lucrare.actualizareLucrare(APP.trs_lucrare.oLucrare.lucrare_id);
				selGrid.store.reload();

				if(oRes.error==0){
					APP.events.throwInformation(oRes.description, true);
				}
				if(oRes.error==1){
					APP.events.throwError(oRes.description, true);
				}
			}
		});

	},

	/**
	 *
	 * @param {Object} crc
	 * @param {Object} el - grid/buton ; in functie de acest lucru se activeaza butoanele de view/delete fisier
	 * @param {Object} rowIndex - in cazul care se trimite la 'el' gridul avem nevoie de rowindex
	 */
	uploadSelSemnat : function(sel_id, grid, rowIndex) {
		var oFpAdd = new Ext.FormPanel({
			autoHeight : true,
			labelWidth : 160,
			fileUpload : true,
			frame : true,
			bodyStyle : {
				padding : "5px"
			},
			layout : 'form',
			items : [{
				xtype : 'fileuploadfield',
				fieldLabel : 'Document',
				name : 'document',
				anchor : '90%',
				allowBlank : false,
				msgTarget : "side",
				buttonCfg : {
					text : '',
					iconCls : 'icon-fugue-plus-circle'
				}
			}],
			buttons : [{
				text : 'Salvare',
				iconCls : 'icon-fugue-disk',
				handler : function(btn) {

					if (!oFpAdd.getForm().isValid()) {
						return;
					}

					btn.disable();

					oFpAdd.getForm().submit({
						url : "terasamente/sel/uploadSelSemnat",
						params : {
							sel_id : sel_id
						},
						success : function() {
							grid.store.reload();
							oWind.close();
						}
					});
				}
			}, {
				text : 'Renunta',
				iconCls : 'icon-fugue-slash',
				handler : function() {
					oWind.close();
				}
			}, {
				text : 'Help',
				iconCls : 'icon-fugue-question',
				handler : function() {
					APP.events.throwInformation('Va trebui sa downloadati documentul SEL pdf, il printati si il semnati si apoi il uploadati aici.', true);
				}
			}]
		});

		//constructie fereastra
		var oWind = new Ext.Window({
			title : "Ataseaza SEL semnat",
			width : 450,
			layout : 'fit',
			modal : true,
			closeAction : 'close',
			items : [oFpAdd]
		}).show();

	},

	uploadDOC : function(configParams,url, grid, rowIndex) {

		var oFpAdd = new Ext.FormPanel({
			autoHeight : true,
			labelWidth : 160,
			fileUpload : true,
			frame : true,
			bodyStyle : {
				padding : "5px"
			},
			layout : 'form',
			items : [{
				xtype : 'fileuploadfield',
				fieldLabel : 'Document',
				name : 'document',
				anchor : '90%',
				allowBlank : false,
				msgTarget : "side",
				buttonCfg : {
					text : '',
					iconCls : 'icon-fugue-plus-circle'
				}
			}],
			buttons : [{
				text : 'Salvare',
				iconCls : 'icon-fugue-disk',
				handler : function(btn) {

					if (!oFpAdd.getForm().isValid()) {
						return;
					}
					btn.disable();
					oFpAdd.getForm().submit({
						url : url,
						params : configParams,
						success : function() {
							grid.store.reload();
							oWind.close();
						}
					});
				}
			}, {
				text : 'Renunta',
				iconCls : 'icon-fugue-slash',
				handler : function() {
					oWind.close();
				}
			}, {
				text : 'Help',
				iconCls : 'icon-fugue-question',
				handler : function() {
					APP.events.throwInformation("Va rugam atasati documente care sa explice motivul pentru care ati neonorat SEL-ul.",true);
				}
			}]
		});

		//constructie fereastra
		var oWind = new Ext.Window({
			title : "Adauga Document",
			width : 450,
			layout : 'fit',
			modal : true,
			closeAction : 'close',
			items : [oFpAdd]
		}).show();

	},

	downloadSel:function(url,sel_id,grid,rowIndex){
		var data = {
			sel_id: sel_id,
			export_type: "pdf"
		};
		APP.workflow.exportDoc(url, data);
	},

	downloadPV:function(url,pv_id,grid,rowIndex){
		var data = {
			pv_id: pv_id,
			export_type: "pdf"
		};
		APP.workflow.exportDoc(url, data);
	},

	downloadDoc:function(url,doc_id,grid,rowIndex){

		var data = {
			document_id: doc_id,
			export_type: "pdf"
		};
		APP.workflow.exportDoc(url, data);
	},


	sendSelEmail: function (sel_id,grid,rowIndex,destinatar){

		Ext.MessageBox.wait('Loading');
		Ext.Ajax.request({
			url : 'terasamente/sel/getEmailConfirmationMessage',
			params : {
				sel_id : sel_id,
				destinatar: destinatar
			},
			success : function(response) {
				Ext.MessageBox.hide();

				var oRes = Ext.decode(response.responseText);

				Ext.MessageBox.confirm("Confirmare",oRes.description, function(btn) {
					if (btn === "yes") {

						Ext.MessageBox.wait('Loading');
						Ext.Ajax.request({
							url : 'terasamente/sel/sendSelEmail',
							params : {
								sel_id : sel_id,
								destinatar: destinatar
							},
							success : function(response) {
									Ext.MessageBox.hide();

								var oRes = Ext.decode(response.responseText);

								if(oRes.error==0){
									APP.events.throwInformation(oRes.description, true);
								}
								if(oRes.error==1){
									APP.events.throwError(oRes.description, true);
								}

								grid.store.reload();
								APP.trs_lucrare.actualizareLucrare(APP.trs_lucrare.oLucrare.lucrare_id);
							}
						});

					}
				});

			}
		});
	},

	getDocJustificatifeGrid:function(sel_id){
		var oDocJustificative = new Ext.ux.fatGrid({
			xtype: "uxFatGrid",
			clicksToEdit : 1,
			layout : 'fit',
			width:500,
			viewConfig : {
				forceFit : true
			},
			stateful : false,
			gridConfig : {
				batch : true,
				autoSave : false,
				storeBaseParams : {
					sel_id : sel_id
				},
				hasBottomBar : true,
				sortDir : "DESC",
				url : 'terasamente/sel/getDocJustificative',
				sortField : "document_id",
				fields : ['document_id', 'sel_id','atasament','data','user_id',"user_alias"]
			},
			cm : new Ext.grid.ColumnModel({
				columns : [{
					header : "Autor",
					dataIndex : 'user_alias',
					width : 220
				},
				{
						header : "Data depunere",
						dataIndex : 'data',
						width : 200,
						renderer : APP.renderer.dateBusiness,
						filter : {
							type : 'date'
						}
				},
				{
					css:"text-align:center;",
					xtype : 'actioncolumn',
					width : 80,
					header : "Document",
					items : [{
						tooltip:"Descarca Doc",
						getClass : function(v, meta, rec) {
							return 'viewImg';
						},
						handler : function(grid, rowIndex, colIndex) {
							var rec = grid.store.getAt(rowIndex);
							var document_id = rec.get('document_id');
							APP.trs_sel.downloadDoc("terasamente/sel/downloadDocJustificativ",document_id,grid, rowIndex);
					}
				}]
				}],
				defaults : {
					sortable : false
				}
			}),
			tbar : [{
				text : "Adauga Document",
				iconCls : 'icon-fugue-plus-circle',
				handler : function() {
					APP.trs_sel.uploadDOC({sel_id:sel_id},"terasamente/sel/uploadDocJustificative",oDocJustificative);
				}
			}]
		});

		return oDocJustificative;

	},

	creareSel:function(comanda_id, grid){

		var sm = new Ext.grid.CheckboxSelectionModel(
			{
					checkOnly:true,
					listeners: {
					//blocam lucrarile anulate sau care au deja SEL
					beforerowselect : function (sm, rowIndex, keep, record) {
						var canHaveSel=record.get('canHaveSel');
						if(canHaveSel.error){
							APP.events.throwError(canHaveSel.description, true);
							return false;
						}
					}
				}
			}
		);
		var oLucrariListGrid = new Ext.ux.fatGrid({
			xtype: "uxFatGrid",
			clicksToEdit : 1,
			layout : 'fit',
			stateful : false,
			loadMask:{
				msg:"Incarc lista de lucrari"
			},
			viewConfig: {
				forceFit: true,
				getRowClass: function(record, rowIndex, rp, ds) {
					var canHaveSel = record.get('canHaveSel');
					if(canHaveSel.error){
						return "disabled-record";
					}
				}
			},
			sm:sm,
			gridConfig : {
				batch : true,
				autoSave : false,
				storeBaseParams : {
					comanda_id :comanda_id
				},
				hasBottomBar : true,
				sortDir : "DESC",
				url : 'terasamente/lucrari/getLucrariComanda',
				sortField : "comanda_id",
				fields: ['lucrare_id', 'sap_contract_mm','sap_valoare_pct',"sap_moneda","sap_valoare_tinta","sap_unit_log_desc","sap_text_scurt",
					'lucrare_nume','partener_nume',"status_nume","status_cod","data_creare","sels_nr_inregistrare","canHaveSel","mesaj_anulare"]
			},
			cm : new Ext.grid.ColumnModel({
				defaults : {
					sortable : true
				},
				columns : [
					sm,
					{
					header : "Cod",
					dataIndex : 'lucrare_id',
					width : 100
				},
					{
						header : "Lucrare Nume",
						dataIndex : 'lucrare_nume',
						width : 200,
						filter : {
							type : 'string'
						}
					},
					{
						header : "Status",
						dataIndex : 'status_nume',
						width : 150,
						filter : {
							type : 'string'
						},
						renderer: function (value, metadata, record, rowIndex, columnIndex, store) {

							if(record.get('status_cod')=="trs_sel_anulat"){
								return value+"("+record.get("mesaj_anulare")+")";
							}
							else
							return	value;
						}
					},
					{
						header : "Partener",
						dataIndex : 'partener_nume',
						width : 200,
						filter : {
							type : 'string'
						}
					},
					{
						header : "Data Creare",
						dataIndex : 'data_creare',
						width : 200,
						renderer : APP.renderer.dateBusiness,
						filter : {
							type : 'date'
						}
					}
					]

			}),
			tbar : [{
				text : "Genereaza SEL",
				iconCls : 'icon-fugue-plus-circle',
				handler:function(){
					if (oLucrariListGrid.selModel.getCount()<1) {
						APP.events.throwInformation("Selectati o activitate !", true);
						return;
					}else{
						lucrariSelectate=[];
						var rows=oLucrariListGrid.getSelectionModel().getSelections();
						Ext.iterate(rows, function(record, index) {
							lucrariSelectate.push(record.get('lucrare_id'));
						});
						//generam SEL-ul
						Ext.MessageBox.wait('Loading');
						Ext.Ajax.request({
							url : 'terasamente/sel/adaugaSel',
							params : {
								id_lucrari : Ext.encode(lucrariSelectate)
							},
							success : function(response) {
								oLucrariListGrid.getSelectionModel().clearSelections();
								Ext.MessageBox.hide();
								oLucrariListGrid.store.reload();
								var oRes = Ext.decode(response.responseText);
								APP.events.throwInformation(oRes.description, true);
								oCreareSelWindow.close();
								APP.trs_lucrare.actualizareLucrare(APP.trs_lucrare.oLucrare.lucrare_id);
								grid.store.reload();
							}
						});
					}
				}
			}]
		});

		//constructie fereastra
		oCreareSelWindow = new Ext.Window({
			title:"Alegeti Lucrarile.",
			width: 700,
			height:350,
			layout: 'fit',
			modal: true,
			closeAction: 'close',
			items: [
				oLucrariListGrid
			]
		}).show();

	},

	editSel:function(record){

		/**
		var oFpDateGenerale = new Ext.FormPanel({
			autoHeight : true,
			labelWidth : 150,
			frame : true,
			region:"center",
			iconCls : 'icon-fugue-report',
			bodyStyle : {
				padding : "5px"
			},
			defaults : {
				anchor : "100%"
			},
			layout : 'form',
			defaults : {
				readOnly : true
			},
			items : [
			{
				xtype : 'textfield',
				fieldLabel : 'Status SEL',
				value :oSel.status_nume,
				anchor : '95%'
			},
			{
					xtype : 'textfield',
					fieldLabel : 'Status SEL',
					value :oSel.status_nume,
					anchor : '95%'
			}
			]

		});

		 ***/


		//constructie fereastra
		oCreareSelWindow = new Ext.Window({
			title:record.get("nr_inregistrare") + " neonorat. Adauga Documente justificative.",
			width: 500,
			layout : 'fit',
			height : 430,
			modal: true,
			closeAction: 'close',
			items: [
				APP.trs_sel.getDocJustificatifeGrid(record.get("sel_id"))
			]
		}).show();



	}


});
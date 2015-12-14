/*global APP,Ext,fnCompleteForm,console*/


/*global Ext, APP*/
Ext.ns('APP.helpie_nomenclator');

Ext.apply(APP.helpie_nomenclator, {
    id: "nomenclator",
    gridPachete: function(config) {
        APP.helpie_nomenclator.id = config.tab_id;

        if (!Ext.getCmp(APP.helpie_nomenclator.id)) {

            var expander = new Ext.ux.grid.RowExpander({
                enableCaching: false,
                tpl: new Ext.Template('<div id="my_dir_row-{id_pachet}" ></div>')
            });
            expander.on('expand', APP.helpie_nomenclator.expandedRowPachet);
            var oCmGrupMaterial = new Ext.grid.ColumnModel({
                columns: [
                    expander,
                    {
                        header: "ID",
                        dataIndex: 'id_pachet',
                        width: 50,
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Nume pachet",
                        dataIndex: 'name',
                        width: 550,
                        editor: new Ext.form.TextField({
                            selectOnFocus: true
                        }),
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Nume pachet(engleza)",
                        dataIndex: 'name_en',
                        width: 550,
                        editor: new Ext.form.TextField({
                            selectOnFocus: true
                        }),
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Pret pachet(RON)",
                        dataIndex: 'price',
                        width: 100,
                        editor: new Ext.form.NumberField({
                            selectOnFocus: true
                        }),
                    },
                ],
                defaults: {
                    sortable: true
                }
            });
            var oGrupMaterialGrid = new Ext.ux.fastEditorGrid({
                xtype: "uxFatGrid",
                layout: 'fit',
                itemId: "pacheteGrid",
                region: 'center',
                plugins: [expander],
                viewConfig: {
                    forceFit: false
                },
                clicksToEdit: 1,
                gridConfig: {
                    filterable: false,
                    resetFilterButton: true,
                    url: 'admin/nomenclator/getPacheteGrid',
                    sortField: "id_pachet",
                    idProperty: "id_pachet",
                    fields: ['name', 'id_pachet', "price", "name_en"]
                },
                cm: oCmGrupMaterial,
                tbar: [
                    {
                        text: "Adauga Pachet",
                        iconCls: 'icon-fugue-pencil',
                        action: "add",
                        handler: APP.helpie_nomenclator.addPachet
                    },
                    {
                        handler: function() {
                            APP.helpie_nomenclator.stergePachet(oGrupMaterialGrid);
                        },
                        text: "Sterge Pachet",
                        icon: "assets/admin/resources/img/delete.png",
                    }
                ],
            }
            );
            var oNomTab = new Ext.Panel({
                title: 'Nomenclator Pachete Helpie',
                id: APP.helpie_nomenclator.id,
                iconCls: 'icon-database',
                closable: true,
                layout: 'border',
                items: [oGrupMaterialGrid]
            });


            //adaugare tab la tabpanel-ul principal
            APP.oCenterRegion.add(oNomTab);
            APP.oCenterRegion.doLayout();
        }


        //activare tab
        APP.oCenterRegion.setActiveTab(APP.helpie_nomenclator.id);
    },
    stergePachet: function(oGrid) {

        if (oGrid.selModel.getCount() !== 1) {
            APP.events.throwInformation("Selectati o inregistrare !", true);
            return;
        }
        var id_pachet = oGrid.selModel.getSelected().id;

        Ext.MessageBox.confirm('Confirmare', 'Sigur doriti stergerea pachetului ?', function(btn_salvare) {
            if (btn_salvare === 'yes') {
                Ext.MessageBox.wait('Loading');
                Ext.Ajax.request({
                    url: 'admin/nomenclator/deletePachet',
                    params: {
                        id_pachet: id_pachet
                    },
                    success: function(response) {
                        Ext.MessageBox.hide();
                        var response = Ext.decode(response.responseText);
                        APP.events.throwInformation(response.description, true);
                        oGrid.store.reload();
                    }
                });

            }
        });
    },
    deleteServiciu: function(oGrid) {

        if (oGrid.selModel.getCount() !== 1) {
            APP.events.throwInformation("Selectati o inregistrare !", true);
            return;
        }
        var id_serviciu = oGrid.selModel.getSelected().id;

        Ext.MessageBox.confirm('Confirmare', 'Sigur doriti stergerea serviciului ?', function(btn_salvare) {
            if (btn_salvare === 'yes') {
                Ext.MessageBox.wait('Loading');
                Ext.Ajax.request({
                    url: 'admin/nomenclator/deleteServiciu',
                    params: {
                        id_serviciu: id_serviciu
                    },
                    success: function(response) {
                        Ext.MessageBox.hide();
                        var response = Ext.decode(response.responseText);
                        APP.events.throwInformation(response.description, true);
                        oGrid.store.reload();
                    }
                });

            }
        });
    },
    formTexte: function(language) {


        var oGrid, oWind;

        //constructie formular
        var oFpAdd = new Ext.FormPanel({
            autoHeight: true,
            labelWidth: 160,
            frame: true,
            labelAlign: 'top',
            bodyStyle: {
                padding: "5px"
            },
            layout: 'form',
            items: [
                {
                    name: 'language',
                    inputType: 'hidden',
                    allowBlank: false,
                    value: language,
                },
                {
                    anchor: "100%",
                    width: "100%",
                    height: 490,
                    id: "texte",
                    xtype: 'textarea',
                    name: 'content',
                    minLength: 3,
                    fieldLabel: 'Data'
                }
            ],
            buttons: [
                {
                    text: 'Salvare',
                    handler: function() {
                        if (!oFpAdd.getForm().isValid()) {
                            return;
                        }

                        Ext.MessageBox.wait('Loading');
                        Ext.Ajax.request({
                            url: 'admin/nomenclator/saveTexte',
                            params: {
                                language: language,
                                data:encodeURIComponent(Ext.getCmp("texte").getValue()),
                            },
                            success: function(response) {
                                Ext.MessageBox.hide();
                                APP.events.throwInformation("Am salvat cu succes", true);
                            }
                        });
                    }
                }
            ]
        });

        //constructie fereastra
        oWind = new Ext.Window({
            title: "Modifica date",
            width: 750,
            height: 600,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [oFpAdd]
        });

        Ext.Ajax.request({
            url: 'admin/nomenclator/getTexte',
            params: {
                language: language
            },
            success: function(response) {

                var response = Ext.decode(response.responseText);

                Ext.getCmp("texte").setValue(response.data);
                oWind.show();
            }
        });


    },
    gridServicii: function(config) {
        APP.helpie_nomenclator.id = config.tab_id;

        if (!Ext.getCmp(APP.helpie_nomenclator.id)) {
            var oCmGrupMaterial = new Ext.grid.ColumnModel({
                columns: [{
                        header: "ID Serviciu",
                        dataIndex: 'id_serviciu',
                        width: 100,
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Nume Serviciu",
                        dataIndex: 'name',
                        width: 250,
                        editor: new Ext.form.HtmlEditor({
                            selectOnFocus: true
                        }),
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Nume Serviciu(engleza)",
                        dataIndex: 'name_en',
                        width: 250,
                        editor: new Ext.form.HtmlEditor({
                            selectOnFocus: true
                        }),
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Descriere",
                        dataIndex: 'description',
                        width: 550,
                        editor: new Ext.form.HtmlEditor({
                            selectOnFocus: true
                        }),
                        filter: {
                            type: 'string'
                        }
                    }
                ],
                defaults: {
                    sortable: true
                }
            });

            var oGrupMaterialGrid = new Ext.ux.fastEditorGrid({
                xtype: "uxFatGrid",
                layout: 'fit',
                id: "serviciiGrid",
                region: 'center',
                viewConfig: {
                    forceFit: false
                },
                clicksToEdit: 1,
                gridConfig: {
                    filterable: true,
                    resetFilterButton: true,
                    url: 'admin/nomenclator/getGridServicii',
                    sortField: "id_serviciu",
                    idProperty: "id_serviciu",
                    sortDir: "DESC",
                    fields: ['name', 'id_serviciu', "description", "name_en"]
                },
                cm: oCmGrupMaterial,
                tbar: [
                    {
                        text: "Creare Serviciu",
                        iconCls: 'icon-fugue-pencil',
                        action: "add",
                        handler: APP.helpie_nomenclator.createServiciu
                    },
                    {
                        text: "Sterge Serviciu",
                        icon: "assets/admin/resources/img/delete.png",
                        handler: function() {

                            APP.helpie_nomenclator.deleteServiciu(oGrupMaterialGrid)
                        }

                    }
                ],
                listeners: {
                }
            });

            var oNomTab = new Ext.Panel({
                title: 'Nomenclator Servicii',
                id: APP.helpie_nomenclator.id,
                iconCls: 'icon-database',
                closable: true,
                layout: 'border',
                items: [oGrupMaterialGrid]
            });


            //adaugare tab la tabpanel-ul principal
            APP.oCenterRegion.add(oNomTab);
            APP.oCenterRegion.doLayout();
        }

        //activare tab
        APP.oCenterRegion.setActiveTab(APP.helpie_nomenclator.id);
    },
    getPachetServiciiGrid: function(config) {
        var oServiciiGrid = new Ext.ux.fastEditorGrid({
            xtype: "uxFatGrid",
            height: 300,
            layout: 'fit',
            id: "pacheteServiciiGrid",
            region: 'center',
            loadMask: {
                msg: "Incarc serviciile pachetului"
            },
            viewConfig: {
                forceFit: true
            },
            clicksToEdit: 1,
            gridConfig: {
                hasBottomBar: false,
                filterable: true,
                resetFilterButton: true,
                url: 'admin/nomenclator/getServiciiPachetGrid',
                sortField: "id_serviciu",
                idProperty: "id_serviciu",
                storeBaseParams: {id_pachet: config.id_pachet},
                fields: ['id_serviciu', 'name', 'name_en', 'description']
            },
            tbar: [
                {
                    text: "Serviciu Nou",
                    iconCls: 'icon-fugue-plus-circle',
                    action: "add",
                    id_pachet: config.id_pachet,
                    handler: APP.helpie_nomenclator.addServiciuToPachet
                }
            ],
            cm: new Ext.grid.ColumnModel({
                columns: [{
                        header: "ID Serviciu",
                        dataIndex: 'id_serviciu',
                        width: 100,
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Nume Serviciu",
                        dataIndex: 'name',
                        width: 250,
                        editor: new Ext.form.HtmlEditor({
                            selectOnFocus: true
                        }),
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Nume Serviciu(Engleza)",
                        dataIndex: 'name_en',
                        width: 250,
                        editor: new Ext.form.HtmlEditor({
                            selectOnFocus: true
                        }),
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Descriere",
                        dataIndex: 'description',
                        width: 550,
                        editor: new Ext.form.HtmlEditor({
                            selectOnFocus: true
                        }),
                        filter: {
                            type: 'string'
                        }
                    }
                ]
            }),
            listeners: {
            }
        });
        return oServiciiGrid;
    },
    expandedRowPachet: function(obj, recordParent, body, rowIndex) {

        id = "my_dir_row-" + recordParent.id;
        var id_pachet = recordParent.id;

        Ext.apply(APP.helpie_nomenclator, {id_pachet: id_pachet});

        var serviciiGrid = APP.helpie_nomenclator.getPachetServiciiGrid({
            id_pachet: id_pachet,
            hasBottomBar: false,
            width: 600,
            height: 150
        });

        serviciiGrid.render(id);
        serviciiGrid.getEl().swallowEvent(['mouseover', 'mousedown', 'click', 'dblclick']);
    },
    addPachet: function() {
        var oGrid, oWind;

        var oGrid = Ext.getCmp(APP.helpie_nomenclator.id).getComponent("pacheteGrid");

        //constructie formular
        var oFpAdd = new Ext.FormPanel({
            autoHeight: true,
            labelWidth: 160,
            frame: true,
            bodyStyle: {
                padding: "5px"
            },
            layout: 'form',
            items: [
                {
                    xtype: 'fieldset',
                    title: 'Pachet',
                    autoHeight: true,
                    defaults: {
                        anchor: "95%",
                        xtype: "textfield",
                        allowBlank: false
                    },
                    items: [
                        {
                            name: 'name',
                            minLength: 3,
                            fieldLabel: 'Nume'
                        },
                        {
                            name: 'name_en',
                            minLength: 3,
                            fieldLabel: 'Nume Engleza'
                        },
                        {
                            xtype: "numberfield",
                            name: 'price',
                            fieldLabel: 'Pret(RON)'
                        }
                    ]
                }
            ],
            buttons: [
                {
                    text: 'Salvare',
                    handler: function() {
                        if (!oFpAdd.getForm().isValid()) {
                            return;
                        }
                        oFpAdd.getForm().submit({
                            waitMsg: 'Loading...',
                            url: "admin/nomenclator/addPachet",
                            success: function(form, response) {
                                oGrid.store.reload();
                                oWind.close();
                            }
                        });
                    }
                }
            ]
        });

        //constructie fereastra
        oWind = new Ext.Window({
            title: "Adaugare inregistrare",
            width: 450,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [oFpAdd]
        }).show();

    },
    createServiciu: function(config) {
        //constructie formular
        var oFpAdd = new Ext.FormPanel({
            autoHeight: true,
            labelWidth: 160,
            frame: true,
            bodyStyle: {
                padding: "5px"
            },
            layout: 'form',
            items: [
                {
                    xtype: 'fieldset',
                    title: 'Creaza un nou Serviciu',
                    autoHeight: true,
                    defaults: {
                        anchor: "95%",
                        xtype: "textfield",
                        allowBlank: false
                    },
                    items: [
                        {
                            name: 'name',
                            minLength: 3,
                            fieldLabel: 'Nume'
                        },
                        {
                            name: 'name_en',
                            minLength: 3,
                            fieldLabel: 'Nume Engleza'
                        },
                        {
                            xtype: 'htmleditor',
                            width: 400,
                            name: 'description',
                            fieldLabel: 'Descriere'
                        }

                    ]
                }
            ],
            buttons: [
                {
                    text: 'Salvare',
                    handler: function() {
                        if (!oFpAdd.getForm().isValid()) {
                            return;
                        }
                        oFpAdd.getForm().submit({
                            params: {id_pachet: config.id_pachet},
                            waitMsg: 'Loading...',
                            url: "admin/nomenclator/createServiciu",
                            success: function(form, response) {
                                Ext.getCmp("serviciiGrid").store.reload();
                                APP.events.throwFormNotification(response.result);
                                oWind.close();
                            }
                        });
                    }
                }
            ]
        });

        //constructie fereastra
        oWind = new Ext.Window({
            title: "Creare serviciu",
            width: 850,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [oFpAdd]
        }).show();


    },
    addServiciuToPachet: function(config) {
        var oGrid, oWind;
        //constructie formular
        var oFpAdd = new Ext.FormPanel({
            autoHeight: true,
            labelWidth: 120,
            frame: true,
            bodyStyle: {
                padding: "5px"
            },
            layout: 'form',
            items: [
                {
                    xtype: 'fieldset',
                    title: 'Adauga serviciu in pachet',
                    autoHeight: true,
                    defaults: {
                        anchor: "95%",
                        xtype: "textfield",
                        allowBlank: false
                    },
                    items: [
                        {
                            fieldLabel: "Nume Serviciu",
                            xtype: "uxFCombo",
                            name: "id_serviciu",
                            allowBlank: false,
                            easyConfig: {
                                baseParams: {
                                    furncl_activ: 1
                                },
                                readerFields: [
                                    {
                                        name: "id",
                                        mapping: "id_serviciu"
                                    },
                                    {
                                        name: "name",
                                        mapping: "name"
                                    }
                                ],
                                proxyUrl: 'admin/nomenclator/getServicii'
                            }
                        }

                    ]
                }
            ],
            buttons: [
                {
                    text: 'Salvare',
                    handler: function() {
                        if (!oFpAdd.getForm().isValid()) {
                            return;
                        }
                        oFpAdd.getForm().submit({
                            params: {id_pachet: config.id_pachet},
                            waitMsg: 'Loading...',
                            url: "admin/nomenclator/addServiciu",
                            success: function(form, response) {
                                Ext.getCmp("pacheteServiciiGrid").store.reload();
                                APP.events.throwFormNotification(response.result);
                                oWind.close();
                            },
                            failure: function(form, response) {
                                APP.events.throwFormNotification(response.result);
                                oWind.close();
                            }
                        });
                    }
                }
            ]
        });

        //constructie fereastra
        oWind = new Ext.Window({
            title: "Adaugare inregistrare",
            width: 500,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [oFpAdd]
        }).show();

    }

});
/*global APP,Ext,fnCompleteForm,console*/


/*global Ext, APP*/
Ext.ns('APP.helpie_comenzi');
Ext.apply(APP.helpie_comenzi, {
    id: "comenzi",
    iconCls: 'icon-fugue-table-money',
    text: 'Comenzi',
    init: function(config) {
        if (!Ext.getCmp(APP.helpie_comenzi.id)) {

            var oComenziGrid = APP.helpie_comenzi.getComenziGrid(config);
            var oNomTab = new Ext.Panel({
                title: 'Comenzi',
                id: APP.helpie_comenzi.id,
                iconCls: 'icon-fugue-plus-circle',
                closable: true,
                layout: 'fit',
                items: [
                    oComenziGrid
                ],
            });
            //adaugare tab la tabpanel-ul principal
            APP.oCenterRegion.add(oNomTab);
            APP.oCenterRegion.doLayout();
        }
        //activare tab
        APP.oCenterRegion.setActiveTab(APP.helpie_comenzi.id);
    },
    getComenziGrid: function(config) {

        APP.helpie_comenzi.taskCM = new Ext.grid.ColumnModel({
            columns: [
                {
                    header: "Actiuni",
                    css: "text-align:center;",
                    xtype: 'actioncolumn',
                    hideable: false,
                    width: 50,
                    items: [
                        {
                            tooltip: "Confirma Comanda",
                            getClass: this.getActionClassTaskActive,
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = grid.store.getAt(rowIndex);
                                var id_comanda = rec.get('id_order');
                                APP.helpie_comenzi.confirmaComanda(grid, id_comanda);
                            }
                        },
                        {
                            tooltip: "Anuleaza Comanda ",
                            icon: 'assets/admin/resources/img/delete.png',
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = grid.store.getAt(rowIndex);
                                var id_order = rec.get('id_order');
                                var status = rec.get('payment_status');
                                if (status == "C") {
                                    APP.events.throwInformation("Comanda este deja anulata", true);
                                    return;
                                }
                                APP.helpie_comenzi.anuleazaComanda(grid, id_order);
                            }
                        },
                    ]
                },
                {
                    header: "ID Comanda",
                    dataIndex: 'id_order',
                    width: 50,
                    filter: {
                        type: 'string'
                    },
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {
                        return value;
                    },
                },
                {
                    header: "Nr Inregistrare",
                    dataIndex: 'order_number',
                    width: 50,
                    filter: {
                        type: 'string'
                    },
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {
                        return value;
                    },
                },
                {
                    header: "Client",
                    dataIndex: 'lastname',
                    width: 100,
                    filter: {
                        type: 'string'
                    },
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {
                        return value + " " + record.get("firstname");
                    },
                },
                {
                    header: "Metoda de Plata",
                    dataIndex: 'payment_method',
                    width: 80,
                    filter: {
                        type: 'string'
                    },
                },
                {
                    header: "Status Plata",
                    dataIndex: 'payment_status',
                    width: 80,
                    filter: {
                        type: 'string'
                    },
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {
                        switch (value) {
                            case "W":
                                {
                                    return "<span style='color:#000'>Pending</span>";
                                }
                                break;
                            case "F":
                                {
                                    return "<span style='color:green'>Confirmat</span>";
                                }
                                break;
                            case "C":
                                {
                                    return "<span style='color:#f00'>Anulat</span>";
                                }
                                break;
                        }
                    },
                },
                {
                    header: "Operatie",
                    dataIndex: 'nume_pachet',
                    width: 100,
                    filter: {
                        type: 'string'
                    },
                },
                {
                    header: "Total plata(lei)",
                    dataIndex: 'total',
                    width: 80,
                    filter: {
                        type: 'string'
                    },
                },
                {
                    header: "Data Comanda",
                    dataIndex: 'orderedOn',
                    width: 80,
                    filter: {
                        type: 'date'
                    },
                }
            ],
            defaults: {
                sortable: true
            }
        });
        var oComenziGrid = new Ext.ux.fatGrid({
            xtype: "uxFatGrid",
            id: "tasksGrid",
            height: "100%",
            stateful: false,
            tbar: [
                {
                    text: "Adauga Comanda",
                    iconCls: 'icon-fugue-plus-circle',
                    action: "add",
                    handler: function() {
                        APP.helpie_comenzi.addComanda(oComenziGrid);
                    }
                }

            ],
            gridConfig: {
                viewConfig: {
                    forceFit: true,
                    getRowClass: function(record, rowIndex, rp, ds) {
                        var status_id = record.get('payment_status');

                        //sel nou
                        if (status_id == "W") {
                            return 'payment_pending';
                        }
                        if (status_id == "F") {
                            // return 'payment_confirmed';
                        }
                        if (status_id == 3) {
                            return 'payment_canceled';
                        }
                    }
                },
                filterable: true,
                idProperty: "id_order",
                sortDir: "DESC",
                sortField: "id_order",
                storeBaseParams: config.storeBaseParams,
                url: 'admin/order/getOrdersGrid',
                fields: ["id_order", "client", "payment_method", "order_number", "firstname", "lastname", "payment_status", "nume_pachet", "total", "orderedOn"],
            },
            stateful: true,
                    split: true,
            collapsible: false,
            id: "comenziGrid",
                    cm: APP.helpie_comenzi.taskCM,
            title: (config.text ? config.text : "Comenzi Pachete"),
            loadMask: {
                msg: "Incarc comenzile..."
            }
        });
        return oComenziGrid;
    },
    getActionClassTaskActive: function(val, meta, rec) {


        if (rec.get('payment_status') != "F")
            return "taskReminderSetActiveCls";
        else
            return "hiddenCls";
    },
    confirmaComanda: function(grid, id_comanda) {

        Ext.MessageBox.confirm('Confirmare', 'Sigur doriti confirmarea comenzii ?', function(btn_salvare) {
            if (btn_salvare === 'yes') {
                Ext.MessageBox.wait('Loading');
                Ext.Ajax.request({
                    url: 'admin/order/confirmOrder',
                    params: {
                        id_order: id_comanda
                    },
                    success: function(response) {
                        Ext.MessageBox.hide();
                        var response = Ext.decode(response.responseText);
                        APP.events.throwInformation(response.description, true);
                        grid.store.reload();
                    }
                });

            }
        });

    },
    anuleazaComanda: function(grid, id_comanda) {

        Ext.MessageBox.confirm('Confirmare', 'Sigur doriti anularea comenzii ?', function(btn_salvare) {
            if (btn_salvare === 'yes') {
                Ext.MessageBox.wait('Loading');
                Ext.Ajax.request({
                    url: 'admin/order/cancelOrder',
                    params: {
                        id_order: id_comanda
                    },
                    success: function(response) {
                        Ext.MessageBox.hide();
                        var response = Ext.decode(response.responseText);
                        APP.events.throwInformation(response.description, true);

                        grid.store.reload();
                    }
                });

            }
        });

    },
    addComanda: function(oGrid) {

        var newComandaForm = new Ext.FormPanel({
            labelWidth: 100,
            frame: true,
            stateful: false,
            height: "100%",
            id: "newComandaForm",
            layout: 'form',
            //labelAlign: 'top',
            defaults: {
                anchor: "100%",
                xtype: "textfield",
                allowBlank: false
            },
            items: [
                {
                    fieldLabel: "Client",
                    xtype: "uxFCombo",
                    name: "id_client",
                    id: "clientCombo",
                    allowBlank: false,
                    easyConfig: {
                        baseParams: {
                            user_status: 1,
                            user_rol: "client"
                        },
                        listeners: {
                            beforequery: function(queryEv) {
                                queryEv.combo.expand();
                                Ext.apply(queryEv.combo.store.baseParams, {
                                    query: queryEv.query
                                });
                                queryEv.combo.store.load();
                                return false;
                            }},
                        readerFields: [
                            {
                                name: "id",
                                mapping: "id_user"
                            },
                            {
                                name: "name",
                                mapping: "user_name"
                            }
                        ],
                        proxyUrl: 'admin/nomenclator/getUseriCombo'
                    }
                },
                {
                    id: "pachetCombo",
                    fieldLabel: "Pachet Client",
                    xtype: "uxFCombo",
                    name: "id_pachet",
                    allowBlank: false,
                    listeners: {
                        beforequery: function(queryEv) {
                            queryEv.combo.expand();
                            Ext.apply(queryEv.combo.store.baseParams, {
                                query: queryEv.query
                            });
                            queryEv.combo.store.load();
                            return false;
                        }
                    },
                    easyConfig: {
                        readerFields: [
                            {
                                name: "id",
                                mapping: "id_pachet"
                            },
                            {
                                name: "name",
                                mapping: "name"
                            }
                        ],
                        proxyUrl: 'admin/nomenclator/getPachete'
                    }
                },
                {
                    id: "metodaPlataCombo",
                    fieldLabel: "Metoda Plata",
                    xtype: "uxFCombo",
                    name: "payment_method",
                    allowBlank: false,
                    easyConfig: {
                        mode: "local",
                        localData: [
                            ['OP', 'Transfer Bancar'],
                            ['CARD', 'Card']
                        ]
                    }
                }
            ],
            buttons: [
                {
                    text: 'Trimite',
                    handler: function() {
                        if (!newComandaForm.getForm().isValid()) {
                            return;
                        }
                        newComandaForm.getForm().submit({
                            params: {},
                            waitMsg: 'Loading...',
                            url: "admin/order/newOrder",
                            success: function(form, response) {
                                oGrid.store.reload();
                                APP.events.throwFormNotification(response.result);
                                oWind.close();
                            }
                        });
                    }
                }
            ]
        });



        oWind = new Ext.Window({
            title: "Creaza Comanda/Pachet",
            width: 350,
            height: 250,
            stateful: false,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [newComandaForm]
        }).show();

    }
});
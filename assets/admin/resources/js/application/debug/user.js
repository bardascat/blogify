/*global APP,Ext,fnCompleteForm,console*/
Ext.ns('APP.userScreen');

Ext.apply(APP.userScreen, {
    //functie de stergere / editare inregistrare
    addOperator: function(config) {
        var oGrid, iRec = 0, oWind, op = "edit";

        oGrid = Ext.getCmp(APP.userScreen.id).getComponent("userGrid");

        //setare tip operatie; daca este butonul de adaugare / editare atunci se citeste paramentrul `action` al acestuia
        if (config.action) {
            op = config.action;
        }

        if (op === "edit") {
            if (oGrid.selModel.getCount() !== 1) {
                APP.events.throwInformation("Selectati o inregistrare !", true);
                return;
            }
            iRec = oGrid.selModel.getSelected().id;
        }

        //constructie formular
        var oFpAdd = new Ext.FormPanel({
            title: "Detalii Utilizator",
            labelWidth: 160,
            frame: true,
            bodyStyle: {
                padding: "5px"
            },
            layout: 'form',
            items: [
                {
                    xtype: 'fieldset',
                    title: 'Date user',
                    autoHeight: true,
                    defaults: {
                        anchor: "95%",
                        xtype: "textfield",
                        allowBlank: false
                    },
                    items: [
                        {
                            name: 'firstname',
                            fieldLabel: 'Prenume'
                        },
                        {
                            name: 'lastname',
                            fieldLabel: 'Nume'
                        },
                        {
                            vtype: 'email',
                            name: 'email',
                            fieldLabel: 'Email'
                        },
                        {
                            name: 'phone',
                            fieldLabel: 'Telefon',
                            allowBlank: true
                        },
                        {
                            xtype: 'xcheckbox',
                            name: "user_activ",
                            fieldLabel: "Activ"
                        },
                        {
                            fieldLabel: "Rol",
                            xtype: "uxFCombo",
                            name: "rol_id",
                            allowBlank: false,
                            easyConfig: {
                                baseParams: {
                                    operator: 1
                                },
                                readerFields: [
                                    {
                                        name: "id",
                                        mapping: "rol_id"
                                    },
                                    {
                                        name: "name",
                                        mapping: "rol_nume"
                                    }
                                ],
                                proxyUrl: 'admin/nomenclator/getRoluri'
                            }
                        },
                        {
                            name: "user_id",
                            xtype: "hidden",
                            value: iRec
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    title: 'Parola',
                    autoHeight: true,
                    defaults: {
                        anchor: "95%",
                        xtype: "textfield"
                    },
                    items: [
                        {
                            fieldLabel: 'Noua parola',
                            name: 'newPassword',
                            id: 'NewUserPassword',
                            vtype: 'PasswordValid',
                            inputType: 'password',
                            allowBlank: (op === "edit" ? true : false)
                        },
                        {
                            fieldLabel: 'Repeta parola',
                            name: 'passwordConfirm',
                            initialPassField: 'NewUserPassword',
                            vtype: 'Password',
                            inputType: 'password',
                            allowBlank: (op === "edit" ? true : false)
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
                            url: "admin/user/editUser",
                            waitMsg: 'Loading...',
                            success: function(form, response) {
                                APP.events.throwFormNotification(response.result);
                                if (op != "edit")
                                    APP.userScreen.initExtraPanelsOperator(response.result.user.id_user);
                                if (op == "edit")
                                    Ext.getCmp("WindowAddEditOperator").setTitle("Editare user: " + response.result.user.firstname + " " + response.result.user.lastname);

                                oGrid.store.reload();

                            }
                        });
                    }
                }
            ]
        });


        var oTabPanel = new Ext.TabPanel({
            id: "oTabOperatori",
            frame: true,
            activeTab: 0,
            defaults: {
                anchor: "100%"
            },
            items: [oFpAdd]
        });

        //constructie fereastra
        oWind = new Ext.Window({
            id: "WindowAddEditOperator",
            title: (op === "edit") ? "Editare inregistrare" : "Adaugare inregistrare",
            width: (op === "edit") ? 750 : 700,
            height: (op === "edit") ? 450 : 450,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [oTabPanel]
        }).show();

        //daca este editare de inregistrare atunci se aduc datele acestei inregistrari
        if (op === "edit") {
            Ext.MessageBox.wait('Loading');
            oFpAdd.on("afterlayout", function() {

                Ext.Ajax.request({
                    url: 'admin/user/getUser',
                    params: {
                        userId: iRec
                    },
                    success: function(response) {
                        Ext.MessageBox.hide();

                        APP.userScreen.initExtraPanelsOperator(iRec);
                        var oRes = Ext.decode(response.responseText);
                        var oData = oRes.data;
                        fnCompleteForm(oFpAdd, oData);
                    }
                });


            }, this, {
                single: true
            });
        }
    },
    addClient: function(config) {
        var oGrid, iRec = 0, oWind, op = "edit";

        oGrid = Ext.getCmp(APP.userScreen.id).getComponent("userGrid");

        //setare tip operatie; daca este butonul de adaugare / editare atunci se citeste paramentrul `action` al acestuia
        if (config.action) {
            op = config.action;
        }

        if (op === "edit") {
            if (oGrid.selModel.getCount() !== 1) {
                APP.events.throwInformation("Selectati o inregistrare !", true);
                return;
            }
            iRec = oGrid.selModel.getSelected().id;
        }



        //constructie formular
        var oFpAdd = new Ext.FormPanel({
            title: "Detalii Utilizator",
            labelWidth: 160,
            frame: true,
            bodyStyle: {
                padding: "5px"
            },
            layout: 'form',
            items: [
                {
                    xtype: 'fieldset',
                    title: 'Date user',
                    autoHeight: true,
                    defaults: {
                        anchor: "95%",
                        xtype: "textfield",
                        allowBlank: false
                    },
                    items: [
                        {
                            name: 'firstname',
                            fieldLabel: 'Prenume'
                        },
                        {
                            name: 'lastname',
                            fieldLabel: 'Nume'
                        },
                        {
                            vtype: 'email',
                            name: 'email',
                            fieldLabel: 'Email'
                        },
                        {
                            name: 'phone',
                            fieldLabel: 'Telefon',
                            allowBlank: true
                        },
                        {
                            xtype: 'xcheckbox',
                            name: "user_activ",
                            fieldLabel: "Activ"
                        },
                        {
                            fieldLabel: "Rol",
                            xtype: "uxFCombo",
                            name: "rol_id",
                            allowBlank: false,
                            easyConfig: {
                                baseParams: {
                                    client: 1
                                },
                                readerFields: [
                                    {
                                        name: "id",
                                        mapping: "rol_id"
                                    },
                                    {
                                        name: "name",
                                        mapping: "rol_nume"
                                    }
                                ],
                                proxyUrl: 'admin/nomenclator/getRoluri'
                            }
                        },
                        {
                            name: "user_id",
                            xtype: "hidden",
                            value: iRec
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    title: 'Parola',
                    autoHeight: true,
                    defaults: {
                        anchor: "95%",
                        xtype: "textfield"
                    },
                    items: [
                        {
                            fieldLabel: 'Noua parola',
                            name: 'newPassword',
                            id: 'NewUserPassword',
                            vtype: 'PasswordValid',
                            inputType: 'password',
                            allowBlank: (op === "edit" ? true : false)
                        },
                        {
                            fieldLabel: 'Repeta parola',
                            name: 'passwordConfirm',
                            initialPassField: 'NewUserPassword',
                            vtype: 'Password',
                            inputType: 'password',
                            allowBlank: (op === "edit" ? true : false)
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
                            url: "admin/user/editUser",
                            waitMsg: 'Loading...',
                            success: function(form, response) {
                                APP.events.throwFormNotification(response.result);

                                if (op != "edit")
                                    APP.userScreen.initExtraPanelsUser(response.result.user.id_user);

                                Ext.getCmp("WindowAddEditClient").setTitle("Editare user: " + response.result.user.firstname + " " + response.result.user.lastname);

                                oGrid.store.reload();

                            }
                        });
                    }
                }
            ]
        });

        var oTabPanel = new Ext.TabPanel({
            id: "oTabUseri",
            frame: true,
            activeTab: 0,
            defaults: {
                anchor: "100%"
            },
            items: [oFpAdd]
        });

        //constructie fereastra
        oWind = new Ext.Window({
            id: "WindowAddEditClient",
            title: (op === "edit") ? "Editare inregistrare" : "Adaugare inregistrare",
            width: (op === "edit") ? 750 : 700,
            height: (op === "edit") ? 450 : 450,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [oTabPanel]
        }).show();

        //daca este editare de inregistrare atunci se aduc datele acestei inregistrari
        if (op === "edit") {
            Ext.MessageBox.wait('Loading');
            oFpAdd.on("afterlayout", function() {

                Ext.Ajax.request({
                    url: 'admin/user/getUser',
                    params: {
                        userId: iRec
                    },
                    success: function(response) {
                        Ext.MessageBox.hide();
                        APP.userScreen.initExtraPanelsUser(iRec);

                        var oRes = Ext.decode(response.responseText);
                        var oData = oRes.data;
                        fnCompleteForm(oFpAdd, oData);
                    }
                });


            }, this, {
                single: true
            });
        }
    },
    //functie de stergere / editare inregistrare
    reset: function() {
        var oGrid, iRec = 0, oWind;

        oGrid = Ext.getCmp(APP.userScreen.id).getComponent("userGrid");

        //setare tip operatie; daca este butonul de adaugare / editare atunci se citeste paramentrul `action` al acestuia
        if (this.action) {
            op = this.action;
        }

        if (oGrid.selModel.getCount() !== 1) {
            APP.events.throwInformation("Selectati o inregistrare !", true);
            return;
        }
        iRec = oGrid.selModel.getSelected().id;

        Ext.MessageBox.confirm("Confirmare", "Sigur doriti resetarea parolei ?", function(btn) {
            if (btn === "yes") {
                //daca este editare de inregistrare atunci se aduc datele acestei inregistrari
                Ext.Ajax.request({
                    url: 'admin/user/resetPasswd',
                    params: {
                        userId: iRec
                    },
                    success: function(response) {
                        var oRes = Ext.decode(response.responseText);
                        APP.events.throwInformation(oRes.description, true);
                    }
                });
            }
        });
    }
});

Ext.apply(APP.userScreen, {
    id: 'idTabUserScreen',
    exec: function() {

        if (!Ext.getCmp(APP.userScreen.id)) {

            var oCmUser = new Ext.grid.ColumnModel({
                columns: [
                    {
                        header: "Nume",
                        dataIndex: 'lastname',
                        width: 200,
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Prenume",
                        dataIndex: 'firstname',
                        width: 200,
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Telefon",
                        dataIndex: 'phone',
                        width: 200,
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Email",
                        width: 200,
                        dataIndex: 'email',
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Rol",
                        width: 200,
                        dataIndex: 'rol_nume',
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: 'Valabilitate Abonament',
                        dataIndex: 'valabilitate_pachet',
                        width: 100,
                        renderer: APP.renderer.dateBusiness,
                        filter: {
                            type: 'date'
                        }
                    },
                    {
                        header: 'Activ',
                        dataIndex: 'user_activ',
                        width: 50,
                        renderer: APP.renderer.yesNo,
                        filter: {
                            type: 'string'
                        }
                    },
                ],
                defaults: {
                    sortable: true
                }
            });

            var oUserGrid = new Ext.ux.fatGrid({
                xtype: "uxFatGrid",
                layout: 'fit',
                itemId: "userGrid",
                region: 'center',
                loadMask: {
                    msg: "Incarc utilizatorii"
                },
                viewConfig: {
                    forceFit: true
                },
                gridConfig: {
                    filterable: true,
                    resetFilterButton: true,
                    url: 'admin/user/getData',
                    sortField: "id_user",
                    fields: ['id_user', 'firstname', 'lastname', 'phone', 'user_activ', 'rol_nume', 'email',"valabilitate_pachet"]
                },
                cm: oCmUser,
                tbar: [
                    {
                        text: "Adauga Operator",
                        iconCls: 'icon-fugue-plus-circle',
                        action: "add",
                        handler: function() {
                            APP.userScreen.addOperator({action: "add"});
                        }
                    },
                    {
                        text: "Adauga Client",
                        iconCls: 'icon-fugue-plus-circle',
                        action: "add",
                        handler: function() {
                            APP.userScreen.addClient({action: "add"});
                        }
                    },
                    "-",
                    {
                        text: "Editeaza",
                        iconCls: 'icon-fugue-pencil',
                        action: "edit",
                        handler: function() {
                            if (oUserGrid.selModel.getCount() !== 1) {
                                APP.events.throwInformation("Selectati o inregistrare !", true);
                                return;
                            }
                            iRec = oUserGrid.selModel.getSelected().id;
                            if (oUserGrid.selModel.getSelected().data.rol_nume == "client") {
                                APP.userScreen.addClient({action: "edit"});
                            } else {
                                APP.userScreen.addOperator({action: "edit"});
                            }

                        }
                    },
                    "-",
                    {
                        text: "Reseteaza parola",
                        iconCls: 'icon-fugue-key',
                        handler: APP.userScreen.reset
                    }
                ],
                listeners: {
                    //'rowdblclick': APP.userScreen.action
                }
            });

            //creare tab ce contine gridul
            var oNomTab = new Ext.Panel({
                title: 'Useri',
                id: APP.userScreen.id,
                iconCls: 'icon-user',
                closable: true,
                layout: 'border',
                items: [oUserGrid]
            });

            //adaugare tab la tabpanel-ul principal
            APP.oCenterRegion.add(oNomTab);
            APP.oCenterRegion.doLayout();

        }
        //activare tab
        APP.oCenterRegion.setActiveTab(APP.userScreen.id);
    },
    initExtraPanelsUser: function(idRec) {
        Ext.getCmp("oTabUseri").add([
            APP.helpie_task.getTasksGrid(
                    {id_list: "root", client: idRec},
            {
                title: "Taskuri Solicitate"
            }),
            APP.helpie_comenzi.getComenziGrid({text: "Comenzi/Pachete", storeBaseParams: {id_user: idRec}}),
            APP.userScreen.getTranzactiiGrid(idRec)
        ]);
    },
    initExtraPanelsOperator: function(idRec) {
        Ext.getCmp("oTabOperatori").add(
                APP.helpie_task.getTasksGrid(
                        {id_list: "root", operator: idRec},
                {
                    title: "Taskuri Atribuite"
                }));
    },
    getTranzactiiGrid: function(id_user) {



        // define a custom summary function
        Ext.ux.grid.GroupSummary.Calculations['totalSold'] = function(v, record, field) {
            if (record.data.type == 1)
                return v + (record.data.value);
            else
                return v - (record.data.value);
        };

        APP.userScreen.tranzactiiCM = new Ext.grid.ColumnModel({
            columns: [
                {
                    summaryType: 'count',
                    header: "ID tranzactie",
                    dataIndex: 'id_transaction',
                    width: 100,
                    summaryRenderer: function(v, params, data) {
                        return "Total: " + ((v === 0 || v > 1) ? '(' + v + ' Tranzactii)' : '(1 Tranzactie)');
                    },
                    filter: {
                        type: 'string'
                    },
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {
                        return value;

                    },
                },
                {
                    header: "Client",
                    dataIndex: 'client',
                    width: 100
                },
                {
                    header: "Operator",
                    dataIndex: 'operator_lastname',
                    width: 80,
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {
                        return value + " " + record.get("operator_firstname");
                    },
                    filter: {
                        type: 'string'
                    },
                },
                {
                    header: "Credit/Debit",
                    dataIndex: 'type',
                    width: 60,
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {
                        if (value == "1")
                            return "Debit";
                        else
                            return "Credit";
                    },
                },
                {
                    summaryType: 'totalSold',
                    header: "Valoare(lei)",
                    dataIndex: 'value',
                    width: 60,
                    type: 'float',
                    summaryRenderer: function(v, params, data) {
                        return "Sold:" + v;
                    },
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {
                        if (record.get("type") == "1")
                            return "<span style='color:green'>+" + value + "</span>";

                        if (record.get("type") == "2")
                            return "<span style='color:red'>-" + value + "</span>";
                    }
                },
                {
                    header: "Companie",
                    dataIndex: 'partener_nume',
                    width: 90,
                    filter: {
                        type: 'string'
                    },
                },
                {
                    header: "Data",
                    dataIndex: 'stamp',
                    width: 80,
                    filter: {
                        type: 'date'
                    },
                },
                {
                    header: "Detalii",
                    dataIndex: 'details',
                    width: 200,
                    renderer: APP.renderer.nowrap,
                }
            ],
            defaults: {
                sortable: true
            }
        });

        var summary = new Ext.ux.grid.GroupSummary();

        var oTransactionGrid = new Ext.ux.fatGrid({
            xtype: "uxFatGrid",
            id: "tasksGrid",
            height: "100%",
            stateful: false,
            plugins: [summary],
            tbar: [
                {
                    text: "Adauga Tranzactie",
                    iconCls: 'icon-fugue-plus-circle',
                    action: "add",
                    handler: function() {
                        APP.userScreen.addTransaction(oTransactionGrid, id_user);
                    }
                }

            ],
            gridConfig: {
                viewConfig: new Ext.grid.GroupingView({
                    forceFit: true,
                    groupOnSort: false,
                    enableGroupingMenu: false,
                    groupTextTpl: '{text}'
                }),
                group: true,
                groupOnSort: false,
                groupField: "client",
                filterable: true,
                idProperty: "id_transaction",
                sortDir: "DESC",
                sortField: "id_transaction",
                storeBaseParams: {id_user: id_user},
                url: 'admin/user/getTransactions',
                fields: ["id_transaction", "client", "type", "operator_lastname", "operator_firstname", "stamp", "value", {name: 'value', type: 'int'}, "partener_nume", "details"]

            },
            stateful: false,
                    collapsible: false,
            id: "tranzactiiGrid",
                    cm: APP.userScreen.tranzactiiCM,
            title: "Tranzactii",
            loadMask: {
                msg: "Incarc tranzactiile..."
            }
        });
        return oTransactionGrid;
    },
    addTransaction: function(oGrid, id_user) {

        var newTranzactieForm = new Ext.FormPanel({
            labelWidth: 100,
            frame: true,
            stateful: false,
            height: "100%",
            id: "newTranzactieForm",
            layout: 'form',
            labelAlign: 'top',
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
                            to: id_user,
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
                    fieldLabel: "Companie(optional)",
                    xtype: "uxFCombo",
                    name: "id_partener",
                    allowBlank: true,
                    easyConfig: {
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
                                mapping: "id_partener"
                            },
                            {
                                name: "name",
                                mapping: "name"
                            }
                        ],
                        proxyUrl: 'admin/nomenclator/getParteneri'
                    }
                },
                {
                    fieldLabel: "Debit/Credit",
                    xtype: "uxFCombo",
                    name: "type",
                    allowBlank: false,
                    easyConfig: {
                        mode: "local",
                        localData: [
                            ['1', 'Debit'],
                            ['2', 'Credit']
                        ]
                    }
                },
                {
                    fieldLabel: "Valoare",
                    xtype: "numberfield",
                    name: "value",
                    allowBlank: false,
                },
                {
                    fieldLabel: "Detalii",
                    xtype: "textarea",
                    name: "details",
                    allowBlank: true,
                }

            ],
            buttons: [
                {
                    text: 'Trimite',
                    handler: function() {
                        if (!newTranzactieForm.getForm().isValid()) {
                            return;
                        }
                        newTranzactieForm.getForm().submit({
                            params: {},
                            waitMsg: 'Loading...',
                            url: "admin/order/newTransaction",
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
            title: "Adauga Tranzactie",
            width: 450,
            height: 350,
            stateful: false,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [newTranzactieForm]
        }).show();

    }
});

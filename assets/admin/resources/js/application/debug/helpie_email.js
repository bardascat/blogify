Ext.ns('APP.helpie_email');


Ext.apply(APP.helpie_email, {
    id: 'idTabEmail',
    inbox: function(config) {
        APP.helpie_email.id = "tabMesagerie";
        if (!Ext.getCmp(APP.helpie_email.id)) {

            var oMailsGrid = APP.helpie_email.getMailsGrid();

            var oContentForm = new Ext.Panel({
                // lazily created panel (xtype:'panel' is default)
                region: 'east',
                minSize: 400,
                maxSize: 900,
                width: 600,
                stateful: false,
                hidden: true,
                collapsible: false,
                title: 'Continut mesaj',
                split: true,
                id: "oMailContentForm",
                layout: 'fit'
            });
            var navigationPanel = new Ext.tree.TreePanel({
                region: 'west',
                id: 'west-panel', // see Ext.getCmp() below
                title: 'Navigation',
                split: true,
                maxSize: 170,
                minSize: 170,
                width: 170,
                stateful: false,
                collapsible: true,
                useArrows: true,
                autoScroll: true,
                animate: true,
                enableDD: true,
                containerScroll: true,
                border: true,
                loader: new Ext.tree.TreeLoader(),
                root: new Ext.tree.AsyncTreeNode({
                    expanded: true,
                    children: [{
                            icon: "assets/admin/resources/img/email_1.png",
                            text: 'Inbox',
                            action: "view_inbox",
                            leaf: true
                        }, {
                            icon: "assets/admin/resources/img/email_go.png",
                            text: 'Sent',
                            action: "view_sent",
                            leaf: true
                        }, {
                            icon: "assets/admin/resources/img/bin.png",
                            text: 'Deleted',
                            action: "view_delete",
                            leaf: true
                        }]
                }),
                rootVisible: false,
                listeners: {
                    click: function(n) {

                        switch (n.attributes.action) {
                            case "view_inbox":
                                {
                                    APP.helpie_email.refreshEmailsGrid("inbox");
                                }
                                break;
                            case "view_sent":
                                {
                                    APP.helpie_email.refreshEmailsGrid("sent");
                                }
                                break;
                            case "view_delete":
                                {
                                    APP.helpie_email.refreshEmailsGrid("deleted");
                                }
                                break;
                            default:
                                {
                                    APP.helpie_email.refreshEmailsGrid("inbox");
                                }
                                break;
                        }

                    }
                }
            });
            var oNomTab = new Ext.Panel({
                title: 'Mesagerie',
                id: APP.helpie_email.id,
                icon: 'assets/admin/resources/img/email.png',
                closable: true,
                layout: 'fit',
                items: [
                    new Ext.Panel({
                        layout: 'border',
                        items: [
                            navigationPanel,
                            oMailsGrid,
                            oContentForm
                        ]
                    })
                ],
            });

            //adaugare tab la tabpanel-ul principal
            APP.oCenterRegion.add(oNomTab);
            APP.oCenterRegion.doLayout();

        }
        //activare tab
        APP.oCenterRegion.setActiveTab(APP.helpie_email.id);
    },
    refreshEmailsGrid: function(action) {
        switch (action) {
            case "inbox":
                {
                    Ext.getCmp("ReplyEmailButton").setVisible(true);

                    Ext.getCmp("mailsGrid").reconfigure(Ext.getCmp("mailsGrid").store, APP.helpie_email.inboxColumnModel);
                    Ext.getCmp("mailsGrid").setTitle("Mesaje Primite");

                }
                break;
            case "sent":
                {
                    Ext.getCmp("ReplyEmailButton").setVisible(false);
                    Ext.getCmp("mailsGrid").reconfigure(Ext.getCmp("mailsGrid").store, APP.helpie_email.sentColumnModel);
                    Ext.getCmp("mailsGrid").setTitle("Mesaje Trimise");

                }
                break;
            case "deleted":
                {

                    Ext.getCmp("ReplyEmailButton").setVisible(false);
                    Ext.getCmp("mailsGrid").reconfigure(Ext.getCmp("mailsGrid").store, APP.helpie_email.deleteColumnModel);
                    Ext.getCmp("mailsGrid").setTitle("Mesaje Sterse");
                }
                break;
        }
        Ext.getCmp("mailsGrid").store.load(
                {
                    params: {email_type: action},
                    callback: function(records, options, success) {
                        APP.helpie_email.initFirstGridEmail(records);
                    }

                }
        );
        Ext.getCmp("mailsGrid").getView().refresh();
    },
    getMailsGrid: function(email_type) {

        APP.helpie_email.inboxColumnModel = new Ext.grid.ColumnModel({
            columns: [
                {
                    header: "From",
                    dataIndex: 'from_lastname',
                    width: 80,
                    filter: {
                        type: 'string'
                    },
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {

                        return value + " " + record.get("from_firstname");
                    },
                },
                {
                    header: "Email",
                    dataIndex: 'from_email',
                    width: 110,
                    filter: {
                        type: 'string'
                    }
                },
                {
                    header: "Title",
                    dataIndex: 'title',
                    width: 190,
                    filter: {
                        type: 'string'
                    }
                },
                {
                    header: "Date",
                    dataIndex: 'cDate',
                    renderer: APP.renderer.dateBusiness,
                    width: 100,
                    filter: {
                        type: 'date'
                    }
                }
            ],
            defaults: {
                sortable: true
            }
        });

        APP.helpie_email.sentColumnModel = new Ext.grid.ColumnModel({
            columns: [
                {
                    header: "To",
                    dataIndex: 'to_lastname',
                    width: 80,
                    filter: {
                        type: 'string'
                    },
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {

                        return value + " " + record.get("to_firstname");
                    },
                },
                {
                    header: "Email",
                    dataIndex: 'to_email',
                    width: 110,
                    filter: {
                        type: 'string'
                    }
                },
                {
                    header: "Title",
                    dataIndex: 'title',
                    width: 190,
                    filter: {
                        type: 'string'
                    }
                },
                {
                    header: "Date",
                    dataIndex: 'cDate',
                    renderer: APP.renderer.dateBusiness,
                    width: 100,
                    filter: {
                        type: 'date'
                    }
                }
            ],
            defaults: {
                sortable: true
            }
        });

        APP.helpie_email.deleteColumnModel = new Ext.grid.ColumnModel({
            columns: [
                {
                    header: "From",
                    dataIndex: 'from_email',
                    width: 110,
                    filter: {
                        type: 'string'
                    }
                },
                {
                    header: "To",
                    dataIndex: 'to_email',
                    width: 110,
                    filter: {
                        type: 'string'
                    }
                },
                {
                    header: "Title",
                    dataIndex: 'title',
                    width: 190,
                    filter: {
                        type: 'string'
                    }
                },
                {
                    header: "Date",
                    dataIndex: 'cDate',
                    renderer: APP.renderer.dateBusiness,
                    width: 100,
                    filter: {
                        type: 'date'
                    }
                }
            ],
            defaults: {
                sortable: true
            }
        });
        
        var oMailsGrid = new Ext.ux.fatGrid({
            xtype: "uxFatGrid",
            id: "mailsGrid",
            viewConfig: {
                getRowClass: function(record, rowIndex, rp, ds) {
                    var viewed = record.get('viewed');
                    if (viewed == 0)
                        return 'mesaj_necitit';
                }
            },
            gridConfig: {
                filterable: true,
                idProperty: "id_email",
                sortDir: "DESC",
                sortField: "cDate",
                storeBaseParams: {email_type: "inbox"},
                url: 'admin/mail/getEmailsForGrid',
                fields: ["id_email", "email", 'title', "date", "cDate", "to_id_user", "to_firstname", "to_lastname",
                    "to_email", "from_id_user", "from_lastname", "from_firstname", "from_email","viewed"]
            },
            stateful: false,
            split: true,
            collapsible: false,
            region: 'center', // a center region is ALWAYS required for border layout
            id: "mailsGrid",
                    cm: APP.helpie_email.inboxColumnModel,
            title: "Mesaje Primite",
            loadMask: {
                msg: "Incarc lista de mesaje..."
            },
            tbar: [
                {
                    text: "Mesaj nou",
                    icon: "assets/admin/resources/img/email_edit.png",
                    handler: function() {
                        APP.helpie_email.newEmail();
                    },
                },
                {
                    text: "Delete",
                    icon: "assets/admin/resources/img/email_delete.png",
                    handler: function() {
                        APP.helpie_email.deleteEmail();
                    }
                },
                {
                    text: "Reply",
                    id: "ReplyEmailButton",
                    icon: "assets/admin/resources/img/reply.png",
                    handler: function() {
                        APP.helpie_email.replyEmail();
                    }
                }
            ],
            listeners: {
                'rowclick': function(grid, rowIndex, e) {
                    if (oMailsGrid.selModel.getCount() !== 1) {
                        return;
                    }
                    
                    var row    = this.getView().getRow(rowIndex); 
                
                $(row).removeClass('mesaj_necitit');
               
              ;        
                    
                    var id_email = oMailsGrid.selModel.getSelected().id;
                    
                    APP.helpie_email.loadEmailContent(id_email);

                }
            }
        });

        oMailsGrid.store.on('load', function(store, records, options) {
            APP.helpie_email.initFirstGridEmail(records);
        });
        return oMailsGrid;
    },
    loadEmailContent: function(id_email) {
        Ext.getCmp("oMailContentForm").load({
            url: 'admin/mail/getEmailContent',
            params: {id_email: id_email}, // or a URL encoded string
            callback: function() {

            },
            text: 'Loading email content...',
        });
    },
    newEmail: function() {

        var newEmailForm = new Ext.FormPanel({
            labelWidth: 100,
            frame: true,
            stateful: false,
            height: "100%",
            id: "newEmailForm",
            layout: 'form',
            labelAlign: 'top',
            defaults: {
                anchor: "100%",
                xtype: "textfield",
                allowBlank: false
            },
            items: [
                {
                    fieldLabel: "Catre: ",
                    xtype: "uxFCombo",
                    name: "to_email",
                    id: "emailTO",
                    allowBlank: false,
                    easyConfig: {
                        baseParams: {
                            furncl_activ: 1
                        },
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
                    id: "emailTitle",
                    name: 'title',
                    minLength: 3,
                    fieldLabel: 'Subject'
                },
                {
                    allowBlank: false,
                    xtype: 'htmleditor',
                    name: 'content',
                    margins: {top: 4, right: 4, bottom: 4, left: 4},
                    id: "emailContentTextArea",
                    itemid: "emailContentTextArea",
                    fieldLabel: 'Continut Email'
                }
            ],
            buttons: [
                {
                    text: 'Trimite',
                    handler: function() {
                        if (!newEmailForm.getForm().isValid()) {
                            return;
                        }
                        newEmailForm.getForm().submit({
                            params: {},
                            waitMsg: 'Loading...',
                            url: "admin/mail/newEmail",
                            success: function(form, response) {
                                Ext.getCmp("mailsGrid").store.reload();
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
            title: "Trimite un nou mesaj",
            width: 850,
            height: 500,
            stateful: false,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [newEmailForm]
        }).show();

    },
    deleteEmail: function() {
        if (Ext.getCmp("mailsGrid").selModel.getCount() !== 1) {
            APP.events.throwInformation("Selectati mesajul pe care doriti sa il stergeti!", true);
            return;
        }
        id_email = Ext.getCmp("mailsGrid").selModel.getSelected().id;
        Ext.Ajax.request({
            url: 'admin/mail/deleteEmail',
            params: {
                id_email: id_email
            },
            success: function(response) {
                Ext.getCmp("mailsGrid").store.reload();
            }
        });

    },
    replyEmail: function() {
        if (Ext.getCmp("mailsGrid").selModel.getCount() !== 1) {
            APP.events.throwInformation("Selectati mesajul!", true);
            return;
        }
        id_email = Ext.getCmp("mailsGrid").selModel.getSelected().id;

        Ext.MessageBox.wait('Loading');
        Ext.Ajax.request({
            url: 'admin/mail/getReplayData',
            params: {
                id_email: id_email
            },
            success: function(response) {
                Ext.MessageBox.hide();
                var res = Ext.decode(response.responseText);



                Ext.MessageBox.wait('Loading');
                APP.helpie_email.newEmail();

                Ext.getCmp("emailContentTextArea").setValue(res.data.body);
                Ext.getCmp("emailTitle").setValue(res.data.subject);
                Ext.getCmp("emailTO").easyConfig.baseParams.to = res.data.to_email;

                Ext.MessageBox.hide();

            }
        });



    },
    initFirstGridEmail: function(records) {


        //afisam content panelul daca avem mailuri
        if (records.length) {
            Ext.getCmp("oMailContentForm").setVisible(true);
            Ext.getCmp("mailsGrid").getSelectionModel().selectFirstRow();
            APP.helpie_email.loadEmailContent(Ext.getCmp("mailsGrid").store.getAt(0).data.id_email);
        } else {
            APP.events.throwInformation("Nu aveti niciun mesaj", true);
            Ext.getCmp("oMailContentForm").setVisible(false);
        }

        Ext.getCmp("oMailContentForm").doLayout();
        Ext.getCmp(APP.helpie_email.id).doLayout();
    }
}); 
/*global Ext, APP,$,window,console,inArray*/
Ext.BLANK_IMAGE_URL = 'assets/admin/ext-3.4.0/resources/images/default/s.gif';
Ext.QuickTips.init();
Ext.apply(APP, {
    oCenterRegion: null // global reference to center region tab panel
});

var glob, glob1, glob2;

Ext.ns('APP.sysLog');
Ext.apply(APP.sysLog, {
    // private, to get them use get
    items: [],
    // public, adds item and returns new item list
    add: function() {
        // this function only accepts objects
        if (typeof (arguments[0]) != 'object') {
            return false;
        }
        APP.sysLog.items.push(Ext.apply(arguments[0], {
            sDate: (new Date()
                    ).format('Y-m-d H:i:s'),
            uniqueId: [new Date().getTime(), Math.random()].join('').replace('.', '')
        }));
        return this.get(arguments[1] || false);
    },
    // get item list in reversed order(newer first)
    get: function() {
        var iLowLimit = 0;
        // if the number is specified
        if (arguments[0] !== undefined && typeof (arguments[0]
                ) === 'number') {
            iLowLimit = APP.sysLog.items.length - arguments[0];
        }

        var aItems = [];
        var i;
        for (i = APP.sysLog.items.length; i >= iLowLimit; i--) {
            if (APP.sysLog.items[i] !== undefined) {
                aItems.push(APP.sysLog.items[i]);
            }
        }
        return aItems;
    }
});

Ext.apply(APP, {
    // gets center region tab panel
    getCenterRegion: function() {
        return Ext.getCmp('center');
    },
    getLogPanel: function() {
        return {
            xtype: 'buttongroup',
            hidden: true,
            items: [
                {
                    width: 340,
                    height: 56,
                    border: false,
                    xtype: 'panel',
                    id: 'x-toast-msg-content-topbar-container',
                    autoScroll: true,
                    html: '<div style="margin: 2px" userToolTip="" id="x-toast-msg-content-topbar"></div>'

                },
                {
                    layout: 'vbox',
                    xtype: 'panel',
                    border: false,
                    frame: false,
                    width: 26,
                    height: 56,
                    layoutConfig: {
                        align: 'stretch',
                        pack: 'start'
                    },
                    defaults: {
                        margins: {
                            top: 2,
                            left: 2,
                            right: 2,
                            bottom: 2
                        }
                    },
                    items: [
                        {
                            xtype: 'button',
                            flex: 1,
                            iconCls: 'icon-farm-bin-empty',
                            handler: function() {
                                $('#x-toast-msg-content-topbar').html("");
                                Ext.each(APP.sysLog.items, function(i) {
                                    APP.sysLog.items.pop(i);
                                });
                                APP.events.throwInformation("Logul a fost sters!", true);
                            }
                        }
                    ]
                }
            ]
        };
    }
});

Ext.ns('APP.config');
Ext.apply(APP.config, {
    get: function(sKey) {
        var el;
        for (el in this.items) {
            if (this.items.hasOwnProperty(el)) {
                if (this.items[el].key == sKey) {
                    //console.log('de returnat: ', this.items[el].value);
                    return this.items[el];
                }
            }
        }
        return undefined;
    },
    /**
     * Returns value by key
     */
    getValue: function(sKey) {
        var el;
        for (el in this.items) {
            if (this.items.hasOwnProperty(el)) {
                if (this.items[el].key == sKey) {
                    //console.log('de returnat: ', this.items[el].value);
                    return this.items[el].value;
                }
            }
        }
        return undefined;
    }
});

Ext.ns('APP.main');
Ext.ns('APP.sessionEvents');
Ext.apply(APP.sessionEvents, {
    doLogging: true,
    // Submit session restore form to server and handles the response
    fnCheckSessionValid: function() {

        var oSessRefreshForm = APP.sessionManager.wSessionRefreshWindow.findByType('form')[0];
        oSessRefreshForm.buttons[0].setIconClass('x-icon-loading-butt');
        oSessRefreshForm.buttons[0].setDisabled(true);

        APP.sessionManager.wSessionRefreshWindow.findByType('form')[0].getForm().submit({
            method: 'post',
            success: function(action) {
                // succes now managed on failure method
            },
            failure: function(form, action) {

                // restore button state
                oSessRefreshForm.buttons[0].setIconClass('icon-lock-go');
                oSessRefreshForm.buttons[0].setDisabled(false);
                //console.log(action);
                if (action.failureType == 'server') {
                    try {
                        var oResponse = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.alert('Status', oResponse.description);
                    } catch (eer) {
                    }
                }
                try {
                    var oResp = Ext.util.JSON.decode(action.response.responseText);
                    if (oResp.error === true) {
                        if (oResp.type === 'multiplesession') {
                            Ext.MessageBox.confirm('Confirmare', 'Doriti suprimarea sesiunii existente?', function(btn) {

                                if (btn === 'yes') {
                                    var oKickUser = APP.sessionManager.wSessionRefreshWindow.findByType('form')[0].find('name', 'kickuser')[0];
                                    oKickUser.setValue(1);
                                    APP.sessionEvents.fnCheckSessionValid();
                                } else {
                                    APP.globals.setConfirmClose(false);
                                    window.location = APP.logout;
                                }

                            });
                        }
                    } else {
                        var oKickUser = APP.sessionManager.wSessionRefreshWindow.findByType('form')[0].find('name', 'kickuser')[0];
                        oKickUser.setValue(0);
                        APP.sessionManager.wSessionRefreshWindow.hide();
                        APP.sessionManager.bSessionRestoreWin = false;
                    }
                } catch (e) {
                }
            }
        });

    }
});

Ext.ns('APP.sessionManager');

Ext.apply(APP.sessionManager, {
    wSessionRefreshWindow: new Ext.Window({
        title: 'Sesiunea a expirat',
        iconCls: 'icon-fugue-lock',
        width: 400,
        autoHeight: true,
        closable: false,
        modal: true,
        resizable: false,
        plain: true,
        draggable: true,
        items: [
            {
                xtype: 'form',
                url: 'admin/sessions/xlogin',
                autoHeight: true,
                frame: true,
                monitorValid: true,
                defaultType: 'textfield',
                labelWidth: 80,
                items: [
                    {
                        xtype: 'box',
                        isFormField: false,
                        hideLabel: true,
                        autoEl: [
                            {
                                tag: 'div',
                                cls: 'x-form-box-big',
                                html: '<font color="red">Atentie:</font> Sesiunea dumneavoastra a expirat!<br />Dupa restaurarea sesiunii va rugam sa repetati actiunea dorita.'
                            }
                        ]
                    },
                    {
                        fieldLabel: 'Utilizator',
                        name: 'username',
                        anchor: '98%',
                        allowBlank: false
                    },
                    {
                        fieldLabel: 'Parola',
                        name: 'password',
                        anchor: '98%',
                        inputType: 'password',
                        allowBlank: false
                    },
                    {
                        name: 'kickuser',
                        inputType: 'hidden',
                        allowBlank: false,
                        value: 0
                    }
                ],
                buttons: [
                    {
                        text: 'Login',
                        formBind: true,
                        iconCls: 'icon-lock-go',
                        //scope: this,
                        handler: APP.sessionEvents.fnCheckSessionValid
                    },
                    {
                        text: 'Parasiti aplicatia',
                        handler: function() {
                            APP.globals.setConfirmClose(false);
                            window.location = APP.logout;
                        }
                    }
                ],
                keys: {
                    key: 13, // Enter key
                    handler: APP.sessionEvents.fnCheckSessionValid
                }
            }
        ]
    })
});

var bPopUp = true;
Ext.ns('APP.events');
Ext.apply(APP.events, {
    /**
     * Usage: APP.events.throwInformation('Error description here!');
     * @param {String} msg
     */
    throwInformation: function(msg) {
        if (!msg) {
            return;
        }
        msg = msg.replace(/\|br\|/gi, '<br />');

        if (arguments[1] === true) {
            Ext.MessageBox.show({
                msg: msg,
                title: 'Informatie',
                width: 400,
                icon: 'ext-mb-info',
                buttons: Ext.MessageBox.OK,
                fn: (arguments[2] || Ext.emptyFn
                        )
            });
        } else {
            Ext.ux.Toast.msg({
                title: 'Informatie',
                iconCls: 'icon-fugue-information'
            }, '{0}', msg);
        }
    },
    throwWarning: function(msg) {
        if (!msg) {
            return;
        }
        msg = msg ? msg.replace(/\|br\|/gi, '<br />') : "";

        if (arguments[1] === true) {
            Ext.MessageBox.show({
                msg: msg,
                title: 'Avertisment',
                width: 400,
                icon: 'ext-mb-warning',
                buttons: Ext.MessageBox.OK,
                fn: (arguments[2] || Ext.emptyFn
                        )
            });
        } else {
            Ext.ux.Toast.msg({
                title: 'Avertisment',
                iconCls: 'icon-fugue-exclamation'
            }, '{0}', msg);
        }
    },
    throwError: function(msg) {
        if (!msg) {
            return;
        }
        msg = msg ? msg.replace(/\|br\|/gi, '<br />') : "";

        if (arguments[1] === true) {
            Ext.MessageBox.show({
                msg: msg,
                title: 'Eroare',
                width: 400,
                icon: 'ext-mb-error',
                buttons: Ext.MessageBox.OK,
                fn: (arguments[2] || Ext.emptyFn
                        )
            });
        } else {
            Ext.ux.Toast.msg({
                title: 'Eroare',
                iconCls: 'icon-fugue-cross-circle'
            }, '{0}', msg);
        }
    },
    throwFormNotification: function(result) {

        if (result.error) {
            APP.events.throwError(result.description, true);
        }
        else {
            APP.events.throwInformation(result.description, true);
        }
    }
});

Ext.apply(APP.events, {
    beforeRequest: function() {
        APP.statusBar.showBusy('Interogare server in curs...');
    },
    requestException: function(conn, response, options) {
        APP.statusBar.clearStatus();
        APP.events.processRequestResponse(response);
    },
    requestComplete: function(conn, response, options) {
        APP.statusBar.clearStatus();
        APP.events.processRequestResponse(response);
    },
    processRequestResponse: function(response, options, exception) {

        try {

            if (exception !== undefined) {
                if (response.status != 500) {
                    APP.events.throwError('[status: ' + response.status + '] ' + response.statusText, bPopUp);
                }
            }
        } catch (e) {
            console.error(e);
        }
        try {
            if (response.status == 200 || response.status == 500 || !Ext.isDefined(response.status)) {

                // if response looks like standard response json
                if (response.responseText.search(/\{"error":/i) !== -1) {
                    // start decoding
                    var oResponse = Ext.util.JSON.decode(response.responseText);

                    if (oResponse.error !== undefined) {
                        if ((oResponse.error === true) || (oResponse.error == 'true')) {

                            if (oResponse.type == 'nosession') {
                                APP.sessionManager.wSessionRefreshWindow.show();
                                return false;
                            }

                            if (oResponse.type != 'silent') {
                                APP.events.throwError('[' + oResponse.type + '] ' + oResponse.description, bPopUp);
                            }
                        } else {
                            if ((oResponse.error === false) || (oResponse.error == 'false')) {
                                if (oResponse.type !== 'silent') {
                                    // silent response, do not echo
                                    APP.events.throwInformation(oResponse.description, bPopUp);
                                }
                            }
                        }
                    }
                }
            } else {
                APP.events.throwError('[' + response.status + '] ' + response.statusText, bPopUp);
            }
        } catch (er) {
            console.error(er);
        }

    },
    wChangePsswd: new Ext.Window({
        title: 'Schimbare parola',
        iconCls: 'icon-fugue-lock',
        width: 300,
        modal: true,
        closeAction: 'hide',
        layout: 'fit',
        height: 160,
        listeners: {
            beforehide: function() {
                this.getComponent("passForm").getForm().reset();
            }
        },
        items: [
            {
                xtype: 'form',
                frame: true,
                itemId: "passForm",
                monitorValid: true,
                items: [
                    {
                        fieldLabel: 'Vechea parola',
                        xtype: 'textfield',
                        inputType: 'password',
                        name: 'oldPassword',
                        allowBlank: false
                    },
                    {
                        fieldLabel: 'Noua parola',
                        xtype: 'textfield',
                        name: 'newPassword',
                        id: 'filedNewUserPassword',
                        vtype: 'PasswordValid',
                        inputType: 'password',
                        allowBlank: false
                    },
                    {
                        fieldLabel: 'Repeta parola',
                        xtype: 'textfield',
                        name: 'passwordConfirm',
                        initialPassField: 'filedNewUserPassword',
                        vtype: 'Password',
                        inputType: 'password',
                        allowBlank: false
                    }
                ],
                buttons: [
                    {
                        text: 'Schimba',
                        formBind: true,
                        handler: function() {
                            var oPost = this.findParentByType('form').getForm().getValues(false);
                            var btn = this;
                            Ext.Ajax.request({
                                url: 'main/xChangePassword',
                                method: 'POST',
                                params: oPost,
                                success: function(response, options) {
                                    var oRes = Ext.decode(response.responseText);
                                    if ((oRes.error) && (oRes.error === false)) {
                                        btn.findParentByType('form').getForm().reset();
                                        APP.events.wChangePsswd.hide();
                                    }
                                }
                            }, this);
                        }
                    }
                ]
            }
        ]
    }),
    fnChangePsswdWin: function() {
        APP.events.wChangePsswd.show();
    },
    fnUserDetailsWindow: function() {
        var wUserDetails = new Ext.Window({
            title: 'Informatii utilizator',
            iconCls: 'icon-fugue-information',
            width: 500,
            closeAction: 'hide',
            modal: true,
            layout: 'fit',
            height: 150,
            items: [
                {
                    xtype: 'xNomForm',
                    layout: 'fit',
                    cls: 'x-form-user-details',
                    url: 'main/userDetailsForm',
                    defaults: {
                        labelWidth: 130
                    },
                    params: {}
                }
            ]
        }).show();
    },
    fnUserEmailSuport: function() {
        var wUserSuport = new Ext.Window({
            title: 'Contact suport',
            iconCls: 'icon-email',
            width: 550,
            closeAction: 'hide',
            modal: true,
            layout: 'fit',
            height: 150,
            items: [
                {
                    bodyStyle: {
                        padding: "5px"
                    },
                    frame: true,
                    html: APP.email_suport,
                    xtype: "panel"
                }
            ]
        }).show();
    },
    fnAtasamenteSuport: function() {
        new Ext.Window({
            title: 'Lipire documente scanate',
            iconCls: 'icon-email',
            width: 550,
            closeAction: 'hide',
            modal: true,
            layout: 'fit',
            height: 250,
            items: [
                {
                    bodyStyle: {
                        padding: "5px"
                    },
                    frame: true,
                    html: APP.atasamente_suport,
                    xtype: "panel"
                }
            ]
        }).show();
    }

});

// server response message ajax handler
Ext.Ajax.on('beforerequest', APP.events.beforeRequest, this);
Ext.Ajax.on('requestcomplete', APP.events.requestComplete, this);
Ext.Ajax.on('requestexception', APP.events.requestException, this);

Ext.ns('APP.date');
Ext.apply(APP.date, {
    value: new Date(),
    setValue: function(date) {
        this.value = date;
    },
    history: {}
});


Ext.ns('APP.globals');
Ext.apply(APP.globals, {
    confirmClose: true,
    setConfirmClose: function(bValue) {
        this.confirmClose = bValue;
    }
});


Ext.apply(APP.main, {
    exec: function() {

        var oStatusClock = new Ext.Toolbar.TextItem('');
        var oStatusInformation = new Ext.Toolbar.TextItem({
            text: 'Nu sunt mesaje.'
        });



        var oMainStatusBar = new Ext.ux.StatusBar({
            defaultText: '',
            hidden: true,
            busyText: 'Se incarca...',
            statusAlign: 'left',
            items: [oStatusInformation, '-', oStatusClock]
        });

        // make it global in APP ns
        APP.statusBar = oMainStatusBar;

        var oNorthRegion = new Ext.Panel({
            region: 'north',
            height: 26,
            stateful: false,
            title: (APP.main_panel_title ? APP.main_panel_title : false),
            border: false,
            tbar: new Ext.Toolbar({
                items: [
                    {
                        xtype: 'buttongroup',
                        columns: 2,
                        //hidden : true,
                        //hidden: !inArray('admin', APP.user_rol),
                        hidden: valueOfArrayinArray(["trs_constructor", "trs_sef_fol", "trs_sef_sector", "trs_utilizator_dgsr"], APP.user_rol),
                        title: 'Administrare',
                        items: [
                            {
                                iconCls: 'icon-user',
                                text: 'Useri',
                                handler: APP.userScreen.exec
                            },
                            {
                                iconCls: 'icon-fugue-key',
                                text: 'Permisiuni',
                                handler: APP.permisiuneScreen.exec
                            },
                            {
                                iconCls: 'icon-fugue-key',
                                text: 'Acces',
                                handler: APP.accesScreen.exec
                            }
                        ]
                    },
                    ////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////////MODUL TERASAMENTE/////////////////////////
                    //////////////////////////////////////////////////////////////////////////////////////

                    {
                        xtype: 'buttongroup',
                        columns: 2,
                        title: "Helpie",
                        items: [
                           {
                               // handler: APP.helpie_orders.init,
                                iconCls: 'icon-fugue-table-money',
                                text: 'Comenzi Pachete',
                                handler:APP.helpie_comenzi.init
                            },
                            {
                                handler: APP.helpie_task.init,
                                iconCls: 'icon-fugue-plus-circle',
                                text: 'Tasks'
                            },
                            {
                                text: 'Nomenclatoare',
                                iconCls: 'icon-fugue-layers-stack',
                                hidden: (!inArray('admin', APP.user_rol) ? true : false),
                                menu: new Ext.menu.Menu({
                                    stateful: false,
                                    items: [
                                        {
                                            text: 'Pachete',
                                            icon: 'assets/admin/resources/img/database_add.png',
                                            handler: function() {
                                                var obj = {
                                                    tab_id: 'tabPachete',
                                                    iconCls: 'icon-database'
                                                }
                                                APP.helpie_nomenclator.gridPachete(obj);
                                            }
                                        },
                                        {
                                            text: 'Servicii',
                                            icon: 'assets/admin/resources/img/database_add.png',
                                            handler: function() {
                                                var obj = {
                                                    tab_id: 'tabServicii',
                                                    iconCls: 'icon-database'
                                                }
                                                APP.helpie_nomenclator.gridServicii(obj);
                                            }
                                        },
                                        {
                                            text: 'Texte EN',
                                            icon: 'assets/admin/resources/img/database_add.png',
                                            handler: function() {
                                                var obj = {
                                                    tab_id: 'tabServicii',
                                                    iconCls: 'icon-database'
                                                }
                                                APP.helpie_nomenclator.formTexte("en");
                                            }
                                        },
                                        {
                                            text: 'Texte RO',
                                            icon: 'assets/admin/resources/img/database_add.png',
                                            handler: function() {
                                                var obj = {
                                                    tab_id: 'tabServicii',
                                                    iconCls: 'icon-database'
                                                }
                                                APP.helpie_nomenclator.formTexte("ro");
                                            }
                                        },
                                    ]
                                })
                            },
                            {
                                text: 'Acces',
                                handler: APP.helpie_email.inbox,
                                text: 'Mesagerie',
                                        icon: 'assets/admin/resources/img/email.png'
                            }

                        ]
                    },
                    '->',
                    APP.getLogPanel(),
                    {
                        text: 'Logout',
                        iconCls: 'icon-fugue-control-power',
                        tooltip: 'Inchideti sesiunea.',
                        handler: function() {
                            APP.globals.setConfirmClose(false);
                            window.location = APP.logout;
                        }
                    }
                ]
            })
        });


        var oCenterRegion = {
            region: 'center',
            border: false,
            id: 'center',
            xtype: 'tabpanel',
            activeItem: 0,
            deferredRender: false,
            items: [
                {
                    title: 'Prima Pagina',
                    cls: 'x-gas-background',
                    autoLoad: "admin/main/getFirstPage/"
                }
            ]
        };

        var oSouthRegion = {
            region: 'south',
            layout: 'fit',
            collapsible: false,
            contentEl: 'south_region_iefix',
            bbar: oMainStatusBar
        };

        var oAppViewport = new Ext.Viewport({
            layout: 'border',
            items: [oNorthRegion, oCenterRegion, oSouthRegion],
            renderTo: Ext.getBody()
        });

        APP.oNorthRegion = oAppViewport.getComponent(0);
        APP.oCenterRegion = oAppViewport.getComponent(1);
        APP.oSouthRegion = oAppViewport.getComponent(2);

        Ext.fly(oStatusClock.getEl()).addClass('x-bold-status');

        var dtSrvUTC = 1;
        var dtTZOffsetDate = new Date(dtSrvUTC);
        var iTZOffsetHour = (dtTZOffsetDate.getTimezoneOffset()) / 60;

        var fnGetDateFromSrv = function() {
            Ext.Ajax.request({
                url: 'timer.php',
                method: 'POST',
                params: {
                    control: 1
                },
                success: function(response, options) {
                    dtSrvUTC = parseInt(response.responseText, 10);
                    dtTZOffsetDate = new Date(dtSrvUTC);
                    iTZOffsetHour = (dtTZOffsetDate.getTimezoneOffset()) / 60;
                },
                failure: function() {

                }
            });

        };

        // Get server date every 10 minutes
        /*
         Ext.TaskMgr.start({
         run: fnGetDateFromSrv,
         interval: 10 * 60 * 1000
         });
         
         Ext.TaskMgr.start({
         run: function () {
         
         var dtSrvDate = new Date(dtSrvUTC);
         
         var dtYear = dtSrvDate.getFullYear();
         var dtMonth = dtSrvDate.getMonth();
         var dtDay = dtSrvDate.getDate();
         var dtHours = dtSrvDate.getHours();
         var dtMinutes = dtSrvDate.getMinutes();
         var dtSeconds = dtSrvDate.getSeconds();
         
         dtSrvUTC = Date.UTC(dtYear, dtMonth, dtDay, dtHours + iTZOffsetHour, dtMinutes, dtSeconds + 1, dtSrvDate.getMilliseconds());
         
         // update APP.date.value
         APP.date.setValue(new Date(dtSrvUTC));
         
         var sSrvDateStatus = dtSrvDate.format('l, d-M-Y G:i:s');
         Ext.fly(oStatusClock.getEl()).update('Data server: ' + sSrvDateStatus);
         },
         interval: 1000
         });
         */

        // hide loading mask
        $("#loading-mask").fadeOut("slow");
        $("#loading").fadeOut("slow");
        //APP.events.throwInformation('Aici este zona afisare notificari aplicatie.');

        window.onbeforeunload = function() {
            if (APP.globals.confirmClose === true) {
                return "Sigur doriti sa parasiti aceasta pagina?\r\nDatele nesalvate vor fi pierdute!";
            }
        };
    }
});

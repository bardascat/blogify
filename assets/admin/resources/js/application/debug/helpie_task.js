Ext.ns('APP.helpie_task');


Ext.apply(APP.helpie_task, {
    id: 'idTabTask',
    init: function(config) {
        APP.helpie_task.id = "idTabTask";
        if (!Ext.getCmp(APP.helpie_task.id)) {

            var oTasksGrid = APP.helpie_task.getTasksGrid({id_list: "root"}, {});

            var navigationPanel = new Ext.tree.TreePanel({
                region: 'west',
                id: 'taskNav', // see Ext.getCmp() below
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
                border: false,
                dataUrl: 'admin/tasks/getTasksList',
                requestMethod: 'GET',
                preloadChildren: true,
                tbar: [
                    {
                        text: "Lista noua",
                        icon: "assets/admin/resources/img/email_edit.png",
                        handler: function() {
                            APP.helpie_task.newList(navigationPanel);
                        },
                    },
                    {
                        text: "Sterge Lista",
                        icon: "assets/admin/resources/img/delete.png",
                        handler: function() {
                            APP.helpie_task.deleteList();
                        },
                    }
                ],
                root: {
                    expanded: true,
                    preloadChildren: true,
                    nodeType: 'async',
                    text: 'All Tasks',
                    draggable: false,
                    id: 'root'
                },
                rootVisible: true,
                listeners: {
                    click: function(n) {

                        var idList = n.attributes.id;
                        APP.helpie_task.refreshEtasksGrid(idList);
                    }
                }
            });

            var oNomTab = new Ext.Panel({
                title: 'Tasks',
                id: APP.helpie_task.id,
                iconCls: 'icon-fugue-plus-circle',
                closable: true,
                layout: 'fit',
                items: [
                    new Ext.Panel({
                        layout: 'border',
                        items: [
                            navigationPanel,
                            oTasksGrid
                        ]
                    })
                ],
            });

            //adaugare tab la tabpanel-ul principal
            APP.oCenterRegion.add(oNomTab);
            APP.oCenterRegion.doLayout();

        }
        //activare tab
        APP.oCenterRegion.setActiveTab(APP.helpie_task.id);
    },
    newList: function(treePanel) {
        var createTaskListForm = new Ext.FormPanel({
            labelWidth: 100,
            frame: true,
            stateful: false,
            height: "100%",
            id: "createTaskListForm",
            layout: 'form',
            defaults: {
                anchor: "100%",
                xtype: "textfield",
                allowBlank: false
            },
            items: [
                {
                    name: 'name',
                    minLength: 3,
                    fieldLabel: 'Nume lista'
                },
            ],
            buttons: [
                {
                    text: 'Creaza',
                    handler: function() {
                        if (!createTaskListForm.getForm().isValid()) {
                            return;
                        }
                        createTaskListForm.getForm().submit({
                            params: {},
                            waitMsg: 'Loading...',
                            url: "admin/tasks/newTaskList",
                            success: function(form, response) {
                                //refresh tree
                                treePanel.getRootNode().reload();
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
            title: "Creaza o lista de taskuri",
            width: 350,
            autoHeight: true,
            stateful: false,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [createTaskListForm]
        }).show();


    },
    refreshEtasksGrid: function(id_list) {
        Ext.getCmp("tasksGrid").store.load(
                {
                    params: {id_list: id_list},
                    callback: function(records, options, success) {
                    }
                }
        );
        Ext.getCmp("tasksGrid").getView().refresh();
    },
    getTasksGrid: function(storeBaseParams, config) {

        APP.helpie_task.taskCM = new Ext.grid.ColumnModel({
            columns: [
                {
                    header: "Actiuni",
                    css: "text-align:center;",
                    xtype: 'actioncolumn',
                    summaryType: 'count',
                    hideable: false,
                    summaryRenderer: function(v, params, data) {
                        return "Total: " + ((v === 0 || v > 1) ? '(' + v + ' Tasks)' : '(1 Task)');
                    },
                    width: 50,
                    items: [
                        {
                            tooltip: "Inchide task",
                            getClass: this.getActionClassTaskActive,
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = grid.store.getAt(rowIndex);
                                var id_task = rec.get('id_task');
                                APP.helpie_task.closeTask(grid, id_task);
                            }
                        },
                        {
                            tooltip: "Reminder",
                            getClass: this.getActionClassTaskReminder,
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = grid.store.getAt(rowIndex);
                                var id_task = rec.get('id_task');
                                APP.helpie_task.showReminders(grid, id_task);
                            }
                        },
                        {
                            tooltip: "Editare ",
                            icon: 'assets/admin/resources/img/note_pencil.png',
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = grid.store.getAt(rowIndex);
                                var id_task = rec.get('id_task');
                                APP.helpie_task.newTask({
                                    id_task: id_task,
                                    action: "edit"
                                });
                            }
                        },
                        {
                            icon: 'assets/admin/resources/img/calendar_day.png', // Use a URL in the icon config
                            tooltip: "Istoric Task",
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = grid.store.getAt(rowIndex);
                                APP.helpie_task.getLog(rec.get("id_task"));
                            }
                        }]
                },
                {
                    header: "ID",
                    dataIndex: 'id_task',
                    width: 50,
                    filter: {
                        type: 'string'
                    },
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {
                        return value;
                    },
                },
                {
                    header: "Nume Task",
                    dataIndex: 'name',
                    width: 200,
                    filter: {
                        type: 'string'
                    },
                    renderer: APP.renderer.nowrap,
                },
                {
                    header: "Status",
                    dataIndex: 'status',
                    width: 50,
                    filter: {
                        type: 'string'
                    },
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {

                        switch (value) {
                            case "1":
                                {
                                    return "Pending";
                                }
                                break;
                            case "2":
                                {
                                    return "Closed";
                                }
                                break;
                            case "3":
                                {
                                    return "Canceled";
                                }
                                break;
                        }
                    },
                },
                {
                    header: "Client",
                    dataIndex: 'client_lastname',
                    width: 80,
                    filter: {
                        type: 'string'
                    },
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {
                        return value + " " + record.get("client_firstname");
                    },
                },
                {
                    header: "Operator",
                    dataIndex: 'operator_lastname',
                    width: 80,
                    filter: {
                        type: 'string'
                    },
                    renderer: function(value, metadata, record, rowIndex, columnIndex, store) {
                        return value + " " + record.get("operator_firstname");
                    },
                },
                {
                    header: "Lista",
                    dataIndex: 'tasklist_name',
                    width: 70,
                    filter: {
                        type: 'string'
                    }
                },
                {
                    header: "termen",
                    dataIndex: 'dueDate',
                    renderer: APP.renderer.dateBusiness,
                    width: 70,
                    filter: {
                        type: 'date'
                    }
                }
            ],
            defaults: {
                sortable: true
            }
        });


        var summary = new Ext.ux.grid.GroupSummary();

        var oTasksGrid = new Ext.ux.fatGrid({
            xtype: "uxFatGrid",
            id: "tasksGrid",
            height: "100%",
            stateful: false,
            gridConfig: {
                viewConfig: new Ext.grid.GroupingView({
                    getRowClass: function(record, rowIndex, rp, ds) {
                        var status_id = record.get('status');
                        //sel nou
                        if (status_id == 1) {
                            return 'task_pending';
                        }
                        if (status_id == 2) {
                            return 'task_closed';
                        }
                        if (status_id == 3) {
                            return 'task_canceled';
                        }
                    },
                    forceFit: true,
                    onLoad: Ext.emptyFn,
                    groupOnSort: false,
                    enableGroupingMenu: false,
                    groupTextTpl: '{text}'
                }),
                filterable: true,
                idProperty: "id_task",
                sortDir: "ASC",
                sortField: "status",
                storeBaseParams: storeBaseParams,
                url: 'admin/tasks/getTasksGrid',
                fields: ["id_task", "operator_lastname", "status", 'operator_firstname', "client_firstname", "client_lastname", "dueDate", "name", "tasklist_name"],
                group: config.group,
                groupOnSort: false,
                groupField: "status"
            },
            stateful: true,
                    split: true,
            collapsible: false,
            region: 'center', // a center region is ALWAYS required for border layout
            id: "tasksGrid",
                    cm: APP.helpie_task.taskCM,
            title: (config.title ? config.title : "Taskuri"),
            loadMask: {
                msg: "Incarc taskurile..."
            },
            plugins: [summary],
            tbar: [
                {
                    text: "Task Nou",
                    iconCls: 'icon-fugue-plus-circle',
                    handler: function() {
                        APP.helpie_task.newTask({
                            id_task: null,
                            action: "add"
                        });
                    },
                },
                {
                    text: "Cancel Task",
                    icon: "assets/admin/resources/img/email_delete.png",
                    handler: function() {
                        APP.helpie_task.cancelTask(oTasksGrid);
                    }
                }
            ]
        });
        return oTasksGrid;
    },
    getLog: function(id_task) {
        try {
            var oCm = new Ext.grid.ColumnModel({
                columns: [{
                        header: "Nume Task",
                        dataIndex: 'taskname',
                        width: 200
                    }, {
                        header: "Operator",
                        dataIndex: 'username',
                        widht: 110,
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Data",
                        width: 130,
                        dataIndex: 'stamp',
                        renderer: APP.renderer.dateBusiness,
                        filter: {
                            type: 'date'
                        }
                    },
                    {
                        width: 400,
                        header: "Observatii",
                        renderer: APP.renderer.nowrap,
                        dataIndex: 'content',
                        filter: {
                            type: 'string'
                        }
                    },
                ],
                defaults: {
                    sortable: true
                }
            });

            var oGrid = new Ext.ux.fatGrid({
                xtype: "uxFatGrid",
                layout: 'fit',
                itemId: "oGrid",
                region: 'center',
                filterable: true,
                tbar: [
                    {
                        text: "Adauga Note",
                        iconCls: 'icon-fugue-plus-circle',
                        handler: function() {
                            APP.helpie_task.addTaskNote(oGrid, id_task);
                        },
                    }
                ],
                gridConfig: {
                    filterable: true,
                    idProperty: 'id_note',
                    url: 'admin/tasks/getTaskLog',
                    sortField: "stamp",
                    sortDir: "DESC",
                    storeBaseParams: {
                        id_task: id_task
                    },
                    fields: ["content", 'stamp', 'id_note', 'username', "taskname"]
                },
                cm: oCm
            });


            //constructie fereastra
            oWind = new Ext.Window({
                title: "Istoric Task",
                width: 800,
                height: 500,
                id: "task_log",
                closable: true,
                layout: 'fit',
                modal: true,
                closeAction: 'close',
                items: [oGrid]
            }).show();

        } catch (er) {
            alert(er);
        }
    },
    addTaskNote: function(grid, id_task) {


        var newTaskNoteForm = new Ext.FormPanel({
            labelWidth: 100,
            frame: true,
            stateful: false,
            height: "100%",
            id: "newTaskNoteForm",
            layout: 'form',
            labelAlign: 'top',
            defaults: {
                anchor: "100%",
                xtype: "textfield",
                allowBlank: false
            },
            items: [
                {
                    allowBlank: false,
                    xtype: 'textarea',
                    height: 300,
                    name: 'content',
                    margins: {top: 4, right: 4, bottom: 4, left: 4},
                    fieldLabel: 'Observatie'
                }
            ],
            buttons: [
                {
                    text: 'Trimite',
                    handler: function() {
                        if (!newTaskNoteForm.getForm().isValid()) {
                            return;
                        }
                        newTaskNoteForm.getForm().submit({
                            params: {id_task: id_task},
                            waitMsg: 'Loading...',
                            url: "admin/tasks/addTaskNote",
                            success: function(form, response) {
                                grid.store.reload();
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
            title: "Adauga Note",
            width: 550,
            height: 300,
            stateful: false,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [newTaskNoteForm]
        }).show();

    },
    newTask: function(config) {
        var grid = Ext.getCmp("tasksGrid");


        var newTaskForm = new Ext.FormPanel({
            labelWidth: 100,
            frame: true,
            stateful: false,
            height: "100%",
            id: "newTaskForm",
            layout: 'form',
            labelAlign: 'top',
            defaults: {
                anchor: "100%",
                xtype: "textfield",
                allowBlank: false
            },
            items: [
                {
                    fieldLabel: "Operator ",
                    xtype: (inArray('admin', APP.user_rol) ? "uxFCombo" : "hidden"),
                    name: "id_operator",
                    id: "operatorCombo",
                    allowBlank: false,
                    easyConfig: {
                        baseParams: {
                            user_status: 1,
                            user_rol: "operator"
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
                            },
                            select: function() {
                                Ext.getCmp("pachetCombo").clearValue();
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
                        select: function() {
                            Ext.getCmp("serviciuCombo").clearValue();
                        },
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
                        proxyUrl: 'admin/nomenclator/getUserPachete'
                    }
                },
                {
                    id: "serviciuCombo",
                    fieldLabel: "Serviciu",
                    xtype: "uxFCombo",
                    name: "id_serviciu",
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
                                mapping: "id_serviciu"
                            },
                            {
                                name: "name",
                                mapping: "name"
                            }
                        ],
                        proxyUrl: 'admin/nomenclator/getGridServicii'
                    }
                },
                {
                    id: "listaTaskCombo",
                    fieldLabel: "Lista",
                    xtype: "uxFCombo",
                    name: "id_list",
                    allowBlank: false,
                    easyConfig: {
                        baseParams: {
                            list_type: (config.action == "edit" ? null : "pending")
                        },
                        readerFields: [
                            {
                                name: "id",
                                mapping: "id_list"
                            },
                            {
                                name: "name",
                                mapping: "name"
                            }
                        ],
                        proxyUrl: 'admin/tasks/getTaksListsCombo'
                    }
                },
                {
                    xtype: "xdatetime",
                    timeFormat: "H:i",
                   // minValue: new Date(),
                    dateFormat: "d.m.Y",
                    fieldLabel: "Termen",
                    name: "dueDate",
                    allowBlank: false,
                },
                {
                    id: "name",
                    name: 'name',
                    minLength: 3,
                    fieldLabel: 'Nume Task'
                },
                {
                    allowBlank: false,
                    xtype: 'htmleditor',
                    name: 'content',
                    margins: {top: 4, right: 4, bottom: 4, left: 4},
                    id: "taskContentTextArea",
                    fieldLabel: 'Descriere Task'
                },
                {
                    name: "id_task",
                    xtype: "hidden",
                    id: "idTaskInput"
                }
            ],
            buttons: [
                {
                    text: 'Trimite',
                    handler: function() {
                        if (!newTaskForm.getForm().isValid()) {
                            return;
                        }
                        newTaskForm.getForm().submit({
                            params: {},
                            waitMsg: 'Loading...',
                            url: "admin/tasks/newTask",
                            success: function(form, response) {
                                grid.store.reload();
                                APP.events.throwFormNotification(response.result);
                                oWind.close();
                            }
                        });
                    }
                }
            ]
        });

        Ext.getCmp("clientCombo").on("select", function(store, params) {
            Ext.getCmp("pachetCombo").clearValue();
            Ext.getCmp("serviciuCombo").clearValue();
        });
        Ext.getCmp("pachetCombo").on("select", function(store, params) {
            Ext.getCmp("serviciuCombo").clearValue();
        });

        Ext.getCmp("pachetCombo").store.on("beforeload", function(store, params) {
            if (!Ext.getCmp("clientCombo").getValue()) {
                APP.events.throwInformation("Alegeti intai Clientul", true);
                return false;
            }


            Ext.getCmp("pachetCombo").store.baseParams = {
                id_user: Ext.getCmp("clientCombo").getValue(),
                query: Ext.getCmp("pachetCombo").getValue()
            };
        });

        Ext.getCmp("serviciuCombo").store.on("beforeload", function(store, params) {
            if (!Ext.getCmp("pachetCombo").getValue()) {
                APP.events.throwInformation("Alegeti intai Pachetul", true);
                return false;
            }


            Ext.getCmp("serviciuCombo").store.baseParams = {
                id_pachet: Ext.getCmp("pachetCombo").getValue(),
                query: Ext.getCmp("serviciuCombo").getValue()
            };
        });

        oWind = new Ext.Window({
            title: "Creaza task",
            width: 650,
            height: 550,
            stateful: false,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [newTaskForm]
        });

        if (config.action == "edit") {
            Ext.MessageBox.wait('Loading');
            Ext.Ajax.request({
                url: 'admin/tasks/getTask',
                params: {
                    id_task: config.id_task
                },
                success: function(response) {
                    Ext.MessageBox.hide();
                    var oRes = Ext.decode(response.responseText);
                    if (!oRes.task[0]) {
                        APP.events.throwerror("Eroare: taskul cu acest ID nu a fost gasit!", true);
                    }

                    Ext.getCmp("idTaskInput").setValue(config.id_task);
                    oRes.task = oRes.task[0];
                    oRes.task.dueDate = oRes.task.dueDate.date;
                    oRes.task.id_client = oRes.task.client.id_user;
                    oRes.task.id_operator = oRes.task.operator.id_user;
                    oRes.task.id_client_val = oRes.task.client.lastname + " " + oRes.task.client.firstname;
                    oRes.task.id_operator_val = oRes.task.operator.lastname + " " + oRes.task.operator.firstname;

                    oRes.task.id_serviciu = oRes.task.serviciu.id_serviciu;
                    oRes.task.id_serviciu_val = oRes.task.serviciu.name;

                    oRes.task.id_pachet = oRes.task.pachet.id_pachet;
                    oRes.task.id_pachet_val = oRes.task.pachet.name;

                    oRes.task.id_list = oRes.task.taskList.id_list;
                    oRes.task.id_list_val = oRes.task.taskList.name;

                    Ext.getCmp("clientCombo").setReadOnly(true);
                    fnCompleteForm(newTaskForm, oRes.task);
                    //daca este task inchis sau anulat si nu e admin anulam forma
                    if ((oRes.task.status == "3" || oRes.task.status == "2") && !inArray('admin', APP.user_rol))
                        newTaskForm.disable();
                    oWind.show();
                }
            });
        } else {
            oWind.show();
        }

    },
    cancelTask: function(grid) {
        if (grid.selModel.getCount() !== 1) {
            APP.events.throwInformation("Selectati taskul pe care doriti sa il anulati!", true);
            return;
        }

        if (grid.selModel.getSelected().data.status == "2" || grid.selModel.getSelected().data.status == "3") {
            APP.events.throwError("Task-ul nu poate fi anulat", true);
            return;
        }
        Ext.MessageBox.confirm('Confirmare', 'Sigur doriti anularea taskului?', function(btn_salvare) {
            if (btn_salvare === 'yes') {
                Ext.MessageBox.wait('Loading');
                Ext.Ajax.request({
                    url: 'admin/tasks/cancelTask',
                    params: {
                        id_task: grid.selModel.getSelected().id
                    },
                    success: function(response) {
                        Ext.MessageBox.hide();
                        APP.events.throwInformation("Task-ul a fost anulat cu succes, si mutat in lista 'Canceled Tasks'", true);
                        grid.store.reload();
                    }
                });

            }
        });


    },
    closeTask: function(grid, id_task) {
        Ext.MessageBox.confirm('Confirmare', 'Sigur doriti inchiderea acestui task?', function(btn_salvare) {
            if (btn_salvare === 'yes') {

                Ext.Ajax.request({
                    url: 'admin/tasks/closetask',
                    params: {
                        id_task: id_task
                    },
                    success: function(response) {
                        var res = Ext.decode(response.responseText);
                        APP.events.throwInformation(res.description, true);
                        grid.store.reload();
                    }
                });

            }
        });
    },
    showReminders: function(grid, id_task) {

        try {
            var oCm = new Ext.grid.ColumnModel({
                columns: [
                    {
                        header: "Data Reminder",
                        dataIndex: 'reminder_date',
                        renderer: APP.renderer.dateBusiness,
                        width: 40,
                        filter: {
                            type: 'date'
                        }
                    },
                    {
                        header: "Operator",
                        dataIndex: 'operator',
                        width: 40,
                        filter: {
                            type: 'string'
                        }
                    },
                    {
                        header: "Descriere",
                        dataIndex: 'reminder_description',
                        widht: 710,
                        renderer: APP.renderer.nowrap,
                        filter: {
                            type: 'string'
                        }
                    },
                ],
                defaults: {
                    sortable: true
                }
            });

            var oGrid = new Ext.ux.fatGrid({
                xtype: "uxFatGrid",
                layout: 'fit',
                itemId: "oGrid",
                stateful: false,
                loadMask: {
                    msg: "Incarc reminderele"
                },
                region: 'center',
                filterable: true,
                tbar: [
                    {
                        text: "Adauga Reminder",
                        iconCls: 'icon-fugue-plus-circle',
                        handler: function() {
                            APP.helpie_task.addReminder(oGrid, id_task);
                        },
                    }
                ],
                gridConfig: {
                    filterable: true,
                    idProperty: 'id_reminder',
                    url: 'admin/tasks/getTaskReminders',
                    sortField: "reminder_date",
                    sortDir: "ASC",
                    storeBaseParams: {
                        id_task: id_task
                    },
                    fields: ["reminder_date", 'id_reminder', 'reminder_description', 'operator']
                },
                cm: oCm
            });


            //constructie fereastra
            oWind = new Ext.Window({
                title: "Remindere Task",
                width: 800,
                height: 500,
                id: "task_reminder",
                closable: true,
                layout: 'fit',
                modal: true,
                closeAction: 'close',
                items: [oGrid]
            }).show();

        } catch (er) {
            alert(er);
        }

    },
    addReminder: function(grid, id_task) {
        var newReminderForm = new Ext.FormPanel({
            labelWidth: 100,
            frame: true,
            stateful: false,
            height: "100%",
            id: "newReminderForm",
            layout: 'form',
            labelAlign: 'top',
            defaults: {
                anchor: "100%",
                xtype: "textfield",
                allowBlank: false
            },
            items: [
                {
                    xtype: "xdatetime",
                    timeFormat: "H:i",
                    minValue: new Date(),
                    dateFormat: "d.m.Y",
                    fieldLabel: "Data Reminder",
                    name: "reminder_date",
                    allowBlank: false,
                },
                {
                    allowBlank: false,
                    xtype: 'textarea',
                    height: 300,
                    name: 'reminder_description',
                    margins: {top: 4, right: 4, bottom: 4, left: 4},
                    fieldLabel: 'Descriere'
                }
            ],
            buttons: [
                {
                    text: 'Salveaza',
                    handler: function() {
                        if (!newReminderForm.getForm().isValid()) {
                            return;
                        }
                        newReminderForm.getForm().submit({
                            params: {id_task: id_task},
                            waitMsg: 'Loading...',
                            url: "admin/tasks/addReminder",
                            success: function(form, response) {
                                grid.store.reload();
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
            title: "Adauga Reminder",
            width: 550,
            height: 300,
            stateful: false,
            layout: 'fit',
            modal: true,
            closeAction: 'close',
            items: [newReminderForm]
        }).show();
    },
    getActionClassTaskReminder: function(val, meta, rec) {

        if (rec.get('status') == "2" || rec.get('status') == "3")
            return "hiddenCls";
        else
            return "taskReminderCls";
    },
    getActionClassTaskActive: function(val, meta, rec) {
        console.log(rec.get('status'));
        if (rec.get('status') == "1")
            return "taskReminderSetActiveCls";
        else
            return "hiddenCls";
    }
}); 
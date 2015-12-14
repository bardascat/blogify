Ext.ns('APP.accesScreen');

Ext.apply(APP.accesScreen, {

   //functie de stergere / editare inregistrare
   actionPermisiune : function() {
      var oGrid, iRec = 0, oWind, op = "edit";

      oGrid = Ext.getCmp(APP.accesScreen.id).getComponent("permisiuneGrid");

      //setare tip operatie; daca este butonul de adaugare / editare atunci se citeste paramentrul `action` al acestuia
      if (this.action) {
         op = this.action;
      }

      if (op === "edit") {
         if (oGrid.selModel.getCount() !== 1) {
            APP.events.throwInformation("Selectati o inregistrare !");
            return;
         }
         iRec = oGrid.selModel.getSelected().id;
      }

      //constructie formular
      var oFpAdd = new Ext.FormPanel({
         autoHeight : true,
         labelWidth : 100,
         frame : true,
         bodyStyle : {
            padding : "5px"
         },
         layout : 'form',
         defaults : {
            xtype : "textfield",
            anchor : "90%"
         },
         items : [{
            name : 'perm_cod',
            fieldLabel : 'Cod'
         }, {
            name : 'perm_nume',
            fieldLabel : 'Nume'
         }, {
            name : "perm_id",
            xtype : "hidden",
            value : iRec
         }],
         buttons : [{
            text : 'Salvare',
            handler : function() {

               if (!oFpAdd.getForm().isValid()) {
                  return;
               }

               oFpAdd.getForm().submit({
                  url : "admin/permisiune/editRecord/",
                  success : function(form, response) {
                     oGrid.store.reload();
                     oWind.close();
                  }
               });
            }
         }]
      });

      //constructie fereastra
      oWind = new Ext.Window({
         title : (op === "edit") ? "Editare inregistrare" : "Adaugare inregistrare",
         width : 350,
         layout : 'fit',
         modal : true,
         closeAction : 'close',
         items : [oFpAdd]
      }).show();

      //daca este editare de inregistrare atunci se aduc datele acestei inregistrari
      if (op === "edit") {
         oFpAdd.on("afterlayout", function() {
            Ext.Ajax.request({
               url : 'admin/permisiune/getRecord/',
               params : {
                  perm_id : iRec
               },
               success : function(response) {
                  var oRes = Ext.decode(response.responseText);
                  var oData = oRes.data;
                  fnCompleteForm(oFpAdd, oData);
               }
            });
         }, this, {
            single : true
         });
      }
   }
});

Ext.apply(APP.accesScreen, {

   id : 'idTabAccesScreen',
   exec : function() {

      if (!Ext.getCmp(APP.accesScreen.id)) {

         var oCmRol = new Ext.grid.ColumnModel({
            columns : [{
               header : "Nume",
               dataIndex : 'rol_nume',
               width : 200
            }],
            defaults : {
               sortable : true
            }
         });

         var oRolGrid = new Ext.ux.fastGrid({
            xtype : "uxFGrid",
            layout : 'fit',
            itemId : "rolGrid",
            flex : 1,
            title : "Rol",
            gridConfig : {
               url : 'admin/rol/getData',
               fields : ['rol_id', 'rol_nume']
            },
            cm : oCmRol,
            listeners : {
               'rowclick' : function() {
                  if (oRolGrid.selModel.getCount() !== 1) {
                     return;
                  }
                  var rol_id = oRolGrid.selModel.getSelected().id;

                  oPermisiuneGrid.getStore().reload({
                     params : {
                        rol_id : rol_id
                     }
                  });
               }
            }
         });

         var oCmPermisiune = new Ext.grid.ColumnModel({
            columns : [{
               header : "Id",
               dataIndex : 'perm_id',
               width : 40
            }, {
               header : "Cod",
               dataIndex : 'perm_cod'
            }, {
               header : "Nume",
               dataIndex : 'perm_nume'
            }, {
               header : "Tip",
               dataIndex : 'perm_tip',
               width : 200
            }, {

               header : "Acces",
               dataIndex : 'rp_valoare',
               width : 200,
               css : "background-color: #eefffe !important;",
               renderer : APP.renderer.permisie,
               editor : new Ext.form.ComboBox({
                  store : new Ext.data.SimpleStore({
                     fields : [{
                        name : "id"
                     }, {
                        name : "name"
                     }],
                     data : [["1", "Allow"], ["2", "Deny"], ["0", "Ignore"]]
                  }),
                  displayField : 'name',
                  valueField : 'id',
                  editable : false,
                  mode : 'local',
                  forceSelection : true,
                  triggerAction : 'all',
                  selectOnFocus : true
               })
            }],
            defaults : {
               sortable : true
            }
         });

         var oPermisiuneGrid = new Ext.ux.fastEditorGrid({
            layout : 'fit',
            itemId : "permisiuneGrid",
            region : 'center',
            flex : 3,
            title : "Permisiune",
            tbar : ["-"],
            plugins : [new Ext.ux.grid.Search({
               disableIndexes : ["perm_id"]
            })],
            clicksToEdit : 1,
            gridConfig : {
               sortField : "perm_cod",
               url : 'admin/acces/getData/',
               fields : ['perm_id', 'rp_valoare', 'perm_cod', 'perm_nume', "perm_tip"]
            },
            cm : oCmPermisiune,
            listeners : {
               beforeedit : function(e) {
                  if (oRolGrid.selModel.getCount() === 0) {
                     APP.events.throwInformation("Selectati un rol !");
                     e.record.reject();
                     return;
                  }
               }
            }
         });

         //creare tab ce contine gridul
         var oNomTab = new Ext.Panel({
            title : 'Acces',
            id : APP.accesScreen.id,
            iconCls : 'icon-fugue-key',
            closable : true,
            layout : {
               type : 'hbox',
               pack : 'start',
               align : 'stretch'
            },
            items : [oRolGrid, oPermisiuneGrid]
         });

         //adaugare tab la tabpanel-ul principal
         APP.oCenterRegion.add(oNomTab);
         APP.oCenterRegion.doLayout();

      }
      //activare tab
      APP.oCenterRegion.setActiveTab(APP.accesScreen.id);
   }
}); 
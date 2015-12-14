Ext.ns('APP.permisiuneScreen');

Ext.apply(APP.permisiuneScreen, {

   //functie de stergere / editare inregistrare
   addPerm : function() {
      var iRec = 0, oWind, op = "add";
      var oGrid = Ext.getCmp(APP.permisiuneScreen.id).getComponent("permisiuneGrid");

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
            name : 'perm_nume',
            fieldLabel : 'Nume'
         }, {
            name : 'perm_cod',
            fieldLabel : 'Cod'
         }, {
            name : 'perm_tip',
            fieldLabel : 'Tip'
         }, {
            name : 'perm_activ',
            fieldLabel : 'Activ',
            xtype : "xcheckbox"
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
         title : "Adaugare inregistrare",
         width : 350,
         layout : 'fit',
         modal : true,
         closeAction : 'close',
         items : [oFpAdd]
      }).show();
   }
});

Ext.apply(APP.permisiuneScreen, {

   id : 'idTabpermisiuneScreen',
   exec : function() {

      if (!Ext.getCmp(APP.permisiuneScreen.id)) {

         var oCmPermisiune = new Ext.grid.ColumnModel({
            columns : [{
               header : "Id",
               dataIndex : 'perm_id',
               width : 40
            }, {
               header : "Cod",
               dataIndex : 'perm_cod',
               css : "background-color: #eefffe !important;",
               editor : new Ext.form.TextField({
                  allowBlank : false
               }),
	            filter : {
		            type : 'string'
	            }
            }, {
               header : "Nume",
               dataIndex : 'perm_nume',
               css : "background-color: #eefffe !important;",
               editor : new Ext.form.TextField({
                  allowBlank : false
               }),
	            filter : {
		            type : 'string'
	            }
            }, {
               header : "Tip",
               dataIndex : 'perm_tip',
               css : "background-color: #eefffe !important;",
               width : 200,
               editor : new Ext.form.TextField({
                  allowBlank : false
               }),
	            filter : {
		            type : 'string'
	            }
            }, {
               header : "Activ",
               dataIndex : 'perm_activ',
               css : "background-color: #eefffe !important;",
               renderer : APP.renderer.yesNo,
               editor : new Ext.ux.form.XCheckbox()
            }],
            defaults : {
               sortable : true
            }
         });

         var oPermisiuneGrid = new Ext.ux.fatGrid({
            layout : 'fit',
            itemId : "permisiuneGrid",
            region : 'center',
            title : "Permisiune",
            clicksToEdit : 1,
            gridConfig : {
	            filterable : true,
	            resetFilterButton : true,
               sortField : "perm_cod",
               url : 'admin/permisiune/getData/',
               fields : ['perm_id', 'perm_cod', 'perm_nume', "perm_tip", "perm_activ"]
            },
            cm : oCmPermisiune,
            tbar : [{
               text : "Adauga",
               iconCls : 'icon-fugue-plus-circle',
               action : "add",
               handler : APP.permisiuneScreen.addPerm
            }]
         });

         //creare tab ce contine gridul
         var oNomTab = new Ext.Panel({
            title : 'Permisiuni',
            id : APP.permisiuneScreen.id,
            iconCls : 'icon-fugue-key',
            closable : true,
            layout : 'border',
            items : [oPermisiuneGrid]
         });

         //adaugare tab la tabpanel-ul principal
         APP.oCenterRegion.add(oNomTab);
         APP.oCenterRegion.doLayout();

      }
      //activare tab
      APP.oCenterRegion.setActiveTab(APP.permisiuneScreen.id);
   }
}); 
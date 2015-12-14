/*global APP,Ext,fnCompleteForm,console*/
Ext.ns('APP.securityAnswer');

Ext.apply(APP.securityAnswer, {

   id : 'idTabSecurityAsnwer',
   exec : function() {

      if (!Ext.getCmp(APP.securityAnswer.id)) {

         var oWind;
         //constructie formular
         var oFpAdd = new Ext.FormPanel({
            autoHeight : true,
            labelWidth : 150,
            frame : true,
            monitorValid : true,
            bodyStyle : {
               padding : "5px"
            },
            defaults : {
               xtype : "textfield",
               allowBlank : false,
               anchor : "95%"
            },
            layout : 'form',
            items : [{
               name : 'security_question_1',
               readOnly : true,
               fieldLabel : 'Intrebare',
               value : APP.security_question[0]
            }, {
               name : 'security_answer_1',
               fieldLabel : 'Raspuns securitate',
               minLenght : 4,
               maxLenght : 100,
               vtype : 'cleanTxt'
            }, {
               name : 'security_question_2',
               readOnly : true,
               value : APP.security_question[1],
               fieldLabel : 'Intrebare'
            }, {
               name : 'security_answer_2',
               fieldLabel : 'Raspuns securitate',
               minLenght : 4,
               xtype : "numberfield",
               allowDecimals : false,
               allowNegative : false,               
               maxLenght : 4
            }],
            buttons : [{
               text : 'Salvare',
               iconCls : 'icon-fugue-disk',
               formBind : true,
               handler : function() {
                  oFpAdd.getForm().submit({
                     url : "user/saveSecurityAnswer",
                     success : function(form, response) {
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
                  APP.events.throwInformation('Va rugam completati rapunsurile intrebarilor de securitate.Acestea vor fi  necesare pentru recuperarea parolei. ', true);
               }
            }]
         });

         //constructie fereastra
         oWind = new Ext.Window({
            title : "Va rugam completati datele de mai jos in vederea recuperii parolei mai tarziu daca va fi necesar.",
            width : 750,
            layout : 'fit',
            modal : true,
            closeAction : 'close',
            items : [oFpAdd]
         }).show();
      }
   }
});

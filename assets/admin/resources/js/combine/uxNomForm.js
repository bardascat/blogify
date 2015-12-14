/**
 * @package PortalGMS
 * @subpackage JSCOMPONENTS
 *
 * @author Dan Ungureanu
 * @copyright (c) 2009, Distrigaz Sud
 * @date
 * @version 1.0
 */
/*global Ext, FormColumn */

Ext.ns('NomForm');
NomForm.version = '1.0';

NomForm.Form = Ext.extend(Ext.form.FormPanel, {
    frame: true,
    labelWidth: 75,
    autoWidth: true,
    bodyStyle: 'padding:5px 5px 0;',
    // {{{
    initComponent: function() {
        
        Ext.apply(this, {
        
            items: [{
                layout: 'column',
                defaults: {
                    collumnWidth: 120,
                    layout: 'form',
                    border: true,
                    xtype: 'panel',
                    defaults: {
                        width: 115
                    },
                    bodyStyle: 'padding:0 9px 0 9px'
                },
                items: []
            }]
        });
        
        NomForm.Form.superclass.initComponent.apply(this, arguments);

        this.config = {
            url: this.url,
            method: 'post',
            params: this.params
        };
        // load configuration XHR
        Ext.Ajax.request(Ext.apply(this.config, {
            success: this.renderForm,
            scope: this
        }));
    }, // eo function initComponent
    // }}}
    
    // {{{
    renderForm: function(response) {
        var dtStart = new Date();
        var oForm = Ext.decode(response.responseText);
        if (oForm.error === true){
            try {
                this.fireEvent('serverError', this);
            }catch (e) {}
        }else{
            var oCol = {
                xtype: 'panel',
                items: []
            };
            Ext.each(oForm, function(col, index) {
                // atach listeners from server
                Ext.each(col.items, function(item, index) {
                    
                    if (item.listeners) {
                        for (var li in item.listeners) {
                            if (typeof(item.listeners[li]) === 'string') {
                                eval("this.listeners." + li + " = " + item.listeners[li]);
                            }
                        }
                    }
                });
                Ext.apply(oCol, col);
                this.items.items[0].add(oCol);

            }, this);
            
            // apply settings to layout
            this.doLayout();
        }
        
        this.hideMask();
        try {
            this.fireEvent('renderComplete', this);
        }catch (e) {}
        
        var dtEnd = new Date();
        color = ((dtEnd - dtStart)> 5000)?'red':'green';
        //Portal.debug.log('Durata incarcare formular: <span style="color:'+color+'; font-weight: bold">'+(dtEnd - dtStart)+'</span> milisecunde');

    }, // eo function renderForm
    // }}}
    
    showMask: function() {
        if (this.el) {
            this.mask = new Ext.LoadMask(Ext.getDom(this.id), Ext.apply({
                msg: 'Se incarca...',
                removeMask: true
            }));
            this.mask.show();
        }
    },
    hideMask: function() {
        this.mask.hide();
    },
    
    // {{{
    onRender: function() {
        // call parent
        NomForm.Form.superclass.onRender.apply(this, arguments);
        this.showMask();
    } // eo function onRender
    // }}}
});

Ext.reg('xNomForm', NomForm.Form);

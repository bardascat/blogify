/**
 * Helps creating easy configurable combo boxes
 * @version 1.1
 *
 *
 * 1.1
 * Changed   displayField, valueField
 *
 *
 * REMOTE
 *  {
 fieldLabel : "Moneda",
 xtype : "uxFCombo",
 editable : false,
 name : "nom_currency_id",
 easyConfig: {
 readerFields: [
 { name : "id",  mapping : "nom_currency_id"  },
 { name : "name", mapping : "nom_currency_value" }
 ]  ,
 proxyUrl: 'contract/getCurrency'
 }
 }
 *
 * LOCAL
 *        {
 fieldLabel : "Level",
 xtype : "uxFCombo",
 editable : false,
 name : "level",
 easyConfig: {
 mode : "local",
 localData: [
 ['user', 'user'],
 ['admin', 'admin']
 ]
 }
 }
 */

Ext.ns("Ext.ux.fastCombo");
Ext.ux.fastCombo.version = '1.1';

Ext.ux.fastCombo.Combo = Ext.extend(Ext.form.ComboBox, {
    typeAhead: false,
    editable: true,
    forceSelection : true,
    //lazyInit: false,
    triggerAction: 'all',
    emptyText:'Selectati...',
    selectOnFocus : true,

    initComponent: function() {

        this.easyConfig.baseParams = this.easyConfig.baseParams || { };
        this.easyConfig.readerRoot = this.easyConfig.readerRoot || "data";
        this.easyConfig.displayField = this.displayField || "name";
        this.easyConfig.valueField = this.valueField || "id";
        this.easyConfig.readerFields = this.easyConfig.readerFields || ["id", "name"];
        this.easyConfig.autoLoad = this.easyConfig.autoLoad || false;
        this.easyConfig.mode = this.easyConfig.mode || "remote";
        this.easyConfig.localData = this.easyConfig.localData || [];

        // if easy config supplied create store with httpProxy
        if (this.easyConfig.mode == "remote") {
            this.mode = 'remote';
            this.store = new Ext.data.Store({
                autoLoad : this.easyConfig.autoLoad,
                baseParams:  this.easyConfig.baseParams,
                proxy: new Ext.data.HttpProxy({
                    method: 'post',
                    url: this.easyConfig.proxyUrl
                }),
                reader: new Ext.data.JsonReader({
                    root: this.easyConfig.readerRoot  ,
					totalProperty: 'totalCount',
                    fields : this.easyConfig.readerFields
                })
            });
        }

        if (this.easyConfig.mode == "local") {
            this.mode = 'local';
            this.store = new Ext.data.SimpleStore({
                fields: this.easyConfig.readerFields,
                data: this.easyConfig.localData
            });
        }

        this.store.on("beforeload", function(store, params) {
            Ext.apply(store.baseParams, params.params);
        });

        this.displayField = this.easyConfig.displayField;
        this.valueField = this.easyConfig.valueField;
        this.minChars = this.easyConfig.minChars || "3";

        if (!this.hiddenName) {
            this.hiddenName = this.name;
        }

        if (!this.minListWidth) {
            this.minListWidth = this.width + 100;
        }

        Ext.ux.fastCombo.Combo.superclass.initComponent.apply(this, arguments);
    },

    render: function() {
        Ext.ux.fastCombo.Combo.superclass.render.apply(this, arguments);
    }
});

Ext.reg('uxFCombo', Ext.ux.fastCombo.Combo);
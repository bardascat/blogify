/*!
 * Ext JS Library 3.4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
/**
 * @class Ext.ux.grid.filter.StringFilter
 * @extends Ext.ux.grid.filter.Filter
 * Filter by a configurable Ext.form.TextField
 * <p><b><u>Example Usage:</u></b></p>
 * <pre><code>
 var filters = new Ext.ux.grid.GridFilters({
    ...
    filters: [{
        // required configs
        type: 'string',
        dataIndex: 'name',
        
        // optional configs
        value: 'foo',
        active: true, // default is false
        iconCls: 'ux-gridfilter-text-icon' // default
        // any Ext.form.TextField configs accepted
    }]
});
 * </code></pre> 
 {
	header: "Unitate angajat",
	dataIndex: "unit_nume",
	width: 250,
	filter: {
		type: "combo",
		baseParams: {
			unit_stare: 1
		},
		readerFields: [
			{
				name: "id",
				mapping: "unit_id"
			},
			{
				name: "name",
				mapping: "unit_nume"
			}
		],
		proxyUrl: "data/getUnit"
	}
},
 */
Ext.ux.grid.filter.ComboFilter = Ext.extend(Ext.ux.grid.filter.Filter, {

	/**
	 * @cfg {String} iconCls
	 * The iconCls to be applied to the menu item.
	 * Defaults to <tt>'ux-gridfilter-text-icon'</tt>.
	 */
	iconCls : 'ux-gridfilter-combo-icon',

	emptyText: 'Enter Filter Text...',
	selectOnFocus: true,
	width: 125,

	/**
	 * @private
	 * Template method that is to initialize the filter and install required menu items.
	 */
	init : function (config) {
		Ext.applyIf(config, {
			enableKeyEvents: true,
			iconCls: this.iconCls,
			listeners: {
				scope: this,
				keyup: this.onInputKeyUp
			}
		});

		this.inputItem = new  Ext.ux.fastCombo.Combo({
			allowBlank: true,
			forceSelection : true,
			listWidth: 300,
			getListParent: function() {
				return this.el.up('.x-menu');
			},
			iconCls: this.iconCls,
			easyConfig: {
				baseParams : this.baseParams,
				readerFields:this.readerFields,
				proxyUrl: this.proxyUrl
			},
			listeners : {
				scope: this,
				select : this.onSelectValue
			}
		});
		this.menu.add(this.inputItem);
		//this.updateTask = new Ext.util.DelayedTask(this.fireUpdate, this);
	},

	/**
	 * @private
	 * Template method that is to get and return the value of the filter.
	 * @return {String} The value of this filter
	 */
	getValue : function () {
		return this.inputItem.getRawValue();
	},

	/**
	 * @private
	 * Template method that is to set the value of the filter.
	 * @param {Object} value The value to set the filter
	 */
	setValue : function (value) {
		this.inputItem.setRawValue(value);
		this.fireEvent('update', this);
	},

	/**
	 * @private
	 * Template method that is to return <tt>true</tt> if the filter
	 * has enough configuration information to be activated.
	 * @return {Boolean}
	 */
	isActivatable : function () {
		return this.inputItem.getValue().length > 0;
	},

	/**
	 * @private
	 * Template method that is to get and return serialized filter data for
	 * transmission to the server.
	 * @return {Object/Array} An object or collection of objects containing
	 * key value pairs representing the current configuration of the filter.
	 */
	getSerialArgs : function () {
		return {type: 'combo', value: this.getValue()};
	},

	/**
	 * Template method that is to validate the provided Ext.data.Record
	 * against the filters configuration.
	 * @param {Ext.data.Record} record The record to validate
	 * @return {Boolean} true if the record is valid within the bounds
	 * of the filter, false otherwise.
	 */
	validateRecord : function (record) {
		var val = record.get(this.dataIndex);

		if(typeof val != 'string') {
			return (this.getValue().length === 0);
		}

		return val.toLowerCase().indexOf(this.getValue().toLowerCase()) > -1;
	},

	/**
	 * @private
	 * Handler method called when there is a keyup event on this.inputItem
	 */
	onSelectValue : function () {
		this.setActive(this.isActivatable());
		this.fireEvent('update', this);
	}
});

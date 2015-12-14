Ext.ns("Ext.ux.searchField");

Ext.ux.searchField = Ext.extend(Ext.form.CompositeField, {
    labelWidth: 120,	

    initComponent: function() {
        var composite = this;
        this.submitValue = false;
		this.data =  [
						  {name : "like", value: "Contine text"}  ,	
                        {name : "=",   value: "="},
                        {name : "<=",  value: "<="},
                        {name : ">=", value: ">="}                      										
                    ];					
		 

        this.items = [
            {
                width:          110,
                xtype:          "combo",
                mode:           "local",
                value:   "like", 
                submitValue : false,
                triggerAction:  "all",
                forceSelection: true,
				listWidth : 110,
                editable:       false,
                fieldLabel:     "Comparator",
                name:          this.name,
                hiddenName:    this.name,
                displayField:   "value",
                valueField:     "name",
                store:          new Ext.data.JsonStore({
                    fields : ["name", "value"],
                    data   :this.data
                })      
            },
            {
                xtype: "textfield",
                flex : 1,
                name :  this.name
            }
        ];

        Ext.ux.searchField.superclass.initComponent.apply(this, arguments);
    },

    render: function() {
        Ext.ux.searchField.superclass.render.apply(this, arguments);
    }  ,

    reset : function () {
        //Ext.ux.searchField.superclass.reset.call(this);
        this.items.items[0].setValue("like");
        this.items.items[1].setValue();       
        this.doLayout();
    }  ,

    getValue: function() {
        Ext.ux.searchField.superclass.getValue.call(this);       
        return Ext.encode([ this.items.items[0].getValue(),  this.items.items[1].getValue()]);
    }

});

Ext.reg("uxSField", Ext.ux.searchField);
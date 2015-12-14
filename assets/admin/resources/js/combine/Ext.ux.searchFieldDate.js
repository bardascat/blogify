Ext.ns("Ext.ux.searchFieldDate");

Ext.ux.searchFieldDate = Ext.extend(Ext.form.CompositeField, {
    labelWidth: 120,
    initComponent: function() {



        var composite = this;
        this.submitValue = false;
		this.data =  [                        
						{name : "between", value: "In perioada"} 						
                    ];		

        this.items = [  
			{xtype: 'displayfield', value: 'Mai mare : '},	
            {
                xtype: "datefield",
                flex : 1,
	            allowBlank:(this.allowBlank!=null ? this.allowBlank : true),
                name :  this.name,
	            format : (this.format ? this.format : "d.m.Y"), //sa fie configurabil formatul
                enableKeyEvents : true,
                ref  : this.name + "_data_start",
                listeners : {
                    blur : function(el, val) {
                        var date = this.getValue();
                        var end = composite.items.items[3];

                        if (!date) {
                            end.setMinValue();
                            return false;
                        }

                        if (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime())) {
                            end.setMinValue(date);
                            end.validate();
                            this.dateRangeMax = date;
                        }
                        return true;
                    }
                }
            } ,
			{xtype: 'displayfield', value: 'Mai mic : '},	
            {
                xtype: "datefield",
                flex : 1,            
                name :  this.name,
                format : (this.format ? this.format : "d.m.Y"),
                enableKeyEvents : true,
	            allowBlank:(this.allowBlank!=null ? this.allowBlank : true),
                listeners : {
                    blur : function(el, val) {
                        var date = this.getValue();
                        var start = composite.items.items[1];

                        if (!date) {
                            start.setMaxValue();
                            return false;
                        }

                        if (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime())) {
                            start.setMaxValue(date);
                            start.validate();
                            this.dateRangeMin = date;
                        }
                        return true;
                    }
                }
            }
        ];

        Ext.ux.searchFieldDate.superclass.initComponent.apply(this, arguments);
    },

    render: function() {
        Ext.ux.searchFieldDate.superclass.render.apply(this, arguments);		
    }  ,
   

    reset : function () {
        //Ext.ux.searchFieldDate.superclass.reset.call(this);
        this.items.items[1].reset();
        this.items.items[3].reset();        
        this.doLayout();
    }  ,

    getValue: function() {
        Ext.ux.searchFieldDate.superclass.getValue.call(this);

        var date_start = (this.items.items[1].getValue() != "") ? this.items.items[1].getValue().dateFormat((this.format ? this.format : "d.m.Y")) : "";
        var date_end = (this.items.items[3].getValue() != "") ? this.items.items[3].getValue().dateFormat((this.format ? this.format : "d.m.Y")) : "";
        return Ext.encode([date_start , date_end]);
    }

});

Ext.reg("uxSFielDdate", Ext.ux.searchFieldDate);
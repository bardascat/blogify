function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) {
            return true;
        }
    }
    return false;
}



function padStr(i) {
    return (i < 10) ? "0" + i : "" + i;
}

function valueOfArrayinArray(haystack1, haystack2) {
	var length1 = haystack1.length;

	for (var i = 0; i < length1; i++) {
		if (haystack2.indexOf(haystack1[i]) > -1) {
			return true;
		}
	}
	return false;
}

/** AUTOCOMPLETE  FORM V1.2
 * 1.2
 * sters verificare null
 * 1.1
 * added mode autocomplete for uxFCombo
 *
 *  **/


function fnCompleteForm(form, oData) {
    if ((typeof form != "object") || (typeof oData != "object")) {
        return;
    }


    for (datacol in oData) {




        if (form.getForm().findField(datacol)) {
            var oFormComponent = form.getForm().findField(datacol);
            if (oFormComponent.xtype) {
                switch (oFormComponent.xtype) {

                    case 'combo' :
                    {
                        var myArray = [];
                        myArray.id = oData[datacol];
                        myArray.name = oData[datacol + "_val"];   ////!!!!!!!!!!!!!
                        var rec = new Ext.data.Record(myArray);
                        oFormComponent.store.add(rec);
                        oFormComponent.setValue(oData[datacol]);
                        break;
                    }
                    case 'uxFCombo' :
                    {
                     
                        var myArray = [];
                        myArray.id = oData[datacol];
                        myArray.name = oData[datacol + "_val"];   ////!!!!!!!!!!!!!
                        var rec = new Ext.data.Record(myArray);
                        if (oFormComponent.mode == "remote") {
                            oFormComponent.store.add(rec);
                        }
                        oFormComponent.setValue(oData[datacol]);
                        break;
                    }

                    default :{
                           
                        oFormComponent.setValue(oData[datacol]);
                    }
                        break;
                }
            }
        }
    }
    return;
}

Ext.override(Ext.form.NumberField, {
    setValue : function(v) {
        v = typeof v == 'number' ? v : String(v).replace(this.decimalSeparator, ".");
        v = isNaN(v) ? '' : String(v).replace(".", this.decimalSeparator);
        return Ext.form.NumberField.superclass.setValue.call(this, v);
    },
    fixPrecision : function(value) {
        var nan = isNaN(value);
        if (!this.allowDecimals || this.decimalPrecision == -1 || nan || !value) {
            return nan ? '' : value;
        }
        return parseFloat(value).toFixed(this.decimalPrecision);
    }
});

/*
@catalin todo: acest override cauzeaza probleme la gridurile cu pluginu de expand
Ext.override(Ext.grid.GridView, {
	templates : {
		cell : new Ext.Template('<td class="x-grid3-col x-grid3-cell x-grid3-td-{id} {css}" style="{style}" tabIndex="0" {cellAttr}>', '<div class="x-grid3-cell-inner x-grid3-col-{id}" {attr}>{value}</div>', "</td>")
	}
});

*/


// OVERRIDE NECESARY FOR Ext.ux.searchField TO WORK
Ext.override(Ext.form.FormPanel, {
    getItemsValues : function(asString) {
        var o = {};
        this.items.each(function(item) {
            if (item.isFormField) {
                o[item.name || item.id] = item.getValue();
            }
        });
        return o;
    }  ,
    resetItemsValues : function(asString) {
        var o = {};
        this.items.each(function(item) {
            if (item.isFormField) {
                o[item.name || item.id] = item.reset();
            }
        });
        return o;
    }
});

var cp = new Ext.state.CookieProvider({
            expires: new Date(new Date().getTime() + (1000 * 60 * 60 * 24 * 365))  //30 days
        });
Ext.state.Manager.setProvider(cp);

Ext.override(Ext.grid.GroupingView, {
	disableGroupingByClick: false,

	processEvent: function(name, e){
		Ext.grid.GroupingView.superclass.processEvent.call(this, name, e);
		var hd = e.getTarget('.x-grid-group-hd', this.mainBody);
		if(hd){
			// group value is at the end of the string
			var field = this.getGroupField(),
				prefix = this.getPrefix(field),
				groupValue = hd.id.substring(prefix.length),
				emptyRe = new RegExp('gp-' + Ext.escapeRe(field) + '--hd');

			// remove trailing '-hd'
			groupValue = groupValue.substr(0, groupValue.length - 3);

			// also need to check for empty groups
			if(groupValue || emptyRe.test(hd.id)){
				this.grid.fireEvent('group' + name, this.grid, field, groupValue, e);
			}
			if(name == 'mousedown' && e.button == 0 && !this.disableGroupingByClick){
				this.toggleGroup(hd.parentNode);
			}
		}

	}
});

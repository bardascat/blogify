/*global Ext */

Ext.ns('Ext.ux.button.EasyGridToolbarButton');
Ext.ux.button.EasyGridToolbarButton.version = '1.1';

/**
 *
 * @class Ext.ux.button.EasyGridToolbarButton
 * @extends Ext.Button
 * Exemplu:
 * <pre><code>
    var oBtn = {
        xtype: 'xEasyGridTbarButton',
        text: 'Nume buton',
        {@link #trackGridSelection}: false,
        {@link #gridSingleSelect}: false,
        {@link #canActivate}: function(oSelected){
            return ((oSelected.id>5)?true:false);
        },
        {@link #canActivateExtended}: function(aSelected){
            return false;
        },
        handler: function(){
            // this.oGrid = obiectul de tip uxDanutGrid de care apartine
            // his.oGrid.getSelectionModel() = sel model grid
            // etc.
        }
    };
 * </code></pre>
 */
Ext.ux.button.EasyGridToolbarButton = Ext.extend(Ext.Button, {

    /**
     * Functie care se apeleaza in momentul in care se schimba selectia in grid. Daca metoda returneaza
     * <code>false</code> butonul devine <code>disabled</code><br>
     * <br>Necesita <code>{@link #trackGridSelection}: true</code>
     * @method canActivate
     * @param {Ext.data.Record} oSelected
     * @return {Boolean}
     */

    /**
     * Functie care se apeleaza in momentul in care se schimba selectia in grid. Daca metoda returneaza
     * <code>false</code> butonul devine <code>disabled</code><br>
     * <br>Necesita <code>{@link #trackGridSelection}: true</code>
     * @method canActivateExtended
     * @param {Array} aSelected Array de obiecte {@link Ext.data.Record}
     * @return {Boolean}
     */

    /**
     * @cfg {Boolean} trackGridSelection
     * If set to true it enables or disables itself depending on grid selection change
     */
    trackGridSelection: true,
	oGridConfig : {},
    /**
     * @cfg {Boolean} gridSingleSelect
     * Forces button to enable only on single selection, depends on  trackGridSelection to be set to true
     */
    gridSingleSelect: true,
    
    initComponent: function(){

        this.oGrid = this.findParentByType('uxFatGrid') || {};
	    if (this.oGridConfig) {
		    this.oGrid = this.oGridConfig
	    }
        
        Ext.ux.button.EasyGridToolbarButton.superclass.initComponent.apply(this, arguments);
        
    },
    
    render: function(){
        
        Ext.ux.button.EasyGridToolbarButton.superclass.render.apply(this, arguments);
        
        // add selection tracking capabilities
        if(this.trackGridSelection === true){
            
            var oBtn = this;
            oBtn.disable();
            try{
                this.oGrid.getSelectionModel().on('selectionchange', function() {

                    if (this.hasSelection()) {
                        if (this.selections.length > 1) {
                            oBtn.setDisabled((oBtn.gridSingleSelect === true)?true:false);
                            if(Ext.isFunction(oBtn.canActivateExtended)){
                                //console.log('REZULTAT FUNCTIE: ', oBtn.canActivateExtended.call(oBtn, this.getSelections()));
                                if(oBtn.canActivateExtended.call(oBtn, this.getSelections()) === false){
                                    //console.log('FACE DISABLE');
                                    oBtn.disable();
                                }else{
                                    //console.log('FACE ENABLE');
                                    oBtn.enable();
                                }
                            }
                        }else if(this.selections.length == 1){
                            oBtn.enable();
                            
                            // tratare conditii activare custom
                            if(Ext.isFunction(oBtn.canActivate)){
                                if(oBtn.canActivate.call(oBtn, [this.getSelected()]) === false){
                                    oBtn.disable();
                                }else{
                                    oBtn.enable();
                                }
                            }
                            
                            if(Ext.isFunction(oBtn.canActivateExtended)){
                                if(oBtn.canActivateExtended.call(oBtn, [this.getSelected()]) === false){
                                    oBtn.disable();
                                }else{
                                    oBtn.enable();
                                }
                            }
                        }else{
                            oBtn.disable();
                        }
                    }else {
                        oBtn.disable();
                    }
                    try{
                        oBtn.onGridSelectionChange(oBtn, this);
                    }catch(e){}
                });
            }catch(e){}
        }
    }
});

Ext.reg('xEasyBridTbarButton', Ext.ux.button.EasyGridToolbarButton);
Ext.reg('xEasyGridTbarButton', Ext.ux.button.EasyGridToolbarButton);
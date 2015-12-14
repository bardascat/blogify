Ext.ux.ThemeCombo = Ext.extend(Ext.form.ComboBox, {
    // configurables     
	themeAccessText : "Tema: Negru - font mare",
    themeGrayText: 'Tema: Gri - font mic' ,
    themeBlueText: 'Tema: Bleu' ,
    themeDcsText: 'Tema: Negru - font mic',
    themeVampireText: 'Vampire Theme',
    themeHumanText: 'Tema: Mov',
    themeSlateText: 'Tema: Bleumarin',
    //themeAzenisText: 'Azenis Theme',
    themeTpText: 'Tema: Gri - font mare'
    ,themeVar:'theme'
    //,themeTpText:'Tp'
    ,selectThemeText: 'Select Theme'
    ,lazyRender:true
    ,lazyInit:true
    ,cssPath:'resources/ext-3.4/resources/css/' // mind the trailing slash

    // {{{
    ,initComponent:function() {

        Ext.apply(this, {
            store: new Ext.data.SimpleStore({
                fields: ['themeFile', {name:'themeName', type:'string'}]
                ,data: [
				    ['xtheme-access.css', this.themeAccessText],
                   // ['xtheme-azenis.css', this.themeAzenisText],
                    ['xtheme-gray.css', this.themeGrayText],
                    ['xtheme-blue.css', this.themeBlueText],
                    ['xtheme-dcs.css', this.themeDcsText],
                    ['xtheme-human.css', this.themeHumanText],
                    ['xtheme-slate.css', this.themeSlateText]/*,
                    ['xtheme-tp.css', this.themeTpText]*/
                ]
            })
            ,valueField: 'themeFile'
            ,displayField: 'themeName'
            ,triggerAction:'all'
            ,mode: 'local'
            ,forceSelection:true
            ,editable:false
            ,fieldLabel: this.selectThemeText
        }); // end of apply

        this.store.sort('themeName');

        // call parent
        Ext.ux.ThemeCombo.superclass.initComponent.apply(this, arguments);

        if (false !== this.stateful && Ext.state.Manager.getProvider()) {
            this.setValue(Ext.state.Manager.get(this.themeVar) || 'xtheme-blue.css');
        }
        else {
            this.setValue('xtheme-blue.css');
        }

    } // end of function initComponent
    // }}}
    // {{{
    ,setValue:function(val) {
        Ext.ux.ThemeCombo.superclass.setValue.apply(this, arguments);
        // set theme
        Ext.util.CSS.swapStyleSheet(this.themeVar, this.cssPath + val);

        if (false !== this.stateful && Ext.state.Manager.getProvider()) {
            Ext.state.Manager.set(this.themeVar, val);
        }
    } // eo function setValue
    // }}}

}); // end of extend

// register xtype
Ext.reg('themecombo', Ext.ux.ThemeCombo);
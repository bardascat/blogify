//1.1
//+added viewconfig ( scroll maintain position)
//1.0


Ext.ns('Ext.ux.fastEditorGrid');

Ext.ux.fastEditorGrid = Ext.extend(Ext.grid.EditorGridPanel, {
    stripeRows: true,
    loadMask: true,
    batchSave: false,
    viewConfig : {
        forceFit: true,
        onLoad: Ext.emptyFn,
        listeners: {
            beforerefresh: function(v) {
                v.scrollTop = v.scroller.dom.scrollTop;
                v.scrollHeight = v.scroller.dom.scrollHeight;
            },
            refresh: function(v) {
                v.scroller.dom.scrollTop = v.scrollTop + (v.scrollTop == 0 ? 0 : v.scroller.dom.scrollHeight - v.scrollHeight);
            }
        }
    },
    initComponent: function() {
        var oFatGrid = this;
        //GRID
        this.gridConfig.pageSize = this.gridConfig.pageSize || 50;
        this.plugins = this.plugins || [];
        this.gridConfig.searchable = this.gridConfig.searchable || false;
        this.gridConfig.filterable = this.gridConfig.filterable || false;
        
        this.gridConfig.hasBottomBar = (this.gridConfig.hasBottomBar == false) ? false : true ;

        //STORE
        this.gridConfig.autoSave = (this.gridConfig.autoSave == false) ? false : true; 
        this.gridConfig.fields = this.gridConfig.fields || [];

	    this.gridConfig.group = (this.gridConfig.group === false) ? false : true;
	    this.gridConfig.groupField = this.gridConfig.groupField || false;

        this.gridConfig.batch =  (this.gridConfig.batch == false) ? false : true;  
        this.gridConfig.idProperty = this.gridConfig.idProperty || this.gridConfig.fields[0];
        this.gridConfig.totalCount = this.gridConfig.totalCount || "totalCount";
        this.gridConfig.root = this.gridConfig.root || "data";
        this.gridConfig.autoLoad = (this.gridConfig.autoLoad == false) ? false : true;
        this.gridConfig.sortField = this.gridConfig.sortField || this.gridConfig.idProperty;
        this.gridConfig.sortDir = this.gridConfig.sortDir || "ASC";
        this.gridConfig.url = this.gridConfig.url || "";
        this.gridConfig.storeBaseParams = this.gridConfig.storeBaseParams || {};

        //VIEW
        if (this.gridConfig.viewConfig) {
	        this.viewConfig = this.gridConfig.viewConfig;
        }


        Ext.apply(this.gridConfig.storeBaseParams, {
            start: 0,
            limit:  this.gridConfig.pageSize
        });


        // default selection model, can be overridden
        if (typeof(this.sm) !== 'object') {
            this.sm = new Ext.grid.CheckboxSelectionModel({
                singleSelect: true
            });
        }

 //Filters
        if (this.gridConfig.filterable) {
            this.plugins.push(new Ext.ux.grid.GridFilters({
                encode: true,
                local: false,
                menuFilterText: 'Filtre'
            }));
            if (this.gridConfig.resetFilterButton) {
                this.tbar.push(['-', {
                        text: 'Sterge filtre',
                        tooltip: 'Sterge filtre',
                        name: "filtre",
                        iconCls: 'icon-fugueov-funnel-minus',
                        handler: function() {
                            oFatGrid.filters.clearFilters();
                        }
                    }]);
            }
        }
        
        /* if (this.gridConfig.filters == 'simple') {
         if (this.gridConfig.searchable) {
         this.plugins.push(new Ext.ux.grid.Search({
         iconCls:'icon-fugue-magnifier',
         position: 'top',
         minChars:2,
         autoFocus:true
         }));
         }
         }  */

        this.store = new Ext.data.GroupingStore({

	        remoteSort: this.gridConfig.group,
	        groupOnSort: true,
	        groupField: this.gridConfig.groupField,

            batch : this.gridConfig.batch,
            autoSave: this.gridConfig.autoSave,
            autoLoad:   this.gridConfig.autoLoad,
            listeners : {
                beforeload: function(store, params) {
                    Ext.apply(store.baseParams, params.params);
                }
            },
            baseParams:  this.gridConfig.storeBaseParams,
            sortInfo: {
                field:  this.gridConfig.sortField ,
                direction:  this.gridConfig.sortDir
            },
            proxy: new Ext.data.HttpProxy({
                url: this.gridConfig.url
            }),
            writer: new Ext.data.JsonWriter(),
            reader: new Ext.data.JsonReader({
                //batch : this.gridConfig.batch ,
                successProperty:'success',
                root:   this.gridConfig.root,
                totalProperty:   this.gridConfig.totalCount ,
                idProperty :  this.gridConfig.idProperty  ,
                fields :    this.gridConfig.fields
            })
        });

        this.oPagingToolbar = new Ext.PagingToolbar({
            pageSize: this.gridConfig.pageSize,
            store: this.store,
            displayInfo: true ,
            beforePageText: 'Pagina&nbsp;',
            afterPageText: 'din {0}',
            firstText: 'Prima pagina',
            lastText: 'Ultima pagina',
            nextText: 'Pagina urmatoare',
            prevText: 'Pagina precedenta',
            displayMsg: 'Inregistrari {0} - {1} din {2}',
            emptyMsg: "No records"
        });


        if (this.gridConfig.hasBottomBar !== false) {
            this.bbar = this.oPagingToolbar;
        }


        Ext.ux.fastEditorGrid.superclass.initComponent.apply(this, arguments);
    } ,
    onRender: function() {
        // call parent
        Ext.ux.fastEditorGrid.superclass.onRender.apply(this, arguments);
    }
});

Ext.reg('uxFEditorGrid', Ext.ux.fastEditorGrid);

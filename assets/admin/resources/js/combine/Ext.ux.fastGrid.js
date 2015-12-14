/*global Ext*/
/*jslint browser: true, sloppy: true, vars: true, white: true */
Ext.ns('Ext.ux.fastGrid');

Ext.ux.fastGrid = Ext.extend(Ext.grid.GridPanel, {
    stripeRows: true,
    loadMask: true,
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
		var oFastGrid = this;
        //GRID
        this.gridConfig.pageSize = this.gridConfig.pageSize || 50;
        this.plugins = this.plugins || [];
		this.tbar = this.tbar || [];
        this.gridConfig.filterable = this.gridConfig.filterable || false;
		this.gridConfig.resetFilterButton = (this.gridConfig.resetFilterButton === false) ? false : true;
		this.gridConfig.exportable = this.gridConfig.exportable || false;
		this.gridConfig.exportButton = (this.gridConfig.exportButton === false) ? false : true;
    	this.gridConfig.hasBottomBar = (this.gridConfig.hasBottomBar === false) ? false : true;

        //STORE
        this.gridConfig.fields = this.gridConfig.fields || [];

	    this.gridConfig.group = (this.gridConfig.group === false) ? false : true;
	    this.gridConfig.groupField = this.gridConfig.groupField || false;

        this.gridConfig.idProperty = this.gridConfig.idProperty || this.gridConfig.fields[0];
        this.gridConfig.totalCount = this.gridConfig.totalCount || "totalCount";
        this.gridConfig.root = this.gridConfig.root || "data";
        this.gridConfig.autoLoad = (this.gridConfig.autoLoad === false) ? false : true;
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




		if (this.gridConfig.group !== true) {
			this.store = new Ext.data.Store({
				remoteSort: true,
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
					url: this.gridConfig.url + "/"
				}),
				reader: new Ext.data.JsonReader({
					root:   this.gridConfig.root,
					totalProperty:   this.gridConfig.totalCount ,
					idProperty :  this.gridConfig.idProperty  ,
					fields :    this.gridConfig.fields
				})
			});
		}
	    else {
			this.store = new Ext.data.GroupingStore({
				remoteSort: this.gridConfig.group,

				groupOnSort: true,
				groupField: this.gridConfig.groupField,

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
					url: this.gridConfig.url + "/"
				}),
				reader: new Ext.data.JsonReader({
					root:   this.gridConfig.root,
					totalProperty:   this.gridConfig.totalCount ,
					idProperty :  this.gridConfig.idProperty  ,
					fields :    this.gridConfig.fields
				})
			});


		}







        this.oPagingToolbar = new Ext.PagingToolbar({
            pageSize: this.gridConfig.pageSize,
            store: this.store,
			plugins : this.plugins,
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


        Ext.ux.fastGrid.superclass.initComponent.apply(this, arguments);
    } ,
    onRender: function() {
        // call parent
        Ext.ux.fastGrid.superclass.onRender.apply(this, arguments);
    }
});

Ext.reg('uxFGrid', Ext.ux.fastGrid);

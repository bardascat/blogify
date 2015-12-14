/*global Ext*/
/*jslint browser: true, sloppy: true, vars: true, white: true */
Ext.ns('Ext.ux.fatGrid');

Ext.ux.fatGrid = Ext.extend(Ext.grid.GridPanel, {
    stripeRows: true,
    loadMask: false,
    loadMask: {
        msg: "Loading"
    },
    viewConfig: {
        forceFit: true,
        onLoad: Ext.emptyFn,
        listeners: {
            beforerefresh: function(v) {
                v.scrollTop = v.scroller.dom.scrollTop;
                v.scrollHeight = v.scroller.dom.scrollHeight;
            },
            refresh: function(v) {
                v.scroller.dom.scrollTop = v.scrollTop + (v.scrollTop === 0 ? 0 : v.scroller.dom.scrollHeight - v.scrollHeight);
            }
        }
    },
    initComponent: function() {
        var oFatGrid = this;
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
            limit: this.gridConfig.pageSize
        });

        // default selection model, can be overridden
        if (typeof (this.sm) !== 'object') {
            this.sm = new Ext.grid.CheckboxSelectionModel({
                singleSelect: true
            });
        }
        this.store = new Ext.data.GroupingStore({
            remoteSort: this.gridConfig.group,
            groupOnSort: false,
            groupField: this.gridConfig.groupField,
            autoLoad: this.gridConfig.autoLoad,
            listeners: {
                beforeload: function(store, params) {
                    Ext.apply(store.baseParams, params.params);
                }

            },
            baseParams: this.gridConfig.storeBaseParams,
            sortInfo: {
                field: this.gridConfig.sortField,
                direction: this.gridConfig.sortDir
            },
            proxy: new Ext.data.HttpProxy({
                url: this.gridConfig.url + "/"
            }),
            reader: new Ext.data.JsonReader({
                root: this.gridConfig.root,
                totalProperty: this.gridConfig.totalCount,
                idProperty: this.gridConfig.idProperty,
                fields: this.gridConfig.fields
            })
        });
        this.store.on('load', function(store, records, options) {

        });

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

        this.oPagingToolbar = new Ext.PagingToolbar({
            pageSize: this.gridConfig.pageSize,
            store: this.store,
            displayInfo: true,
            beforePageText: 'Pagina&nbsp;',
            afterPageText: 'din {0}',
            firstText: 'Prima pagină',
            lastText: 'Ultima pagină',
            nextText: 'Pagina următoare',
            prevText: 'Pagina precedentă',
            displayMsg: 'Înregistrări {0} - {1} din {2}',
            emptyMsg: "Nu există înregistrări",
            plugins: new Ext.ux.grid.GridFilters({
                encode: true,
                local: false,
                menuFilterText: 'Filtre'
            })
        });

        if (this.gridConfig.hasBottomBar !== false) {
            this.bbar = this.oPagingToolbar;
        }

        //Export
        if (this.gridConfig.exportable) {
            var aColumns = [];
            Ext.each(oFatGrid.cm.config, function(col, index) {
                aColumns.push({
                    header: col.header,
                    dataIndex: col.dataIndex,
                    type: col.filter.type
                });
            });

            this.exportData = function() {
                var filterData = oFatGrid.filters.getFilterData();
                Ext.each(filterData, function(filt, index) {
                    filt.value = filt.data.value;
                    filt.comparison = filt.data.comparison;
                    filt.type = filt.data.type;
                });

                //create dummy form
                if (!Ext.fly("table-export")) {
                    var frm = document.createElement("form");
                    frm.id = "table-export";
                    frm.name = "table-export";
                    frm.className = "x-hidden";
                    document.body.appendChild(frm);
                }
                //get attachment
                Ext.Ajax.request({
                    url: oFatGrid.gridConfig.url,
                    method: "POST",
                    form: "table-export",
                    isUpload: true,
                    params: {
                        filter: Ext.encode(filterData),
                        columns: Ext.encode(aColumns),
                        isExport: true,
                        params: Ext.encode(oFatGrid.store.baseParams)
                    }
                });
            };

            if (this.gridConfig.exportButton) {
                this.tbar.push(['-', {
                        text: 'Export',
                        tooltip: 'Export',
                        iconCls: 'icon-fugue-table-excel',
                        handler: function() {
                            oFatGrid.exportData();
                        }

                    }]);
            }
        }

        Ext.ux.fatGrid.superclass.initComponent.apply(this, arguments);
    },
    onRender: function() {
        // call parent
        Ext.ux.fatGrid.superclass.onRender.apply(this, arguments);
    }

});

Ext.reg('uxFatGrid', Ext.ux.fatGrid);

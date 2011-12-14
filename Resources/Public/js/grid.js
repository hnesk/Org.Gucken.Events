// example grid pre-configured class
Event.Grid = Ext.extend(Ext.grid.GridPanel, {
    initComponent:function() {
     // create the data store
        var directFn = function(callback) {Org_Gucken_Events_Controller_ExtController.list(callback);};
        directFn.directCfg = {method: {len: 0}};

        var extDirectStore = new Ext.data.DirectStore({
            directFn: directFn,
            autoLoad: true,
            autoDestroy: true,
            root: 'data',
            fields:[
                {name:'name'},
                {name:'telephone'},
                {name:'address'}
            ]
        });

        this.store = extDirectStore;

        var config = {
            store:this.store,
            columns:[{
                    header:"Name",
                    width:60,
                    sortable:true,
                    dataIndex:'name'
                },{
                    header:"Telefon",
                    width:120,
                    sortable:true,
                    dataIndex:'telephone'
                },{
                    header:"Adresse",
                    width:120,
                    sortable:true,
                    dataIndex:'address',
                    renderer:this.renderAddress.createDelegate(this)
                }

                //{header:"Last Updated", width:20, sortable:true, renderer:Ext.util.Format.dateRenderer('d.m.Y H:i'),dataIndex:'lastChange'}
            ],
            height: 300,
            viewConfig: {
                forceFit: true
            }
        };

        this.on('render', function() {this.body.mask('Loading');});
        this.store.on('load', function() {this.body.unmask();}, this);

        // apply config
        Ext.apply(this, Ext.apply(this.initialConfig, config));

        // call parent
        Event.Grid.superclass.initComponent.apply(this, arguments);
    },
    
    renderAddress:function(value, cell, record) {        
        return value.streetAddress + ', ' + value.postalCode + ' ' + value.addressLocality;
    }



});

// application main entry point
Ext.onReady(function() {
    
    Ext.QuickTips.init();
    var eventGrid = new Event.Grid();
    eventGrid.render('eventgrid');

});
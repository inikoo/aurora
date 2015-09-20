var columns = [
 {
    name: "id",
    label: "ID",
    editable: false,
     sortType: "toggle",
    {if $sort_key=='id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.IntegerCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view('customers/' + this.model.get("store_key") + '/' + this.$el.html())
            }
        },
        className: "link"
       
}),
   
}, {
    name: "store_key",
    label: "",
    editable: false,
    renderable: false,
    cell: "string",
     sortType: "toggle",

}, {
    name: "name",
    label: "Name",
     sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
            "dblclick": "enterEditMode"
        }
    })
}, {
    name: "location",
    label: "{t}Location{/t}",
    sortType: "toggle",
            {if $sort_key=='location'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    editable: false,
    
    cell: "html"
}, {
    name: "activity",
    label: "Status",
    editable: false,
    sortType: "toggle",
         {if $sort_key=='activity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

    cell: "string"
}, {
    name: "contact_since",
    label: "Since",
    editable: false,
     defautOrder:1,
    sortType: "toggle",
            {if $sort_key=='contact_since'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
 
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell

}, {
    name: "last_order",
    label: "Last Order",
     defautOrder:1,
    editable: false,
    sortType: "toggle",
    {if $sort_key=='last_order'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell

}, {
    name: "invoices",
    label: "Invoices",
    editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
}, {
    name: "logins",
    label: "{t}Logins{/t}",
    editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='logins'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
}, {
    name: "failed_logins",
    label: "{t}Fail Logins{/t}",
    editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='logins'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
}, {
    name: "requests",
    label: "{t}Pageviews{/t}",
    editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='logins'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
}]

function change_table_view(view){

    $('.view').removeClass('selected');
    $('#view_'+view).addClass('selected');
    
    
grid.columns.findWhere({ name: 'invoices'} ).set("renderable", false)
grid.columns.findWhere({ name: 'last_order'} ).set("renderable", false)
grid.columns.findWhere({ name: 'contact_since'} ).set("renderable", false)



 grid.columns.findWhere({ name: 'failed_logins'} ).set("renderable", false)
grid.columns.findWhere({ name: 'logins'} ).set("renderable", false)
grid.columns.findWhere({ name: 'requests'} ).set("renderable", false)

   
  //  var idCol = grid.columns.where({ name: "logins" });
//grid.hideColumn(idCol);

      
    
    if(view=='overview'){
   
    grid.columns.findWhere({ name: 'invoices'} ).set("renderable", true)
grid.columns.findWhere({ name: 'last_order'} ).set("renderable", true)
grid.columns.findWhere({ name: 'contact_since'} ).set("renderable", true)

    }else if(view=='weblog'){
    grid.columns.findWhere({ name: 'logins'} ).set("renderable", true)
 grid.columns.findWhere({ name: 'failed_logins'} ).set("renderable", true)
grid.columns.findWhere({ name: 'requests'} ).set("renderable", true)

    }
    

}
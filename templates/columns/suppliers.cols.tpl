var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

},{
    name: "formatted_id",
    label: "{t}ID{/t}",
    editable: false,
     sortType: "toggle",
    {if $sort_key=='id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    cell: Backgrid.StringCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view('supplier{if $data.object=='list'}/list/{$data.key}{/if}/' + this.$el.html())
            }
        },
        className: "link"
       
})
   
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
    name: "last_purchase_order",
    label: "{t}Last PO{/t}",
     defautOrder:1,
    editable: false,
    sortType: "toggle",
    {if $sort_key=='last_invoice'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell

}, {
    name: "products",
    label: "{t}Products{/t}",
    editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
}, {
    name: "pending_po",
    label: "{t}Pending PO{/t}",
    editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='logins'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
}, {
    name: "company_name",
    label: "{t}Company{/t}",
    editable: true,
    sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
            "dblclick": "enterEditMode"
        }
    })
}, {
    name: "contact_name",
    label: "{t}Main contact{/t}",
    editable: true,
    sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
            "dblclick": "enterEditMode"
        }
    })
}, {
    name: "email",
    label: "{t}Email{/t}",
    editable: true,
    sortType: "toggle",
    cell: Backgrid.EmailCell.extend({
        events: {
            "dblclick": "enterEditMode"
        }
    })
}, {
    name: "mobile",
    label: "{t}Mobile{/t}",
    editable: true,
    sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
            "dblclick": "enterEditMode"
        }
    })
}, {
    name: "telephone",
    label: "{t}Telephone{/t}",
    editable: true,
    sortType: "toggle",
    cell: Backgrid.StringCell.extend({
        events: {
            "dblclick": "enterEditMode"
        }
    })
}

]

function change_table_view(view,save_state){

console.log(view)

    $('.view').removeClass('selected');
    $('#view_'+view).addClass('selected');
    
//    grid.columns.findWhere({ name: 'formatted_id'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'name'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'location'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'last_purchase_order'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'products'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'pending_po'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'company_name'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'contact_name'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'email'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'mobile'} ).set("renderable", false)
    grid.columns.findWhere({ name: 'telephone'} ).set("renderable", false)
 
    
    if(view=='overview'){
        grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'location'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'last_purchase_order'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'products'} ).set("renderable", true)
       
    }else if(view=='weblog'){
        grid.columns.findWhere({ name: 'logins'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'failed_logins'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'requests'} ).set("renderable", true)
    }else if(view=='contact'){
        grid.columns.findWhere({ name: 'company_name'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'contact_name'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'email'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'mobile'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'telephone'} ).set("renderable", true)
    }else if(view=='products'){
        grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'products'} ).set("renderable", true)
  
    }else if(view=='orders'){
        grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'last_purchase_order'} ).set("renderable", true)
        grid.columns.findWhere({ name: 'pending_po'} ).set("renderable", true)
 
    }
    
    if(save_state){
     var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view
   
    $.getJSON(request, function(data) {});
    }

}
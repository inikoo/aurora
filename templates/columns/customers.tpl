 [
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
    name: "contact_since",
    label: "Since",
    editable: false,
     defautOrder:1,
    sortType: "toggle",
            {if $sort_key=='contact_since'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
 
    cell: "html"
}, {
    name: "activity",
    label: "Status",
    editable: false,
    sortType: "toggle",
         {if $sort_key=='activity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

    cell: "string"
}, {
    name: "last_order",
    label: "Last Order",
     defautOrder:1,
    editable: false,
    sortType: "toggle",
             {if $sort_key=='last_order'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


    cell: "string"
}, {
    name: "invoices",
    label: "Invoices",
    editable: false,
    cell: "integer",
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

    headerCell: integerHeaderCell
}]

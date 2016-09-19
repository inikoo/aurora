var columns = [
  {
    name: "id",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

}, {
    name: "warehouse_area_key",
    label: "",
    editable: false,
     renderable: false,
    cell: "string",

}, {
    name: "flag",
    label: "{t}Flag{/t}",
    sortType: "toggle",
    {if $sort_key=='flag'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    editable: false,
    
    cell: "html"
},{
    name: "code",
    label: "{t}Code{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                 change_view('locations/{$data['parent_key']}/' + this.model.get("id"))
            }
        },
        className: "link"
       
})
   
},{
    name: "area",
    label: "{t}Area{/t}",
    editable: false,
     sortType: "toggle",
    
    cell: Backgrid.StringCell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                 change_view('warehouse/{$data['parent_key']}/area/' + this.model.get("warehouse_area_key"))
            }
        },
        className: "link"
       
})
   
}, {
    name: "used_for",
    label: "{t}Used for{/t}",
    sortType: "toggle",
    {if $sort_key=='location'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    
    editable: false,
    
    cell: "string"
}, {
    name: "max_weight",
    label: "{t}Max weight{/t}",
     defautOrder:1,
    editable: false,
    sortType: "toggle",
    {if $sort_key=='max_weight'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell

}, {
    name: "max_volume",
    label: "{t}Max volume{/t}",
    editable: false,
   
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='max_volume'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),

    headerCell: integerHeaderCell
}, {
    name: "parts",
    label: "{t}Parts{/t}",
    editable: false,
    defautOrder:1,
    sortType: "toggle",
    {if $sort_key=='parts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
    headerCell: integerHeaderCell
},
]

function change_table_view(view,save_state){}
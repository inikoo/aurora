var columns = [
 {
    name: "id",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
}, {
    name: "formated_id",
    label: "{t}Id{/t}",
    editable: false,
     cell: Backgrid.Cell.extend({
        orderSeparator: '',
        events: {
            "click": function() {
                change_view('{$data['object']}/{$data['key']}/payment/' + this.model.get("id") )
            }
        },
        className: "link",
       
        
    })
},{
    name: "reference",
    label:"{t}Reference{/t}",
    editable: false,
    sortType: "toggle",
    cell: Backgrid.StringCell.extend({  }),
},{
    name: "type",
    label:"{t}Type{/t}",
    editable: false,
    sortType: "toggle",
    cell: Backgrid.StringCell.extend({  }),
},  {
    name: "amount",
    label: "{t}Amount{/t}",
    defautOrder:1,
    editable: false,
    sortType: "toggle",
    {if $sort_key=='transactions'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell
},  {
    name: "date",
    label: "{t}Date{/t}",
    defautOrder:1,
    editable: false,
    sortType: "toggle",
    {if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
    cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell
},{
    name: "status",
    label:"{t}Status{/t}",
    editable: false,
    sortType: "toggle",
    cell: Backgrid.StringCell.extend({  }),
}, 


]
function change_table_view(view,save_state){}

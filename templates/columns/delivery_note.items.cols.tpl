var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "product_pid",
label: "",
editable: false,
cell: "string",
renderable: false


},{
name: "code",
label: "{t}Product{/t}",
editable: false,
cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view("order/{$data['key']}/item/"+this.model.get("id"))
}
},
className: "link"
}),
}, {
name: "quantity",
label: "{t}Ordered{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},{
name: "description",
label: "{t}Parts{/t}",
editable: false,
cell: "html"

}, {
name: "picked",
label: "{t}Picked{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='picked'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "packed",
label: "{t}Packed{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='packed'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "notes",
label: "{t}Notes{/t}",
editable: false,
cell: "html"
}
]


function change_table_view(view, save_state) {}

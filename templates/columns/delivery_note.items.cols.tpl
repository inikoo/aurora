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
name: "reference",
label: "{t}Reference{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
events: {
"click": function() {
change_view("order/{$data['key']}/item/"+this.model.get("id"))
}
},
className: "link"
}),
},
{
name: "description",
label: "{t}Description{/t}",
editable: false,
cell: "html"

},

{
name: "location",
label: "{t}Location{/t}",
editable: false,
cell: "html"

},


{
name: "quantity",
label: "{t}Qty{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "picked",
label: "{t}Picked{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='picked'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "packed",
label: "{t}Packed{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='packed'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}
]


function change_table_view(view, save_state) {}

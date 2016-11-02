var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},
{
name: "reference",
label: "{t}Supplier's part code{/t}",
editable: false,
cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view(this.model.get("parent_type")+"/"+this.model.get("parent_key")+"/part/hk/"+this.model.get("supplier_part_historic_key"))


}
},
className: "link width_250"
}),
}, {
name: "quantity",
label: "{t}Cartons{/t}",
defautOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ } ),
}
]


function change_table_view(view, save_state) {


}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",
sortType: "toggle",

}, {
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",


cell: Backgrid.StringCell.extend({
events: {
"dblclick": "enterEditMode",
"click": function() {
change_view('customers/list/' + this.model.get("id"))
}
},
className: "link"
})
}, {
name: "type",
label: "{t}Type{/t}",
sortType: "toggle",
cell: "string",
}, {
name: "creation_date",
label: "{t}Created{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='creation_date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "items",
label: "{t}Customers{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}]

function change_table_view(view,save_state){}


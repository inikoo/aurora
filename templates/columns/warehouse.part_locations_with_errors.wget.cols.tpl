var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

}, {
name: "stock_status",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},{
name: "reference",
label: "{t}Part{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({})

},
{
name: "location",
label: "{t}Location{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({})


},
{
name: "can_pick",
label: "{t}Can pick{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({})


},
{
name: "quantity",
label: "{t}Quantity{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

]

function change_table_view(view,save_state){

}
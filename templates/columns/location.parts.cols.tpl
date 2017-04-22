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
label: "{t}Reference{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({ })

},
{
name: "sko_description",
label: "{t}SKO description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.StringCell.extend({


})

},
{
name: "can_pick",
label: "{t}Picking here{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.StringCell.extend({


})

},
{
name: "quantity",
label: "{t}SKOs{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "sko_cost",
label: "{t}Value/SKO{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sko_cost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "stock_value",
label: "{t}Stock value{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock_value'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},


]

function change_table_view(view,save_state){

}
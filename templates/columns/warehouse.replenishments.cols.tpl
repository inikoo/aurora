var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "part",
label: "{t}Part{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

}, {
name: "other_locations_stock",
label: "{t}Other locations stock{/t}",

sortable: false,
editable: false,

cell: "html"
},

{
name: "location",
label: "{t}Location{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


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
name: "recommended_quantity",
label: "{t}Recommended SKOs quantity{/t}",
sortable: false,

editable: false,

cell: Backgrid.HtmlCell.extend({ } ),
}

]

function change_table_view(view,save_state){}
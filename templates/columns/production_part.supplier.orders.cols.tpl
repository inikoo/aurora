var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}, {
name: "public_id",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})
},

{
name: "date",
label: "{t}Date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "state",
label: "{t}State{/t}",
editable: false,
sortType: "toggle",
cell: "html"
}
, {
name: "qty_units",
label: "{t}Units{/t}",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
 {
name: "qty_skos",
label: "{t}SKOs{/t}",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}
]

function change_table_view(view,save_state){}

var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}, {
name: "number",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='number'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })
}, {
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
label: "{t}Status{/t}",
editable: false,
sortType: "toggle",
cell: "html"
},
{
name: "amount",
label: "{t}Amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},{
name: "delivery_notes",
label: "{t}Delivery notes{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='delivery_notes'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "boxes",
label: "{t}Boxes{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='boxes'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "notes",
label: "",
editable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]

function change_table_view(view,save_state){

}

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
name: "parent",
label: "{t}Supplier/Agent{/t}",
renderable:{if ($data['object']=='supplier' or $data['object']=='agent'  )}false{else}true{/if},
sortType: "toggle",
editable: false,
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
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "state",
label: "{t}State{/t}",
editable: false,
sortType: "toggle",
cell: "html"
}, {
name: "total_amount",
label: "{t}Total{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}]

function change_table_view(view,save_state){}

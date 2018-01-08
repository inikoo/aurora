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
cell: Backgrid.HtmlCell.extend({ })
}, {
name: "date",
label: "{t}Date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "supplier",
label: "{t}Supplier{/t}",
renderable:{if $data['parent']=='supplier'}false{else}true{/if},
sortType: "toggle",
editable: false,
cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view('supplier/' + this.model.get("supplier_key")  )
}
},
className: "link",
})
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

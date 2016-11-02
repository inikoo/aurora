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
cell: Backgrid.StringCell.extend({
events: {
"click": function() {
{if $tab=='suppliers.deliveries'}
    change_view("delivery/" + this.model.get("id")  )

{else}
    change_view("{$data['object']}/{$data['key']}/delivery/" + this.model.get("id")  )

{/if}
}
},
className: "link",
})
}, {
name: "date",
label: "{t}Date{/t}",
editable: false,
defautOrder:1,
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
defautOrder:1,
sortType: "toggle",
{if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}]

function change_table_view(view,save_state){}

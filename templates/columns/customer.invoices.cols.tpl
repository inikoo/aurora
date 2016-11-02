var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}, {
name: "store_key",
label: "",
editable: false,
renderable: false,
cell: "string"
},{
name: "customer_key",
label: "",
editable: false,
renderable: false,
cell: "string"
}, {
name: "number",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='number'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view('invoices/'+this.model.get("store_key")+'/' + this.model.get("id")  )
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
name: "customer",
label: "{t}Customer{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.StringCell.extend({
orderSeparator: '',
events: {
"click": function() {
change_view('customer/' + this.model.get("customer_key")  )
}
},
className: "link",
})
}, {
name: "type",
label: "{t}Type{/t}",
editable: false,
sortType: "toggle",
cell: "html"
}, {
name: "method",
label: "{t}Payment Method{/t}",
editable: false,
sortType: "toggle",
cell: "html"
}, {
name: "state",
label: "{t}Payment State{/t}",
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

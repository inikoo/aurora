var columns = [
{
name: "store_key",
label: "",
editable: false,
renderable: false,
cell: "string"
}, {
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.StringCell.extend({ className: ""} )
}, {
name: "name",
label:"{t}Store Name{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "orders",
label:"{t}Orders{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.Cell.extend({
events: {
"click": function() {
if(this.model.get('store_key')==''){
change_view('orders/all' )
}else{
change_view('orders/' + this.model.get("store_key")+'/dashboard' )
}
}
},
className: "link aright",


}),
headerCell: integerHeaderCell

}, {
name: "invoices",
label:"{t}Invoices{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.Cell.extend({
events: {
"click": function() {
if(this.model.get('store_key')==''){
change_view('invoices/all' )
}else{
change_view('invoices/' + this.model.get("store_key") )
}
}
},
className: "link aright",


}),
headerCell: integerHeaderCell

}, {
name: "delivery_notes",
label:"{t}Delivery Notes{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.Cell.extend({
events: {
"click": function() {
if(this.model.get('store_key')==''){
change_view('delivery_notes/all' )
}else{
change_view('delivery_notes/' + this.model.get("store_key") )
}
}
},
className: "link aright",


}),
headerCell: integerHeaderCell

}, {
name: "payments",
label:"{t}Payments{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}

]
function change_table_view(view,save_state){}

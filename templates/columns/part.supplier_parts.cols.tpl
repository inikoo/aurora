var columns = [{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

}, {
name: "status",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

}, {
name: "reference",
label: "{t}Reference{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({
})

},

{
name: "supplier_code",
label: "{t}Supplier{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({

})

},


{
name: "cost",
label: "{t}Cost{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "delivered_cost",
label: "{t}Delivered Cost{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "packing",
label: "{t}Packing{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}

]


function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


grid.columns.findWhere({ name: 'cost'} ).set("renderable", false)
grid.columns.findWhere({ name: 'delivered_cost'} ).set("renderable", false)
grid.columns.findWhere({ name: 'packing'} ).set("renderable", false)

if(view=='overview'){
grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
grid.columns.findWhere({ name: 'cost'} ).set("renderable", true)
grid.columns.findWhere({ name: 'delivered_cost'} ).set("renderable", true)
grid.columns.findWhere({ name: 'packing'} ).set("renderable", true)

}
if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
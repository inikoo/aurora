var columns = [{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},

{
name: "principal",
label: "",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_20 align_center"
})

},

{
name: "status",
label: "",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"
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
name: "reference",
label: "{t}Supplier's code{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({
})

},

{
name: "description",
label: "{t}Supplier's unit description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

},

{
name: "cost",
label: "{t}Unit cost{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "delivered_cost",
label: "{t}Delivered unit cost{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "sko_per_carton",
title: '{t}Units (SKOs) per carton{/t}',
label:'',
html_label: '<i class="fal  fa-pallet"></i>',
editable: false,
renderable:false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sko_per_carton'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell
},


{
name: "operations",
label: "",
sortable: false,
editable: false,
cell: Backgrid.HtmlCell.extend({


className: "width_100 aright padding_right_5"

}),
headerCell: rightHeaderHtmlCell

}
]


function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


grid.columns.findWhere({ name: 'cost'} ).set("renderable", false)
grid.columns.findWhere({ name: 'delivered_cost'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sko_per_carton'} ).set("renderable", false)

if(view=='overview'){
grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
grid.columns.findWhere({ name: 'cost'} ).set("renderable", true)
grid.columns.findWhere({ name: 'delivered_cost'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sko_per_carton'} ).set("renderable", true)

}
if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
var columns = [{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "data",
label: "",
editable: false,
renderable: false,
cell: Backgrid.HtmlCell.extend({
})

},
{
name: "supplier_code",
label: "{t}Supplier{/t}",
editable: false,
renderable: {if $data['object']=='supplier' }false{else}true{/if},
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({
})

}, {
name: "status",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"
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
name: "barcode",
label: "{t}Unit barcode{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})

},
{
name: "barcode_sko",
label: "{t}SKO barcode{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})

},
{
name: "barcode_carton",
label: "{t}Carton barcode{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})

},


{
name: "weight_sko",
label: "{t}SKO weight{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='weight_sko'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "cbm",
label: "{t}Carton CBM{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='cbm'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
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

},


{
name: "sales",
label: "{t}Revenue{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sales_1yb",
label: "{t}1YB{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "dispatched",
label: "{t}Dispatched{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sold'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "dispatched_1yb",
label: "{t}1YB{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sold'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "stock",
label: "{t}Stock{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "stock_status",
label: "",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock_status'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"

} ),
headerCell: integerHeaderCell
},

{
name: "dispatched_per_week",
label: "{t}Dispatched/w{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "available_forecast",
label: "{t}Available forecast{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},


{
name: "next_deliveries",
label: "{t}Next deliveries{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='next_deliveries'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({

} ),

},


]


function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');

close_columns_period_options()
$('#columns_period').addClass('hide');

grid.columns.findWhere({ name: 'description'} ).set("renderable", false)
grid.columns.findWhere({ name: 'stock'} ).set("renderable", false)
grid.columns.findWhere({ name: 'cost'} ).set("renderable", false)
grid.columns.findWhere({ name: 'delivered_cost'} ).set("renderable", false)
grid.columns.findWhere({ name: 'packing'} ).set("renderable", false)
grid.columns.findWhere({ name: 'barcode'} ).set("renderable", false)
grid.columns.findWhere({ name: 'barcode_sko'} ).set("renderable", false)
grid.columns.findWhere({ name: 'barcode_carton'} ).set("renderable", false)

grid.columns.findWhere({ name: 'weight_sko'} ).set("renderable", false)
grid.columns.findWhere({ name: 'cbm'} ).set("renderable", false)

grid.columns.findWhere({ name: 'sales'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sales_1yb'} ).set("renderable", false)
grid.columns.findWhere({ name: 'dispatched'} ).set("renderable", false)
grid.columns.findWhere({ name: 'dispatched_1yb'} ).set("renderable", false)
grid.columns.findWhere({ name: 'stock_status'} ).set("renderable", false)

grid.columns.findWhere({ name: 'dispatched_per_week'} ).set("renderable", false)
grid.columns.findWhere({ name: 'available_forecast'} ).set("renderable", false)
grid.columns.findWhere({ name: 'next_deliveries'} ).set("renderable", false)


if(view=='overview'){
grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
grid.columns.findWhere({ name: 'cost'} ).set("renderable", true)
grid.columns.findWhere({ name: 'delivered_cost'} ).set("renderable", true)
grid.columns.findWhere({ name: 'packing'} ).set("renderable", true)
grid.columns.findWhere({ name: 'description'} ).set("renderable", true)

}else if(view=='barcodes'){

grid.columns.findWhere({ name: 'description'} ).set("renderable", true)
grid.columns.findWhere({ name: 'barcode'} ).set("renderable", true)
grid.columns.findWhere({ name: 'barcode_sko'} ).set("renderable", true)
grid.columns.findWhere({ name: 'barcode_carton'} ).set("renderable", true)

grid.columns.findWhere({ name: 'weight_sko'} ).set("renderable", true)
grid.columns.findWhere({ name: 'cbm'} ).set("renderable", true)

}else if(view=='parts'){
$('#columns_period').removeClass('hide');

grid.columns.findWhere({ name: 'description'} ).set("renderable", true)

grid.columns.findWhere({ name: 'sales'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_1yb'} ).set("renderable", true)
grid.columns.findWhere({ name: 'dispatched'} ).set("renderable", true)
grid.columns.findWhere({ name: 'dispatched_1yb'} ).set("renderable", true)


}else if(view=='reorder'){
grid.columns.findWhere({ name: 'status'} ).set("renderable", true)

grid.columns.findWhere({ name: 'packing'} ).set("renderable", true)
grid.columns.findWhere({ name: 'stock'} ).set("renderable", true)
grid.columns.findWhere({ name: 'stock_status'} ).set("renderable", true)


grid.columns.findWhere({ name: 'dispatched_per_week'} ).set("renderable", true)
grid.columns.findWhere({ name: 'available_forecast'} ).set("renderable", true)
grid.columns.findWhere({ name: 'next_deliveries'} ).set("renderable", true)


}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
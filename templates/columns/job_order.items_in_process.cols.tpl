var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},
{
name: "reference",
label: "{t}S. Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
})
},
{
name: "image",
label: "{t}Image{/t}",
editable: false,
sortable: false,

cell: "html"

},
{
name: "description_units",
label: "{t}Unit description{/t}",
editable: false,
cell: "html"

},
{
name: "description_skos",
label: "{t}SKO description{/t}",
editable: false,
cell: "html"

},{
name: "description_cartons",
label: "{t}Carton description{/t}",
editable: false,
cell: "html"

},

{
name: "other_deliveries_units",
label: "{t}Other deliveries{/t}",
editable: false,
cell: "html"

},
{
name: "other_deliveries_skos",
label: "{t}Other deliveries{/t}",
editable: false,
cell: "html"

},
{
name: "other_deliveries_cartons",
label: "{t}Other deliveries{/t}",
editable: false,
cell: "html"

},
{
name: "subtotals",
label: "{t}Subtotals{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='subtotals'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: ""} ),

},
{
name: "quantity_units",
label: "{t}Units{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "quantity_skos",
label: "{t}SKOs{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "quantity_cartons",
label: "{t}Cartons{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]


function change_table_view(view, save_state) {

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


grid.columns.findWhere({ name: 'description_units'} ).set("renderable", false)
grid.columns.findWhere({ name: 'description_skos'} ).set("renderable", false)
grid.columns.findWhere({ name: 'description_cartons'} ).set("renderable", false)



grid.columns.findWhere({ name: 'other_deliveries_units'} ).set("renderable", false)
grid.columns.findWhere({ name: 'other_deliveries_skos'} ).set("renderable", false)
grid.columns.findWhere({ name: 'other_deliveries_cartons'} ).set("renderable", false)


grid.columns.findWhere({ name: 'quantity_units'} ).set("renderable", false)
grid.columns.findWhere({ name: 'quantity_skos'} ).set("renderable", false)
grid.columns.findWhere({ name: 'quantity_cartons'} ).set("renderable", false)


$('.add_item_form').addClass('hide')
$('#new_item_unit').addClass('hide')
$('#new_item_sko').addClass('hide')
$('#new_item_carton').addClass('hide')


if(view=='cartons'){
grid.columns.findWhere({ name: 'description_cartons'} ).set("renderable", true)
grid.columns.findWhere({ name: 'quantity_cartons'} ).set("renderable", true)
grid.columns.findWhere({ name: 'other_deliveries_cartons'} ).set("renderable", true)

$('#new_item_carton').removeClass('hide')

$('#upload_order_items').removeClass('hide')
$('#upload_order_items_upload').data('field','Purchase Order Cartons')


}else if(view=='skos'){
grid.columns.findWhere({ name: 'quantity_skos'} ).set("renderable", true)
grid.columns.findWhere({ name: 'description_skos'} ).set("renderable", true)
grid.columns.findWhere({ name: 'other_deliveries_skos'} ).set("renderable", true)
$('#new_item_sko').removeClass('hide')
$('#upload_order_items').removeClass('hide')
$('#upload_order_items_upload').data('field','Purchase Order SKOs')


}else if(view=='units'){
grid.columns.findWhere({ name: 'description_units'} ).set("renderable", true)
grid.columns.findWhere({ name: 'quantity_units'} ).set("renderable", true)
grid.columns.findWhere({ name: 'other_deliveries_units'} ).set("renderable", true)
$('#new_item_unit').removeClass('hide')
$('#upload_order_items').removeClass('hide')
$('#upload_order_items_upload').data('field','Purchase Order Units')

}



if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}

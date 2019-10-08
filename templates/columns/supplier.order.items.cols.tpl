var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},

{
name: "checkbox",
renderable:false,
label: "",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: "width_20"} ),
},



{
name: "reference",
label: "{t}S. Code{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='reference'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
sortType: "toggle",
{if $sort_key=='description_units'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "html"

},
{
name: "description_skos",
label: "{t}SKO description{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='description_skos'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "html"

},{
name: "description_cartons",
label: "{t}Carton description{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='description_cartons'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "html"

},


{
name: "items_qty",
label: "{t}Qty{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='items_qty'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: ""} ),

},
{
name: "weight",
label: "{t}Weight{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='weight'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: ""} ),

},
{
name: "cbm",
label: "{t}CBM{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='cbm'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: ""} ),

},
{
name: "amount",
label: "{t}Amount{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: ""} ),

},
{
name: "state",
label: "{t}State{/t}",
editable: false,
cell: "html"

},

{
name: "operations",
renderable:false,
label: "",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: "width_20"} ),

},
{
name: "quantity_units",
label: "{t}Units{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity_units'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "quantity_skos",
label: "{t}SKOs{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity_skos'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "quantity_cartons",
label: "{t}Cartons{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity_cartons'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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



//grid.columns.findWhere({ name: 'other_deliveries_units'} ).set("renderable", false)
//grid.columns.findWhere({ name: 'other_deliveries_skos'} ).set("renderable", false)
//grid.columns.findWhere({ name: 'other_deliveries_cartons'} ).set("renderable", false)


grid.columns.findWhere({ name: 'quantity_units'} ).set("renderable", false)
grid.columns.findWhere({ name: 'quantity_skos'} ).set("renderable", false)
grid.columns.findWhere({ name: 'quantity_cartons'} ).set("renderable", false)

if(view=='overview'){

grid.columns.findWhere({ name: 'description_units'} ).set("renderable", true)


}else if(view=='cartons'){
grid.columns.findWhere({ name: 'description_cartons'} ).set("renderable", true)
grid.columns.findWhere({ name: 'quantity_cartons'} ).set("renderable", true)
//grid.columns.findWhere({ name: 'other_deliveries_cartons'} ).set("renderable", true)

}else if(view=='skos'){
grid.columns.findWhere({ name: 'quantity_skos'} ).set("renderable", true)
grid.columns.findWhere({ name: 'description_skos'} ).set("renderable", true)
//grid.columns.findWhere({ name: 'other_deliveries_skos'} ).set("renderable", true)


}else if(view=='units'){
grid.columns.findWhere({ name: 'description_units'} ).set("renderable", true)

grid.columns.findWhere({ name: 'quantity_units'} ).set("renderable", true)
//grid.columns.findWhere({ name: 'other_deliveries_units'} ).set("renderable", true)

}



if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}

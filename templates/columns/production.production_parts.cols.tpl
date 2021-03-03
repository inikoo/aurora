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


}, {
name: "description_and_packing",
label: "{t}Unit description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

},
{
name: "description",
label: "{t}Unit description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

},




{
name: "components",
label: "{t}Materials{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='components'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "tasks",
label: "{t}Tasks{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='tasks'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
name: "next_deliveries",
label: "{t}Job orders{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='next_deliveries'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({  className: "aright"  } ),

headerCell: integerHeaderCell
},

{
name: "units_per_sko",
label: "{t}Units/SKO{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='units_per_sko'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sko_per_carton",
label: "{t}SKO/Carton{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sko_per_carton'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "units_per_batch",
label: "{t}Units/Batch{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='units_per_batch'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

]





function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');

grid.columns.findWhere({ name: 'description_and_packing'} ).set("renderable", false)

grid.columns.findWhere({ name: 'description'} ).set("renderable", false)
grid.columns.findWhere({ name: 'components'} ).set("renderable", false)
grid.columns.findWhere({ name: 'tasks'} ).set("renderable", false)
grid.columns.findWhere({ name: 'stock'} ).set("renderable", false)
grid.columns.findWhere({ name: 'next_deliveries'} ).set("renderable", false)


grid.columns.findWhere({ name: 'units_per_sko'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sko_per_carton'} ).set("renderable", false)
grid.columns.findWhere({ name: 'units_per_batch'} ).set("renderable", false)




if(view=='overview'){

grid.columns.findWhere({ name: 'description_and_packing'} ).set("renderable", true)
grid.columns.findWhere({ name: 'components'} ).set("renderable", true)
grid.columns.findWhere({ name: 'tasks'} ).set("renderable", true)
grid.columns.findWhere({ name: 'stock'} ).set("renderable", true)
grid.columns.findWhere({ name: 'next_deliveries'} ).set("renderable", true)

}else if(view=='packing'){
grid.columns.findWhere({ name: 'description'} ).set("renderable", true)

grid.columns.findWhere({ name: 'units_per_sko'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sko_per_carton'} ).set("renderable", true)
grid.columns.findWhere({ name: 'units_per_batch'} ).set("renderable", true)
}else if(view=='costing'){

}else if(view=='produced'){


}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
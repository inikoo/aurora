{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2017 at 22:39:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}
var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "reference",
label: "{t}Reference{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "link"
}),
},
{
name: "description",
label: "{t}SKO Description{/t}",
editable: false,
cell: "html"

},



{
name: "overview_required",
label: "{t}Required{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='overview_required'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "overview_problem",
label: "{t}Problem{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='overview_problem'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "overview_picked",
label: "{t}Picked{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='overview_picked'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "overview_packed",
label: "{t}Packed{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='overview_packed'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "overview_restock",
label: "{t}Returned{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='overview_restock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},






]


function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


grid.columns.findWhere({ name: 'reference'} ).set("renderable", false)
grid.columns.findWhere({ name: 'description'} ).set("renderable", false)




grid.columns.findWhere({ name: 'overview_required'} ).set("renderable", false)
grid.columns.findWhere({ name: 'overview_problem'} ).set("renderable", false)
grid.columns.findWhere({ name: 'overview_picked'} ).set("renderable", false)
grid.columns.findWhere({ name: 'overview_packed'} ).set("renderable", false)
grid.columns.findWhere({ name: 'overview_restock'} ).set("renderable", false)


if(view=='overview'){
grid.columns.findWhere({ name: 'reference'} ).set("renderable", true)
grid.columns.findWhere({ name: 'description'} ).set("renderable", true)

grid.columns.findWhere({ name: 'overview_required'} ).set("renderable", true)
grid.columns.findWhere({ name: 'overview_problem'} ).set("renderable", true)
grid.columns.findWhere({ name: 'overview_picked'} ).set("renderable", true)
grid.columns.findWhere({ name: 'overview_packed'} ).set("renderable", true)
grid.columns.findWhere({ name: 'overview_restock'} ).set("renderable", true)
}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}


}

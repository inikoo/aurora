{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 August 2018 at 12:25:11 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
*/*}


var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}, {
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
editable: false,
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: "",})
},

{
name: "new_prospects",
label: "{t}Prospects created{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='new_prospects'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "calls",
label: "{t}Calls{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='calls'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "emails_sent",
label: "{t}Emails sent{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='emails_sent'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "open",
label: "{t}Open{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='open'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "click",
label: "{t}Clicked{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='click'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "register",
label: "{t}Registered{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='register'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "invoiced",
label: "{t}Invoiced{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoiced'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "open_percentage",
label: "{t}Open %{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='open_rate'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "click_percentage",
label: "{t}Click %{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='click_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "register_percentage",
label: "{t}Register %{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='register_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "invoiced_percentage",
label: "{t}Invoiced %{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoiced_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

]

function change_table_view(view,save_state){


$('.view.tab').removeClass('selected');
$('#view_'+view).addClass('selected');




grid.columns.findWhere({ name: 'open'} ).set("renderable", false)
grid.columns.findWhere({ name: 'click'} ).set("renderable", false)
grid.columns.findWhere({ name: 'register'} ).set("renderable", false)
grid.columns.findWhere({ name: 'invoiced'} ).set("renderable", false)

grid.columns.findWhere({ name: 'open_percentage'} ).set("renderable", false)
grid.columns.findWhere({ name: 'click_percentage'} ).set("renderable", false)
grid.columns.findWhere({ name: 'register_percentage'} ).set("renderable", false)
grid.columns.findWhere({ name: 'invoiced_percentage'} ).set("renderable", false)


if(view=='overview'){

grid.columns.findWhere({ name: 'open'} ).set("renderable", true)
grid.columns.findWhere({ name: 'click'} ).set("renderable", true)
grid.columns.findWhere({ name: 'register'} ).set("renderable", true)
grid.columns.findWhere({ name: 'invoiced'} ).set("renderable", true)

}else if(view=='percentages'){

grid.columns.findWhere({ name: 'open_percentage'} ).set("renderable", true)
grid.columns.findWhere({ name: 'click_percentage'} ).set("renderable", true)
grid.columns.findWhere({ name: 'register_percentage'} ).set("renderable", true)
grid.columns.findWhere({ name: 'invoiced_percentage'} ).set("renderable", true)

}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}


}

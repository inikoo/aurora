{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 January 2019 at 13:45:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
*/*}
var columns = [{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

}, {
name: "reference",
label: "{t}Reference{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({

})




}, {
name: "description",
label: "{t}Unit description{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})

},
{
name: "stock",
label: "{t}Stock{/t} (SKOs)",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}
,{
name: "available_to_make_up",
label: "{t}Can make up{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='available_to_make_up'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}
, {
name: "cost_unit",
label: "{t}Cost{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},{
name: "qty_skos",
label: "{t}SKOs{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "qty",
label: "{t}Units{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}

]


function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


if(view=='overview'){

}else if(view=='parts'){

}else if(view=='reorder'){

}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
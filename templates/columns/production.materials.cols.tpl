var columns = [{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

}, {
name: "object",
renderable: true,
sortable: false,
label: "",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center",

})

},{
name: "reference",
label: "{t}Reference{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({

})

}
, {
name: "description",
label: "{t}Unit description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

},
{
name: "stock_units",
label: "{t}Stock{/t} ({t}Units{/t})",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock_units'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}
, {
name: "production_links",
label: "{t}Used in{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
{if $sort_key=='production_links'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

headerCell: integerHeaderCell

}

]


function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');



if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
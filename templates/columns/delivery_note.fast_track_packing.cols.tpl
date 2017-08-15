{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:11 August 2017 at 14:21:01 CEST, Tranava, Slovakia
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
name: "location",
label: "{t}Location{/t}",
editable: false,
cell: "html"

},




{
name: "quantity",
label: "{t}Qty{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "picked_offline_input",
label: "{t}Picked{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='picked_offline_input'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
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

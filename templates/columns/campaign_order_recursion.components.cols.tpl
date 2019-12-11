{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 November 2017 at 15:11:20 GMT+7, Bangkok, Thailand
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},



{
name: "status",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"
})

},



{
name: "term_allowances",
label: "{t}Description{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

}),

},










{
name: "from",
label: "{t}From{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='from'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},

{
name: "orders",
label: "{t}Orders{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='orders'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "customers",
label: "{t}Customers{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='customers'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "edit",
label: "",
editable: false,
sortable:false,

cell: Backgrid.HtmlCell.extend({
className: "width_40 align_center"
})

},
]

function change_table_view(view,save_state){




if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
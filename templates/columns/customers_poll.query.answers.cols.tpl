{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2018 at 11:32:14 GMT+8 Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

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
name: "formatted_id",
label: "{t}Customer ID{/t}",
sortType: "toggle",
editable: false,
sortType: "toggle",
{if $sort_key=='customer_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
}
)
},



{
name: "customer",
label: "{t}Customer name{/t}",
sortType: "toggle",
editable: false,
sortType: "toggle",
{if $sort_key=='customer'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
}
)
},



{
name: "date",
label: "{t}Date{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell,


},

{
name: "answer",
label: "{t}Answer{/t}",
sortType: "toggle",
editable: false,
sortType: "toggle",
{if $sort_key=='answer'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
}
)
}
]

function change_table_view(view,save_state){


}
{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 February 2018 at 19:27:40 GMT+8, Kuala Lumpur, Malaysia
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
name: "code",
label: "{t}Code{/t}",
sortType: "toggle",
editable: false,
sortType: "toggle",
{if $sort_key=='code'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
}
)
},


{
name: "label",
label: "{t}Public label{/t}",
sortType: "toggle",
editable: false,
sortType: "toggle",
{if $sort_key=='label'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
}
)
},



{
name: "last_chose",
label: "{t}Last chose{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='last_chose'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell,


},


{
name: "customers",
label: "{t}Customers{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='customers'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell,


},

{
name: "customers_percentage",
label: "%",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='customers_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright width_200"} ),
headerCell: integerHeaderCell,


}
]

function change_table_view(view,save_state){


}
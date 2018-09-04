{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 September 2018 at 23:15:29 GMT+9, Tokyo, Japan
 Copyright (c) 2018, Inikoo

 Version 3
*/*}
var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "code",
label: "{t}Code{/t}",
sortType: "toggle",
defaultOrder:1,

{if $sort_key=='},{'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},{
name: "description",
label: "{t}Description{/t}",
sortType: "toggle",

editable: false,
cell: "html"

},{
name: "discounts",
label: "{t}Discounts{/t}",
defaultOrder:1,
editable: false,
sortable: false,

sortType: "toggle",
{if $sort_key=='discounts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "quantity",
label: "{t}Quantity{/t}",
sortable: false,

defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "net",
label: "{t}Net{/t}",
defaultOrder:1,
editable: false,
sortable: false,

sortType: "toggle",
{if $sort_key=='net'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell
}
]


function change_table_view(view, save_state) {}

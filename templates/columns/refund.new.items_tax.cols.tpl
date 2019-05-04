{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  03-05-2019 10:07:25 GMT+2 , Tranava, Slovakia
 Copyright (c) 2018, Inikoo

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
name: "product_pid",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},{
name: "description",
label: "{t}Description{/t}",
editable: false,
cell: "html"

},
{
name: "quantity",
label: "{t}Quantity{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "tax",
label: "{t}Tax{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='tax'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell
},
{
name: "refund_quantity",
label: "",
defaultOrder:1,
sortable: false,

editable: false,
sortType: "toggle",
{if $sort_key=='refund_quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell
}, {
name: "refund_tax",
label: "{t}Refund tax{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='refund_tax'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell
}
]


function change_table_view(view, save_state) {}

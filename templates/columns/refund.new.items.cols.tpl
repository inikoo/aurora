{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  6 November 2017 at 12:18:44 GMT+8, Legian Bali, Indonesia
 Copyright (c) 2017, Inikoo

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
name: "net",
label: "{t}Net{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='net'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
name: "refund_net",
label: "{t}Refund net{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='refund_net'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell
},
{
name: "feedback",
label: "{t}Feedback{/t}",
sortable:false,
editable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright min_width_120"} ),
headerCell: integerHeaderCell

},
]


function change_table_view(view, save_state) {}

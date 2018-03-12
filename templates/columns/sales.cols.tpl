{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 March 2018 at 14:18:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
*/*}

var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},

{
name: "store",
label: "{t}Store{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})
},
{
name: "customers",
label: "{t}Customers{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='customers'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "refunds",
label: "{t}Refunds{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refunds'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "refund_amount",
label: "{t}Refund amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refund_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "invoices",
label: "{t}Invoices{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "revenue",
label: "{t}Revenue{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='revenue'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "profit",
label: "{t}Profit{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='profit'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}


]

function change_table_view(view,save_state){}

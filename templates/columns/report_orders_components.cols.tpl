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
name: "items_cost",
label: "{t}Items cost{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items_cost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "shipping_cost",
label: "{t}Shipping cost{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='shipping_cost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "replacement_cost",
label: "{t}Rpl cost{/t}",
title: "{t}Replacement cost{/t}",

editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='replacement_cost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "items_net",
label: "{t}Items{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items_net'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "shipping_net",
label: "{t}Shipping{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='shipping_net'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "charges_net",
label: "{t}Charges{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='charges_net'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "refund_amount",
label: "{t}Refunds{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refund_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "total_net",
label: "{t}Total net{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_net'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "tax",
label: "{t}Tax{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='tax'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
},
{
name: "margin",
label: "{t}Margin{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='margin'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}


]

function change_table_view(view,save_state){}

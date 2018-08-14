{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 August 2018 at 12:25:11 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2018, Inikoo

 Version 3
*/*}


var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}, {
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
editable: false,
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: "",})
},

{
name: "customers",
label: "{t}Customers{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='customers'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "refunds",
label: "{t}Refunds{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='deliveries'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "invoices",
label: "{t}Invoices{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "sales",
label: "{t}Sales{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

]

function change_table_view(view,save_state){}

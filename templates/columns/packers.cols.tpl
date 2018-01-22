{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 222 January 2018 at 22:46:56 GMT+8, Kuala Lumpur, Malaysia
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
cell: Backgrid.HtmlCell.extend({
className: "",})
},

{
name: "deliveries",
label: "{t}Deliveries{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='deliveries'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "packed",
label: "{t}SKOs{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='packed'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "dp",
label: "{t}Deliveries x SKOs{/t} (DS)",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='dp'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "dp_percentage",
label: "DS %",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='dp_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "hrs",
label: "{t}Clocked hours{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='hrs'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "dp_per_hour",
label: "DS/h",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='dp_per_hour'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
]

function change_table_view(view,save_state){}

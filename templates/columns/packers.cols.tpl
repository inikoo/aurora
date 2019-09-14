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
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
label: "{t}SKOs packed{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='packed'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "dp",
label: "{t}Count(distinct Part,Delivery){/t} (PD)",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='dp'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "dp_percentage",
label: "PD %",
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
label: "PD/h",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='dp_per_hour'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "issues",
label:'',
html_label: '<i style="color:saddlebrown" class="fa  discreet fa-poop"></i>',
title: "{t}Customer reported issues{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='issues'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell
},
{
name: "issues_percentage",
label:'',
html_label: '<i style="color:" class="far  discreet fa-poop"></i>%',
title: "{t}Percentage deliveries with customer reported issues{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='issues_percentage'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell
},
{*/*
{
name: "deliveries_with_errors",
label: "{t}Errors{/t} (D)",
title: "{t}Deliveries with errors{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='deliveries_with_errors'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "deliveries_with_errors_percentage",
label: "{t}Errors{/t} (D)%",
title: "{t}Percentage deliveries with errors{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='deliveries_with_errors_percentage'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "picks_with_errors",
label: "{t}Errors{/t} (P)",
title: "{t}Picks with errors{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='picks_with_errors'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "picks_with_errors_percentage",
label: "{t}Errors{/t} (P)%",
title: "{t}Percentage picks with errors{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='picks_with_errors_percentage'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
*/*}

]

function change_table_view(view,save_state){}

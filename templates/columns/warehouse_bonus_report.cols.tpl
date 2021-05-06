{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 January 2018 at 17:35:59 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

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
name: "picks",
label: "{t}Picks{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='picks'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "cartons",
label: "{t}Cartons{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='cartons'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
name: "bonus",
label: "{t}Bonus{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='bonus'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "salary",
label: "{t}Salary{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='salary'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "bonus_net",
label: "{t}Bonus net{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='bonus_net'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
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

function change_table_view(view,save_state){


}

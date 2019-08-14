{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:13-08-2019 13:34:52 MYT    Kuala Lumpur, Malaysia
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
name: "period",
label: "{t}Period{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "",})
},
{
name: "tariff_code",
label: "{t}Commodity{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "",})
},
{
name: "country_code",
label: "{t}Country{/t}",
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
{if $sort_key=='orders'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "parts",
label: "{t}Part{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='products'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "items",
label: "{t}Units send{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "value",
label: "{t}Amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='value'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "weight",
label: "{t}Weight{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='weight'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
]

function change_table_view(view,save_state){}

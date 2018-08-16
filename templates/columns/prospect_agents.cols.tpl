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
name: "new_prospects",
label: "{t}Prospects created{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='new_prospects'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "calls",
label: "{t}Calls{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='calls'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "emails_sent",
label: "{t}Emails sent{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='emails_sent'}deliveries: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "open_percentage",
label: "{t}Open %{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='open_rate'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "click_percentage",
label: "{t}Click %{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='click_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "register_percentage",
label: "{t}Register %{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='register_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "ordered_percentage",
label: "{t}Order %{/t}",
editable: false,
sortable:false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='ordered_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

]

function change_table_view(view,save_state){}

{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 December 2018 at 15:43:03 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
*/*}
var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "number",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='number'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })
}, 

{
name: "store",
label: "{t}Store{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({ })
},
{
name: "customer",
label: "{t}Customer{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({ })
},

{
name: "date",
label: "{t}Date creation{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},  {
name: "weight",
label: "{t}Weight{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='weight'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},  {
name: "parts",
label: "{t}Parts{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='parts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, 

{
name: "operations",
label: "{t}Assign picker{/t}",
editable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]

function change_table_view(view,save_state){}

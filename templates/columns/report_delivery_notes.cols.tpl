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
},{
name: "shipments",
label: "{t}Shipments{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='shipments'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "replacements",
label: "{t}Replacements{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='replacements'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "delivery_notes",
label: "{t}Delivery Notes{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='delivery_notes'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}


]

function change_table_view(view,save_state){}

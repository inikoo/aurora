{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2018 at 16:14:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
*/*}


var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},



{
name: "code",
label: "{t}Code{/t}   ",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ })
},



{
name: "label",
label: "{t}Label{/t}",
editable: false,
sortType: "toggle",
cell: "html"
},


{
name: "products",
label: "{t}Products{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='products'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "invoices",
label: "{t}Invoices{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},



{
name: "amount",
label: "{t}Invoiced amount{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

]




function change_table_view(view, save_state) {


}

{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 May 2018 at 18:55:54 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
*/*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},{
name: "formatted_id",
label: "{t}ID{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })


},  {
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
}, {
name: "location",
label: "{t}Location{/t}",
sortType: "toggle",
{if $sort_key=='location'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

editable: false,

cell: "html"
}, {
name: "activity",
label: "{t}Status{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='activity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: "string"
},

{
name: "favourited",
label: "{t}Favourited{/t}",
title: "{t}Favourited products in{/t} {$asset_code}",

editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='favourited'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},


{
name: "last_invoice",
label: "{t}Last invoice{/t}",
title: "{t}Last invoice with{/t} {$asset_code}",

defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='last_invoice'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},

{
name: "invoices",
label: "{t}Invoices{/t}",
title: "{t}Invoices with{/t} {$asset_code}",

editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "invoiced_amount",
label: "{t}Invoiced{/t}",
title: "{t}Invoiced amount{/t} {$asset_code}",

editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoiced_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "basket_amount",
label: "{t}Basket{/t}",
title: "{t}Basket amount{/t} {$asset_code}",

editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='basket_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}



]

function change_table_view(view,save_state){


}

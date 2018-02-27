{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 December 2017 at 11:40:20 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
*/*}


var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},

{
name: "delivery",
label: "{t}Stock transaction{/t}",
editable: false,
sortable: false,

{if $sort_key=='delivery'}direction: '{if $sort_order==1}ascending{else}descending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ } ),
},

{
name: "date",
label: "{t}Date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright width_300"} ),
headerCell: integerHeaderCell
},



{
name: "skos",
label: "{t}SKO received{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "cost",
label: "{t}Paid to supplier{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sko_cost",
label: "{t}Cost per SKO{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}




]

function change_table_view(view,save_state){




}


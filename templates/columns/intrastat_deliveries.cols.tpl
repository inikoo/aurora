{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 December 2017 at 15:50:40 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

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
name: "number",
label: "{t}Order{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})
}, {
name: "date",
label: "{t}Dispatch date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "supplier",
label: "{t}Supplier/Agent{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({})
},
 {
name: "parts",
label: "{t}Parts{/t}",
sortable: false,
editable: false,
cell: Backgrid.HtmlCell.extend({})
},
{
name: "amount",
label: "{t}Amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
}


]

function change_table_view(view,save_state){}

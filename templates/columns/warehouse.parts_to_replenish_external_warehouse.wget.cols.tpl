{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17:52:18 MYT Monday, 13 July 2020 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

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
name: "reference",
label: "{t}Part{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

}, {
name: "locations",
label: "{t}Locations{/t}",

sortable: false,
editable: false,

cell: "html"
},
{
name: "stock_external",
label: "{t}External stock{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock_external'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "stock_local",
label: "{t}Local stock{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock_local'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "to_pick",
label: "{t}Ordered{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='to_pick'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}


]

function change_table_view(view,save_state){

}
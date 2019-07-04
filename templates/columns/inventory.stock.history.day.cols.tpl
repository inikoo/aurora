{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 December 2017 at 15:59:06 GMT, Sheffield, UK
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

},{
name: "reference",
label: "{t}Part{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='reference'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })

},
{
name: "description",
label: "{t}Description{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='description'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })

},
{
name: "stock",
label: "{t}Stock{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"}),
headerCell: integerHeaderCell

},

{
name: "cost",
label: "{t}SKO value{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='cost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "stock_value",
label: "{t}Stock value{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock_value'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},


]

function change_table_view(view,save_state){

}
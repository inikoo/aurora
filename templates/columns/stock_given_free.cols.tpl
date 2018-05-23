{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2018 at 19:08:01 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

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
name: "delivery_note",
label: "{t}Delivery Note{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='delivery_note'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })

},


{
name: "type",
label: "{t}Type{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='type'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })

},

{
name: "stock",
label: "{t}SKOs{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"}),
headerCell: integerHeaderCell

},
{
name: "value",
label: "{t}Cost value{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='value'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}


]

function change_table_view(view,save_state){

}
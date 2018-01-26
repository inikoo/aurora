{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 January 2018 at 14:35:19 GMT+8, Kuala Lumpur, Malaysia
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

},

{
name: "can_pick",
label: "",
editable: false,
sortable:false,

cell: Backgrid.HtmlCell.extend({ })

},

{
name: "location",
label: "{t}Location{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ })

},



{
name: "last_audit",
label: "{t}Last audited{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='last_audit'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},


{
name: "quantity",
label: "{t}SKOs{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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

{
name: "space",
label: "",
editable: false,
sortable:false,

cell: Backgrid.HtmlCell.extend({
class: "width_20"

})

},

{
name: "notes",
label: "{t}Notes{/t}",
editable: false,
sortable:false,
cell:'Html',

},
]

function change_table_view(view,save_state){

}
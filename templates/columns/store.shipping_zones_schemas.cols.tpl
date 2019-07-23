{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 December 2018 at 15:12:09 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
*/*}

var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}, {
name: "type",
label: "",
editable: false,
sortable:false,
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"

})
},
{
name: "label",
label: "{t}Label{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='label'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ } ),
},
{
name: "zones",
label: "{t}Zones{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='zones'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
className: "aright"

} ),
headerCell: integerHeaderCell

},
{
name: "first_used",
label: "{t}First used{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='first_used'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "last_used",
label: "{t}Last used{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='last_used'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright" } ),
headerCell: integerHeaderCell

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

},
{
name: "orders",
label: "{t}Order{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='orders'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

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

}


]

function change_table_view(view,save_state){}

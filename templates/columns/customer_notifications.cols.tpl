{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 February 2018 at 18:20:03 GMT+8, Kuala, Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

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
name: "status",
label: "",
editable: false,
sortable:false,
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"
})
},
{
name: "type",
label: "{t}Type{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='type'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
})
},
{
name: "mailshots",
label: "{t}Mailshots{/t}",
renderable:false,
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='mailshots'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "sent",
label: "{t}Sent{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sent'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "bounces",
label: '{t}Bounces{/t}',
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='bounces'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},

{
name: "delivered",
label: "{t}Delivered{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='delivered'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "open",
label: "{t}Opened{/t}",
editable: false,
defaultOrder:1,
sortType: "open",
{if $sort_key=='read'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "clicked",
label: "{t}Clicked{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='clicked'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "spam",
label: "{t}Spam{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='spam'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}



]

function change_table_view(view,save_state){}

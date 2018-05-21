{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 May 2018 at 08:57:59 CEST, Trnava, Slovakia
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
name: "name",
label: "{t}Scope{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
})
}, {
name: "mailshots",
label: "{t}Mailshots{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='mailshots'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "send",
label: "{t}Emails send{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='send'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "open",
label: "{t}Open rate{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='open'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "clicked",
label: "{t}Click rate{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='clicked'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}


]

function change_table_view(view,save_state){}

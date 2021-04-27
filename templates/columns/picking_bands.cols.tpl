{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 April 2021 21:14:00 GMT+8, Kuala Lumpur , Malaysia
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
label: "{t}Name{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ } ),
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
name: "parts",
label: "{t}Parts{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='parts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},




]

function change_table_view(view,save_state){}

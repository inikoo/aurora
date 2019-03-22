{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2017 at 13:52:52 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
*/*}

var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}, {
name: "code",
label: "{t}Code{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='number'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })
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
name: "territories",
label: "{t}Territories{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='territories'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ } ),
},
{
name: "price",
label: "{t}Shipping price{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='price'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ } ),
}

]

function change_table_view(view,save_state){}

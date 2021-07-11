{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 July 2021 at 20:30:35 GMT+8 Kuala Lumpur Malaysia
 Copyright (c) 2021, Inikoo

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
name: "formatted_id",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })
},

{
name: "customer_delivery_reference",
label: "{t}Reference{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='customer_delivery_reference'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ } ),
},

{
name: "date",
label: "{t}Date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "state",
label: "{t}State{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='state'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "html"
}
]

function change_table_view(view,save_state){

}

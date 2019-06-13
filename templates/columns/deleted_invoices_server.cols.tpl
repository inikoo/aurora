{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12-06-2019 19:22:44 MYT   Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
*/*}


var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}, {
name: "store",
label: "{t}Store{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='store'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})
}, {
name: "number",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='number'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})
}, {
name: "user",
label: "{t}Deleted by{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='user'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})
},{
name: "date",
label: "{t}Deleted date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},{
name: "order",
label: "{t}Order{/t}",
sortable: false,
editable: false,
cell: Backgrid.HtmlCell.extend({
})
}, {
name: "type",
label: "{t}Type{/t}",
editable: false,
sortType: "toggle",
cell: "html"
}, {
name: "total_amount",
label: "{t}Total{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "note",
label: "{t}Note{/t}",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({

})
}
]

function change_table_view(view,save_state){}

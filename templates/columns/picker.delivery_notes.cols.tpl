{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  08 November 2019  10:10::43  +0100, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}, {
name: "store_key",
label: "",
editable: false,
renderable: false,
cell: "string"
},{
name: "customer_key",
label: "",
editable: false,
renderable: false,
cell: "string"
}, {
name: "number",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='number'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })
}, {
name: "date",
label: "{t}Date creation{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "customer",
label: "{t}Customer{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({ })
}, {
name: "type",
label: "{t}Type{/t}",
editable: false,
sortType: "toggle",
cell: "html"
}, {
name: "state",
label: "{t}Status{/t}",
editable: false,
sortType: "toggle",
cell: "html"
}, {
name: "weight",
label: "{t}Weight{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "parcels",
label: "{t}Parcels{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "notes",
label: "",
editable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]

function change_table_view(view,save_state){}

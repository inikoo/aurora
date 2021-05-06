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


},{
name: "number",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='number'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })
}, {
name: "date_packed",
label: "{t}Date packed{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date_packed'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
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
{if $sort_key=='parcels'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "bonus_picker",
label: "{t}Bonus pick{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='bonus_picker'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "bonus_packer",
label: "{t}Bonus pack{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='bonus_packer'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]

function change_table_view(view,save_state){}

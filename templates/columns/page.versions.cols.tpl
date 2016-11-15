{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2016 at 20:10:15 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3
*/*}


var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},

{
name: "device",
label:"{t}Device{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='device'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "string"

},

{
name: "code",
label:"{t}Code{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='code'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "html"
},

{
name: "template",
label:"{t}Template{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='title'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "string"
},

{
name: "display",
label:"{t}Display{/t}",
editable: false,
defaultOrder:1,
sortType: "state",
{if $sort_key=='title'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "html"
}  , {
name: "url",
label:"URL",
editable: false,
defaultOrder:1,
renderable: false,
sortType: "toggle",
{if $sort_key=='url'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "uri"
}, {
name: "users",
label:"{t}Users{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='users'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "requests",
label:"{t}Views{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='requests'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}

]
function change_table_view(view,save_state){}



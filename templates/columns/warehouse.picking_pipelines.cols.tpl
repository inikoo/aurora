{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14:13 Sun Jul 25 2021 Kuala Lumpur Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
*/*}
var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},

{
name: "name",
label: "{t}Name{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
orderSeparator: '',
events: {
},
})
},
{
name: "store",
label: "{t}Store{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
orderSeparator: '',
events: {
},
})
},

{
name: "locations",
label: "{t}Locations{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='locations'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}
, {
name: "parts",
label: "{t}Parts{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='parts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}

]

function change_table_view(view,save_state){

}
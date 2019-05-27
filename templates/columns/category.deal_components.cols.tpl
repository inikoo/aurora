{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 May 2018 at 09:54:43 CEST, Mijas Costa, Spain
 Copyright (c) 2018, Inikoo

 Version 3
*/*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

}, {
name: "store_key",
label: "",
editable: false,
renderable: false,
cell: "string",
sortType: "toggle",

},
{
name: "status",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},
{
name: "name",
label: "{t}Code{/t}",
editable: false,

sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({ })
},
{
name: "description",
label: "{t}Offer{/t}",
editable: false,

sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

}),

},
{
name: "from",
label: "{t}From{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='from'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "to",
label: "{t}To{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='to'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "orders",
label: "{t}Orders{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='orders'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "customers",
label: "{t}Customers{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='customers'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}
]

function change_table_view(view,save_state){

return;

}
{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Wed 16 Oct 2019 14:21:29 +0800 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
*/*}


var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},


{
name: "stock_status",
label: "",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"
})

},

{
name: "code",
label: "{t}Code{/t}   ",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ })
},



{
name: "name",
label: "{t}Name{/t}",
editable: false,
sortType: "toggle",
cell: "html"
},
 {
name: "created",
label: "{t}Since{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='created'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "until",
label: "{t}Until{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='until'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "orders",
label: "{t}Orders{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='orders'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},


{
name: "qty",
label: "{t}Quantity{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='qty'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
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

]




function change_table_view(view, save_state) {


}

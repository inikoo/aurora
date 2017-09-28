{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 September 2017 at 22:29:39 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
*/*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},{
name: "reference",
label: "{t}Reference{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({

})

},
{
name: "description",
label: "{t}Description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

},

{
name: "deliveries",
label: "{t}Sales Deliveries{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='deliveries'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "dispatched",
label: "{t}Dispatched SKOs{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='dispatched'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},


{
name: "sales",
label: "{t}Sales{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "delta_sales_percentage",
label: "{t}Delta % 1y{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='delta_sales_percentage'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "delta_sales",
label: "{t}Delta 1y{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='delta_sales'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}

]


function change_table_view(view,save_state){

}
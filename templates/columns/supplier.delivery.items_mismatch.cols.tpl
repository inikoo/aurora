{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 October 2018 at 22:39:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
*/*}
var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},
{
name: "type",
label: "",
renderable: true,

editable: false,
cell: Backgrid.HtmlCell.extend({
class:'width_20'
}),
},
{
name: "reference",
label: "{t}Part{/t}",
renderable: true,

editable: false,
cell: Backgrid.HtmlCell.extend({

}),
},{
name: "description",
label: "{t}SKO description{/t}",
editable: false,
cell: "html"

},


{
name: "items_qty",
label: "{t}Delivery Qty{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='items_qty'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: ""} ),

},

{
name: "checked_qty",
label: "{t}Actual checked Qty{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='checked_qty'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: ""} ),

},

{
name: "diff",
label: "{t}Diff{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='diff'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell


},

{
name: "diff_units",
label: "{t}Units{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='diff_units'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell


},


{
name: "diff_skos",
label: "{t}SKOs{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='diff_skos'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell


},

]


function change_table_view(view, save_state) {

{if isset($data['metadata']['create_delivery']) and $data['metadata']['create_delivery'] }

    grid.columns.findWhere({
    name: 'checkbox'
    }).set("renderable", true)

    grid.columns.findWhere({
    name: 'operations'
    }).set("renderable", true)

    grid.columns.findWhere({
    name: 'delivery_quantity'
    }).set("renderable", true)
{/if}

}

{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 August 2016 at 18:07:06 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "product_pid",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "checkbox",
renderable:false,
label: "",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: "width_20"} ),

},{
name: "operations",
renderable:false,
label: "",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: "width_20"} ),

},
{
name: "item_index",
renderable:false,
label: "",
editable: false,
cell: Backgrid.HtmlCell.extend({
events: {

},

}),
},
{
name: "supplier",
label: "{t}Supplier{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},
{
name: "image",
label: "{t}Image{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
}),
},
{
name: "reference",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},

{
name: "description",
label: "{t}Unit description{/t}",
editable: false,
cell: "html"

},


{
name: "packed_in",
label: "{t}Packed in{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='unit_cost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "units_per_carton",
label: "{t}U/C{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='unit_cost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "sko_per_carton",
label: "{t}Pack/C{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='unit_cost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "unit_cost",
label: "{t}Unit cost{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='unit_cost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "qty_units",
label: "{t}Units{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='qty_units'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "qty_cartons",
label: "{t}Cartons{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='qty_cartons'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "amount",
label: "{t}Total{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

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

{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 August 2018 at 15:36:34 GMT+8, Legian, Bli, Indonesia
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
},

{
name: "back_operations",
label: "",
sortable:false,
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},
{
name: "state",
label: "{t}State{/t}",
sortable:false,
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},
{
name: "forward_operations",
label: "",
sortable:false,
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
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

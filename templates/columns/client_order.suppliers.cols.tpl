{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 August 2018 at 20:42:23 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2018, Inikoo

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
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},
{
name: "name",
label: "{t}Name{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},



{
name: "products",
label: "{t}Products{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='products'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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

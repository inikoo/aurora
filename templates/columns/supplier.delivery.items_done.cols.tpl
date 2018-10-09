{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 October 2018 at 09:51:24 GMT+8, Kuala Lumpur Malaysia
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
name: "part_reference",
label: "{t}Part{/t}",
renderable:true,

editable: false,
cell: Backgrid.HtmlCell.extend({

}),
},{
name: "description",
label: "{t}SKO description{/t}",
editable: false,
cell: "html"

},{
name: "received_quantity",
label: "{t}SKOs In{/t}",
renderable: {if $data['_object']->get('State Index')>=40}true{else}false{/if},
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "items_amount",
label: "{t}Items{/t} ({$currency})",
renderable: true,

editable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "extra_amount",
label: "{t}Extra{/t} ({$currency})",
renderable: true,

editable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "extra_amount_account_currency",
label: "{t}Extra{/t} ({$currency_account})",
renderable: true,

editable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "total_paid",
label: "{t}Paid{/t} ({$currency_account})",
renderable: true,

editable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "sko_cost",
label: "",
renderable: true,

editable: false,

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

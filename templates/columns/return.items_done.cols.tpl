{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 November 2018 at 21:51:42 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo

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
label: "{t}SKOs Returned{/t}",
renderable: {if $data['_object']->get('State Index')>=40}true{else}false{/if},
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='received_quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},{
name: "checked_quantity",
label: "{t}SKOs Received{/t}",
renderable: {if $data['_object']->get('State Index')>=40}true{else}false{/if},
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='checked_quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "placement",
sortable:false,
label: "{t}Placements{/t}",
editable: false,
cell: "html",
headerCell: integerHeaderCell

}


]


function change_table_view(view, save_state) {



}

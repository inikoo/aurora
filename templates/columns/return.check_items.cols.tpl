{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 November 2018 at 13:29:55 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

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
name: "part_reference",
label: "{t}Part{/t}",
renderable: {if $data['_object']->get('State Index')>=30}true{else}false{/if},

editable: false,
cell: Backgrid.HtmlCell.extend({

}),
},{
name: "placement_notes",
label: "{t}Placement notes{/t}",
editable: false,
cell: "html"

},{
name: "description",
label: "{t}SKO description{/t}",
editable: false,
cell: "html"

},

{
name: "items_qty",
label: "{if $data['_object']->get('State Index')>=40  }{t}Returned SKOs{/t}{else}{t}SKO to return{/t}{/if}",
renderable: {if $data['_object']->get('State Index')>=40  }true{else}false{/if},
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='items_qty'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend( ),

},


{
name: "sko_edit_checked_quantity",
label: "{t}Checked SKO{/t}",
renderable: {if $data['_object']->get('State Index')>=40  }true{else}false{/if},
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


/*
{
name: "sko_checked_quantity",
label: "{t}Checked SKO{/t}",
renderable: {if $data['_object']->get('State Index')==100}true{else}false{/if},
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell
},
*/

{
name: "placement",
label: "{t}Placements{/t}",
renderable: {if $data['_object']->get('State Index')>=40}true{else}false{/if},

editable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}
]


function change_table_view(view, save_state) {


grid.columns.findWhere({ name: 'placement_notes'} ).set("renderable", false)

grid.columns.findWhere({ name: 'description'} ).set("renderable", false)
grid.columns.findWhere({ name: 'items_qty'} ).set("renderable", false)


if(view=='overview'){
grid.columns.findWhere({ name: 'description'} ).set("renderable", true)
grid.columns.findWhere({ name: 'items_qty'} ).set("renderable", true)
} else if(view=='placement_notes'){
grid.columns.findWhere({ name: 'placement_notes'} ).set("renderable", true)
}




}

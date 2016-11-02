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
cell: Backgrid.StringCell.extend({
events: {

},

}),
},
{
name: "supplier",
label: "{t}Supplier{/t}",
editable: false,
cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view("/supplier/"+this.model.get("supplier_key"))


}
},
className: "link"
}),
},
{
name: "reference",
label: "{t}S. Code{/t}",
editable: false,
cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view("supplier/"+this.model.get("supplier_key")+"/part/"+this.model.get("supplier_part_key"))


}
},
className: "link"
}),
},
{
name: "description",
label: "{t}Unit description{/t}",
editable: false,
cell: "html"

}, {
name: "subtotals",
label: "{t}Subtotals{/t}",
defautOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='subtotals'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: ""} ),

}, {
name: "quantity",
label: "{t}E Cartons{/t}",
renderable: {if $data['_object']->get('Purchase Order State')!='InProcess'}true{else}false{/if},
defautOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "ordered",
label: "{t}Cartons{/t}",
renderable: {if $data['_object']->get('Purchase Order State')=='InProcess'}true{else}false{/if},
defautOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='ordered'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "delivery_quantity",
label: "{t}Delivery{/t}",
renderable: false,
defautOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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

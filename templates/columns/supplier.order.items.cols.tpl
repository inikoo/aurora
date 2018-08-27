var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}
,{
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
name: "reference",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
})
},
{
name: "image",
label: "{t}Image{/t}",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({
})
},
{
name: "description",
label: "{t}Carton description{/t}",
editable: false,
cell: "html"

},
{
name: "unit_description",
label: "{t}Unit description{/t}",
editable: false,
cell: "html"

},

{
name: "description_sales",
label: "{t}Carton description{/t}",
editable: false,
cell: "html"

},
{
name: "unit",
label: "{t}Unit description{/t}",
editable: false,
cell: "html"
},

{
name: "unit_cost",
label: "{t}Unit cost{/t}",
editable: false,
cell: "html"
},
{
name: "units_per_sko",
label: "U/SKO",
editable: false,
cell: "html"
},

{
name: "skos_per_carton",
label: "SKOs/C",
editable: false,
cell: "html"
},

{
name: "ordered_skos",
label: "{t}SKOs{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='ordered_skos'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "ordered_cartons",
label: "{t}Cartons{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='ordered_cartons'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "quantity",
label: "{t}Cartons{/t}",
renderable: {if $data['_object']->get('Purchase Order State')=='InProcess'}true{else}false{/if},
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


{
name: "info",
label: "{t}Info{/t}",
editable: false,
cell: "html"

},
{
name: "subtotals",
label: "{t}Subtotals{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='subtotals'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: ""} ),

},
{
name: "quantity",
label: "{t}Cartons{/t}",
renderable: {if $data['_object']->get('Purchase Order State')=='InProcess'}true{else}false{/if},
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "ordered",
label: "{t}Cartons{/t}",
renderable: {if $data['_object']->get('Purchase Order State')!='InProcess'}true{else}false{/if},
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='ordered'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "delivery_quantity",
label: "{t}Delivery{/t}",
renderable: false,
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "amount",
label: "{t}Amount{/t}",
renderable: false,
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "state",
label: "{t}State{/t}",
defaultOrder:1,
editable: false,
sortType: "state",
{if $sort_key=='amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "delivery_quantity",
label: "{t}Delivery{/t}",
renderable: false,
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='delivery_quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}


]

function change_table_view(view, save_state) {

grid.columns.findWhere({ name: 'unit_description'} ).set("renderable", false)

grid.columns.findWhere({ name: 'description'} ).set("renderable", false)
grid.columns.findWhere({ name: 'description_sales'} ).set("renderable", false)
grid.columns.findWhere({ name: 'unit'} ).set("renderable", false)
grid.columns.findWhere({ name: 'units_per_sko'} ).set("renderable", false)

grid.columns.findWhere({ name: 'skos_per_carton'} ).set("renderable", false)

grid.columns.findWhere({ name: 'unit_cost'} ).set("renderable", false)
grid.columns.findWhere({ name: 'quantity'} ).set("renderable", false)
grid.columns.findWhere({ name: 'info'} ).set("renderable", false)
grid.columns.findWhere({ name: 'subtotals'} ).set("renderable", false)
grid.columns.findWhere({ name: 'ordered'} ).set("renderable", false)

grid.columns.findWhere({ name: 'ordered_skos'} ).set("renderable", false)
grid.columns.findWhere({ name: 'ordered_cartons'} ).set("renderable", false)
grid.columns.findWhere({ name: 'amount'} ).set("renderable", false)
grid.columns.findWhere({ name: 'state'} ).set("renderable", false)




if(view=='overview'){
grid.columns.findWhere({ name: 'unit_description'} ).set("renderable", true)
grid.columns.findWhere({ name: 'units_per_sko'} ).set("renderable", true)

grid.columns.findWhere({ name: 'skos_per_carton'} ).set("renderable", true)

grid.columns.findWhere({ name: 'ordered_skos'} ).set("renderable", true)
grid.columns.findWhere({ name: 'ordered_cartons'} ).set("renderable", true)
grid.columns.findWhere({ name: 'amount'} ).set("renderable", true)
grid.columns.findWhere({ name: 'state'} ).set("renderable", true)

}else if(view=='ordering'){
grid.columns.findWhere({ name: 'description_sales'} ).set("renderable", true)

}else if(view=='ordering_with_sales'){
grid.columns.findWhere({ name: 'description_sales'} ).set("renderable", true)

}




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

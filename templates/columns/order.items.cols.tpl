var columns = [
{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
}),
},{
name: "description",
label: "{t}Description{/t}",
editable: false,
cell: "html"

},

{
name: "tariff_code",
label: "{t}Tariff code{/t}",
sortType: "toggle",
editable: false,
cell: "html"

},
{
name: "package_weight",
label: "{t}Outer weight{/t}",
sortType: "toggle",

editable: false,
cell: "html"

},

{
name: "discounts",
label: "{t}Discounts{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='discounts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "packing",
label: "{t}Packing{/t}",
defaultOrder:1,
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "quantity",
label: "{t}Quantity{/t}",
renderable: {if $object->get('Order State')=='InBasket' or $object->get('Order State')=='InProcess'}false{else}true{/if},

defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "quantity_edit",
label: "{t}Quantity{/t}",
renderable: {if $object->get('Order State')=='InBasket' or $object->get('Order State')=='InProcess'}true{else}false{/if},

defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='quantity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "net",
label: "{t}Net{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='net'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell
},
{
name: "weight",
label: "{t}Weight{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='weight'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright width_100"} ),
headerCell: integerHeaderCell
}



]



function change_table_view(view,save_state){



$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');





grid.columns.findWhere({ name: 'description'} ).set("renderable", false)
grid.columns.findWhere({ name: 'discounts'} ).set("renderable", false)

grid.columns.findWhere({ name: 'net'} ).set("renderable", false)

grid.columns.findWhere({ name: 'tariff_code'} ).set("renderable", false)
grid.columns.findWhere({ name: 'package_weight'} ).set("renderable", false)
grid.columns.findWhere({ name: 'weight'} ).set("renderable", false)


if(view=='overview'){


grid.columns.findWhere({ name: 'description'} ).set("renderable", true)
grid.columns.findWhere({ name: 'discounts'} ).set("renderable", true)

grid.columns.findWhere({ name: 'net'} ).set("renderable", true)



}if(view=='properties'){
grid.columns.findWhere({ name: 'tariff_code'} ).set("renderable", true)
grid.columns.findWhere({ name: 'package_weight'} ).set("renderable", true)
grid.columns.findWhere({ name: 'weight'} ).set("renderable", true)



}

}
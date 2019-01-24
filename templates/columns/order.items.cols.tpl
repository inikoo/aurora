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

},{
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
}
]


function change_table_view(view, save_state) {}

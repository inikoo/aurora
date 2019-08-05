{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2017 at 13:52:52 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
*/*}

var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}, {
name: "code",
label: "{t}Code{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='number'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })
},
{
name: "name",
label: "{t}Name{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ } ),
},
{
name: "territories",
label: "{t}Territories{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='territories'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ } ),
},
{
name: "price",
label: "{t}Shipping price{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='price'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ } ),
},

{
name: "first_used",
label: "{t}First used{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='first_used'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "last_used",
label: "{t}Last used{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='last_used'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright" } ),
headerCell: integerHeaderCell

},
{
name: "customers",
label: "{t}Customers{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='customers'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "orders",
label: "{t}Order{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='orders'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "amount",
label: "{t}Amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}

]

function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');

grid.columns.findWhere({ name: 'territories'} ).set("renderable", false)
grid.columns.findWhere({ name: 'price'} ).set("renderable", false)

grid.columns.findWhere({ name: 'first_used'} ).set("renderable", false)
grid.columns.findWhere({ name: 'last_used'} ).set("renderable", false)
grid.columns.findWhere({ name: 'customers'} ).set("renderable", false)
grid.columns.findWhere({ name: 'orders'} ).set("renderable", false)
grid.columns.findWhere({ name: 'amount'} ).set("renderable", false)

if(view=='overview'){
grid.columns.findWhere({ name: 'territories'} ).set("renderable", true)
grid.columns.findWhere({ name: 'price'} ).set("renderable", true)


}else if(view=='usage'){
grid.columns.findWhere({ name: 'first_used'} ).set("renderable", true)
grid.columns.findWhere({ name: 'last_used'} ).set("renderable", true)
grid.columns.findWhere({ name: 'customers'} ).set("renderable", true)
grid.columns.findWhere({ name: 'orders'} ).set("renderable", true)
grid.columns.findWhere({ name: 'amount'} ).set("renderable", true)
}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}

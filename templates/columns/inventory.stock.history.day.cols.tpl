{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 December 2017 at 15:59:06 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3
*/*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},{
name: "reference",
label: "{t}Part{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='reference'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })

},
{
name: "description",
label: "{t}Description{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='description'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })

},
{
name: "stock",
label: "{t}Stock{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"}),
headerCell: integerHeaderCell

},

{
name: "cost",
label: "{t}SKO value{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='cost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "stock_value",
label: "{t}Stock value{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock_value'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "no_sales_1_year",
label: "{t}Sales 1 year{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='no_sales_1_year'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ })

},
{
name: "stock_left_1_year_ago",
label: "{t}Stock older than 1 year{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock_left_1_year_ago'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"}),
headerCell: integerHeaderCell

},

]


function change_table_view(view, save_state) {

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


grid.columns.findWhere({ name: 'no_sales_1_year'} ).set("renderable", false)
grid.columns.findWhere({ name: 'stock_left_1_year_ago'} ).set("renderable", false)



grid.columns.findWhere({ name: 'stock'} ).set("renderable", false)
grid.columns.findWhere({ name: 'cost'} ).set("renderable", false)
grid.columns.findWhere({ name: 'stock_value'} ).set("renderable", false)

if(view=='overview'){

grid.columns.findWhere({ name: 'stock'} ).set("renderable", true)
grid.columns.findWhere({ name: 'cost'} ).set("renderable", true)
grid.columns.findWhere({ name: 'stock_value'} ).set("renderable", true)


}else if(view=='1_year'){
grid.columns.findWhere({ name: 'no_sales_1_year'} ).set("renderable", true)
grid.columns.findWhere({ name: 'stock_left_1_year_ago'} ).set("renderable", true)

}



if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}

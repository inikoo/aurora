var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "associated",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},
{
name: "family",
label: "{t}Family{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.StringCell.extend({
events: {

},
className: " width_150",
})
},

{
name: "reference",
label: "{t}Reference{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view('{if $data['parent']=='account'}{else if $data['parent']=='category'}category/{$data['key']}/{else}{$data['parent']}/{$data['parent_key']}/{/if}part/' + this.model.get("id"))
}
},
className: "link"

})

},
{
name: "unit_description",
label: "{t}Unit description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.StringCell.extend({


})

},
{
name: "stock_status",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},
{
name: "stock",
label: "{t}Stock{/t}",
editable: false,

defautOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sold",
label: "{t}Sold{/t}",
editable: false,

defautOrder:1,
sortType: "toggle",
{if $sort_key=='sold'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sold_1y",
label: "",
editable: false,

defautOrder:1,
sortType: "toggle",
{if $sort_key=='sold'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "revenue",
label: "{t}Revenue{/t}",
editable: false,

defautOrder:1,
sortType: "toggle",
{if $sort_key=='money_in'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "revenue_1y",
label: "",
editable: false,

defautOrder:1,
sortType: "toggle",
{if $sort_key=='revenue_1y'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "lost",
label: "{t}Lost{/t}",
editable: false,

defautOrder:1,
sortType: "toggle",
{if $sort_key=='lost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "bought",
label: "{t}Bought{/t}",
editable: false,

defautOrder:1,
sortType: "toggle",
{if $sort_key=='bought'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}
]

function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


close_columns_period_options()
$('#columns_period').addClass('hide');

grid.columns.findWhere({ name: 'unit_description'} ).set("renderable", false)
grid.columns.findWhere({ name: 'stock'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sold'} ).set("renderable", false)
grid.columns.findWhere({ name: 'revenue'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sold_1y'} ).set("renderable", false)
grid.columns.findWhere({ name: 'revenue_1y'} ).set("renderable", false)
grid.columns.findWhere({ name: 'lost'} ).set("renderable", false)
grid.columns.findWhere({ name: 'bought'} ).set("renderable", false)

if(view=='overview'){
grid.columns.findWhere({ name: 'unit_description'} ).set("renderable", true)
grid.columns.findWhere({ name: 'stock'} ).set("renderable", true)
grid.columns.findWhere({ name: 'revenue'} ).set("renderable", true)
$('#columns_period').removeClass('hide');


}else if(view=='sales'){
grid.columns.findWhere({ name: 'sold'} ).set("renderable", true)
grid.columns.findWhere({ name: 'revenue'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sold_1y'} ).set("renderable", true)
grid.columns.findWhere({ name: 'revenue_1y'} ).set("renderable", true)
$('#columns_period').removeClass('hide');


}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
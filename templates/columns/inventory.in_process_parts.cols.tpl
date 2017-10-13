var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

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
name: "sko_description",
label: "{t}SKO description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.StringCell.extend({


})

},
{
name: "valid_from",
label: "{t}Created{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='valid_from'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.StringCell.extend({


})

},
{
name: "has_stock",
label: "{t}Stock{/t}",
editable: false,

// defaultOrder:1,
sortType: "toggle",
{if $sort_key=='has_stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "has_products",
label: "{t}Products{/t}",
editable: false,

//  defaultOrder:1,
sortType: "toggle",
{if $sort_key=='has_products'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},


{
name: "cost",
label: "{t}Cost{/t}",
editable: false,
sortType: "toggle",
defaultOrder:1,
{if $sort_key=='cost'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.StringCell.extend({

className: "aright"
}),
headerCell: integerHeaderCell

},


{
name: "margin",
label: "{t}Margin{/t}",
editable: false,
sortType: "toggle",
defaultOrder:1,
{if $sort_key=='margin'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

className: "aright"
}),
headerCell: integerHeaderCell

}

]

function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


close_columns_period_options()
$('#columns_period').addClass('hide');

grid.columns.findWhere({ name: 'sko_description'} ).set("renderable", false)
grid.columns.findWhere({ name: 'valid_from'} ).set("renderable", false)

grid.columns.findWhere({ name: 'has_stock'} ).set("renderable", false)
grid.columns.findWhere({ name: 'has_products'} ).set("renderable", false)


if(view=='overview'){
grid.columns.findWhere({ name: 'sko_description'} ).set("renderable", true)
grid.columns.findWhere({ name: 'has_stock'} ).set("renderable", true)
grid.columns.findWhere({ name: 'has_products'} ).set("renderable", true)
grid.columns.findWhere({ name: 'valid_from'} ).set("renderable", true)

$('#columns_period').removeClass('hide');


}


if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
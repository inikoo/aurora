var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},{
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
name: "description",
label: "{t}Description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

},

{
name: "dispatched_per_week",
label: "{t}Dispatched/w{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "available_forecast",
label: "{t}Available forecast{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "stock",
label: "{t}Stock{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},


]

function get_quarter_label(index) {
var d = new Date();
d.setMonth(d.getMonth() - 3 * index);
return getQuarter(d) + 'Q ' + d.getFullYear().toString().substr(2, 2)
}

function getQuarter(d) {
d = d || new Date();
var q = [1, 2, 3, 4];
return q[Math.floor(d.getMonth() / 3)];
}


function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


close_columns_period_options()
$('#columns_period').addClass('hide');

//   grid.columns.findWhere({ name: 'description'} ).set("renderable", false)
//  grid.columns.findWhere({ name: 'stock'} ).set("renderable", false)
//   grid.columns.findWhere({ name: 'dispatched_per_week'} ).set("renderable", false)
//  grid.columns.findWhere({ name: 'weeks_available'} ).set("renderable", false)


if(view=='overview'){
//    $('#columns_period').removeClass('hide');
//   grid.columns.findWhere({ name: 'description'} ).set("renderable", true)
//  grid.columns.findWhere({ name: 'stock'} ).set("renderable", true)
}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
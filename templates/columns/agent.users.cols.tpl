var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},{
name: "employee_key",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "active_icon",
label: "{t}Active{/t}",
editable: false,
sortType: "active",
{if $sort_key=='active_icon'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "Html"
},
{
name: "handle",
label: "{t}Handle{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='handle'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })

}, {
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ })
}

]

function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


grid.columns.findWhere({ name: 'name'} ).set("renderable", false)
if(view=='overview'){
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)

}else if(view=='weblog'){


}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
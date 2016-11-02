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
name: "active",
label: "{t}Active{/t}",
editable: false,
sortType: "active",
{if $sort_key=='active'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "string"
},
{
name: "handle",
label: "{t}Handle{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='handle'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view('account/user/' +this.model.get("id"))
}
},
className: "link"

})

}, {
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view('employee/' + +this.model.get("employee_key"))
}
}
})
}

]

function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


grid.columns.findWhere({ name: 'name'} ).set("renderable", false)

if(view=='privilegies'){
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)

}else if(view=='groups'){
}else if(view=='weblog'){


}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
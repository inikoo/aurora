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
orderSeparator: '',
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
}, {
name: "groups",
label: "{t}Groups{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='groups'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "string"
}, {
name: "stores",
label: "{t}Stores{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='stores'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "string"
}, {
name: "warehouses",
label: "{t}Warehouses{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='warehouses'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "string"
}, {
name: "websites",
label: "{t}Websites{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='websites'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "string"
}

]

function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');

grid.columns.findWhere({ name: 'groups'} ).set("renderable", false)
grid.columns.findWhere({ name: 'stores'} ).set("renderable", false)
grid.columns.findWhere({ name: 'warehouses'} ).set("renderable", false)
grid.columns.findWhere({ name: 'websites'} ).set("renderable", false)

grid.columns.findWhere({ name: 'name'} ).set("renderable", false)

if(view=='privilegies'){
grid.columns.findWhere({ name: 'stores'} ).set("renderable", true)
grid.columns.findWhere({ name: 'warehouses'} ).set("renderable", true)
grid.columns.findWhere({ name: 'websites'} ).set("renderable", true)
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)

}else if(view=='groups'){
grid.columns.findWhere({ name: 'groups'} ).set("renderable", true)
}else if(view=='weblog'){


}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
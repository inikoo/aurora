var columns = [{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

}, {
name: "status",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

}, {
name: "reference",
label: "{t}Reference{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({

})


}, {
name: "description",
label: "{t}Description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

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
{
name: "components",
label: "{t}Materials{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='components'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "tasks",
label: "{t}Tasks{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='tasks'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]


function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');



if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
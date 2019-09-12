var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
}, {
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({

}),

}, {
name: "name",
label:"{t}Website name{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='items'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
} ),
}, {
name: "url",
label:"URL",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='url'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({
} )
}, {
name: "online_users",
label:"{t}Online{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='online_users'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},{
name: "users",
label:"{t}Registered{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='users'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},  {
name: "pages",
label:"{t}Pages{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='pages'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}

]



function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');

grid.columns.findWhere({ name: 'code'} ).set("renderable", false)
grid.columns.findWhere({ name: 'url'} ).set("renderable", false)
grid.columns.findWhere({ name: 'users'} ).set("renderable", false)
grid.columns.findWhere({ name: 'pages'} ).set("renderable", false)

grid.columns.findWhere({ name: 'online_users'} ).set("renderable", false)



if(view=='overview'){
grid.columns.findWhere({ name: 'users'} ).set("renderable", true)

grid.columns.findWhere({ name: 'online_users'} ).set("renderable", true)

}else if(view=='gsc'){

}else if(view=='ga'){

}else if(view=='webpages'){


}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
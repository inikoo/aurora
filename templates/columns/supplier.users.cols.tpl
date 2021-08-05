var columns = [

{
name: "active_icon",
label: "{t}Active{/t}",
editable: false,
sortType: "active",
{if $sort_key=='active'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: "Html"
},
{
name: "handle",
label: "{t}Handle{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='handle'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})

}
,{
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})
},
{
name: "email",
label: "{t}Email{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

})
},
{
name: "logins",
label: "{t}Logins{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='logins'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"}),
headerCell: integerHeaderCell

},
{
name: "last_login",
label: "{t}Last login{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='last_login'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"}),
headerCell: integerHeaderCell

},
{
name: "fail_logins",
label: "{t}Fail logins{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='fail_logins'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"}),
headerCell: integerHeaderCell

}



]

function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');


grid.columns.findWhere({ name: 'last_login'} ).set("renderable", false)
grid.columns.findWhere({ name: 'email'} ).set("renderable", false)
grid.columns.findWhere({ name: 'logins'} ).set("renderable", false)
grid.columns.findWhere({ name: 'fail_logins'} ).set("renderable", false)




grid.columns.findWhere({ name: 'name'} ).set("renderable", false)
if(view=='overview'){

grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'email'} ).set("renderable", true)
grid.columns.findWhere({ name: 'last_login'} ).set("renderable", true)


}else if(view=='weblog'){

grid.columns.findWhere({ name: 'last_login'} ).set("renderable", true)
grid.columns.findWhere({ name: 'logins'} ).set("renderable", true)
grid.columns.findWhere({ name: 'fail_logins'} ).set("renderable", true)

}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {

});
}

}
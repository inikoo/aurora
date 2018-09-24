{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 September 2018 at 09:33:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
*/*}

var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},

{
name: "name",
label: "{t}Name{/t}",
editable: false,

sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({

})
},{
name: "email",
label: "{t}Email{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='email'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.EmailCell.extend({

})
}, {
name: "location",
label: "{t}Location{/t}",
sortType: "toggle",
{if $sort_key=='location'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

editable: false,

cell: "html"
},
{
name: "contact_since",
label: "{t}Created{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='contact_since'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},

{
name: "status",
label: "{t}Status{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='activity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: "string"
}, {
name: "company_name",
label: "{t}Company{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='company_name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
}, {
name: "contact_name",
label: "{t}Main contact{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='contact_name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
}, {
name: "mobile",
label: "{t}Mobile{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='mobile'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
}, {
name: "telephone",
label: "{t}Telephone{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='telephone'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
}


]

function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');

grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'status'} ).set("renderable", false)
grid.columns.findWhere({ name: 'location'} ).set("renderable", false)

grid.columns.findWhere({ name: 'contact_since'} ).set("renderable", false)

grid.columns.findWhere({ name: 'company_name'} ).set("renderable", false)
grid.columns.findWhere({ name: 'contact_name'} ).set("renderable", false)
grid.columns.findWhere({ name: 'email'} ).set("renderable", false)
grid.columns.findWhere({ name: 'mobile'} ).set("renderable", false)
grid.columns.findWhere({ name: 'telephone'} ).set("renderable", false)


if(view=='overview'){
grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
grid.columns.findWhere({ name: 'location'} ).set("renderable", true)
grid.columns.findWhere({ name: 'contact_since'} ).set("renderable", true)
grid.columns.findWhere({ name: 'email'} ).set("renderable", true)

}else if(view=='contact'){
grid.columns.findWhere({ name: 'company_name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'contact_name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'email'} ).set("renderable", true)
grid.columns.findWhere({ name: 'mobile'} ).set("renderable", true)
grid.columns.findWhere({ name: 'telephone'} ).set("renderable", true)
}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
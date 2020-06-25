{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5:04 pm Thursday, 25 June 2020 (MYT) Kuala Lumpur , Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}


var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "payroll_id",
label: "{t}Id{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})
},
{
name: "code",
label: "{t}Code{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})
},

{
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
events: {

}
})
},
{
name: "po_queued",
label:'',
title:'{t}Queued job orders{/t}',
html_label: '<i class="fal fa-clipboard"></i> <i class="fal small fa-stopwatch"></i>',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='po_queued'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "po_manufacturing",
label:'',
title:'{t}Manufacturing job orders{/t}',
html_label: '<i class="fal fa-clipboard"></i> <i class="fal small fa-fill-drip"></i>',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='po_manufacturing'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

}


]

function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');

grid.columns.findWhere({ name: 'payroll_id'} ).set("renderable", false)

grid.columns.findWhere({ name: 'code'} ).set("renderable", false)
grid.columns.findWhere({ name: 'name'} ).set("renderable", false)
grid.columns.findWhere({ name: 'po_queued'} ).set("renderable", false)
grid.columns.findWhere({ name: 'po_manufacturing'} ).set("renderable", false)



if(view=='overview'){

grid.columns.findWhere({ name: 'payroll_id'} ).set("renderable", true)

grid.columns.findWhere({ name: 'code'} ).set("renderable", true)
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'po_queued'} ).set("renderable", true)
grid.columns.findWhere({ name: 'po_manufacturing'} ).set("renderable", true)
}else if(view=='personal_info'){

}else if(view=='employment'){




}else if(view=='system_user'){


}else if(view=='system_roles'){



}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {

});
}

}
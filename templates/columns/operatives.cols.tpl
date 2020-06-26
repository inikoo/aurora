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
name: "payroll_id",
label: "{t}Id{/t}",
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})
},
{
name: "po_placed",
label:'',
title:'{t}Placed job orders{/t}',
html_label: '<i class="fal fa-clipboard"></i> {t}Placed{/t}',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='po_placed'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

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

},
{
name: "po_manufactured",
label:'',
title:'{t}Manufactured job orders{/t}',
html_label: '<i class="fal fa-clipboard"></i> <i class="fal small fa-flag-checkered purple"></i>',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='po_manufactured'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "po_placing",
label:'',
title:'{t}Manufactured job orders{/t}',
html_label: '<i class="fal fa-clipboard"></i> <i class="fal small fa-hand-holding-heart"></i>',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='po_placing'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "total_transactions",
label:'',
title:'{t}Total tasks completed{/t}',
html_label: '<i class="fal fa-tasks"></i> {t}Completed{/t}',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_transactions'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "transactions_queued",
label:'',
title:'{t}Queued tasks{/t}',
html_label: '<i class="fal fa-tasks"></i> <i class="fal small fa-stopwatch"></i>',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='transactions_queued'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "transactions_manufacturing",
label:'',
title:'{t}Tasks been made{/t}',
html_label: '<i class="fal fa-tasks"></i> <i class="fal small fa-fill-drip"></i>',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='transactions_manufacturing'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "transactions_manufactured",
label:'',
title:'{t}Task done waiting for QC{/t}',
html_label: '<i class="fal fa-tasks"></i> <i class="fal small fa-flag-checkered purple"></i>',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='transactions_manufactured'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "products_placed",
label:'',
title:'{t}Distinct products placed{/t}',
html_label: '<i class="fal fa-fa-box-alt"></i> {t}Placed{/t}',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='products_placed'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "products_queued",
label:'',
title:'{t}Queued distinct products{/t}',
html_label: '<i class="fal fa-box-alt"></i> <i class="fal small fa-stopwatch"></i>',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='products_queued'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "products_manufacturing",
label:'',
title:'{t}Distinct products currently been manufacturing{/t}',
html_label: '<i class="fal fa-box-alt"></i> <i class="fal small fa-fill-drip"></i>',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='products_manufacturing'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "products_manufactured",
label:'',
title:'{t}Distinct products manufactured waiting for inspection{/t}',
html_label: '<i class="fal fa-box-alt"></i> <i class="fal small fa-flag-checkered"></i>',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='products_manufactured'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},
{
name: "products_placing",
label:'',
title:'{t}Distinct products been booked in warehouse{/t}',
html_label: '<i class="fal fa-box-alt"></i> <i class="fal small fa-hand-holding-heart"></i>',
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='products_placing'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
grid.columns.findWhere({ name: 'po_manufactured'} ).set("renderable", false)
grid.columns.findWhere({ name: 'po_placing'} ).set("renderable", false)
grid.columns.findWhere({ name: 'po_placed'} ).set("renderable", false)


grid.columns.findWhere({ name: 'products_queued'} ).set("renderable", false)
grid.columns.findWhere({ name: 'products_manufacturing'} ).set("renderable", false)
grid.columns.findWhere({ name: 'products_manufactured'} ).set("renderable", false)
grid.columns.findWhere({ name: 'products_placing'} ).set("renderable", false)
grid.columns.findWhere({ name: 'products_placed'} ).set("renderable", false)

grid.columns.findWhere({ name: 'transactions_queued'} ).set("renderable", false)
grid.columns.findWhere({ name: 'transactions_manufacturing'} ).set("renderable", false)
grid.columns.findWhere({ name: 'transactions_manufactured'} ).set("renderable", false)
grid.columns.findWhere({ name: 'total_transactions'} ).set("renderable", false)


if(view=='overview'){

grid.columns.findWhere({ name: 'payroll_id'} ).set("renderable", true)

grid.columns.findWhere({ name: 'code'} ).set("renderable", true)
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'po_queued'} ).set("renderable", true)
grid.columns.findWhere({ name: 'po_manufacturing'} ).set("renderable", true)
grid.columns.findWhere({ name: 'products_queued'} ).set("renderable", true)
grid.columns.findWhere({ name: 'products_manufacturing'} ).set("renderable", true)
grid.columns.findWhere({ name: 'products_manufactured'} ).set("renderable", true)

}else if(view=='purchase_orders'){
grid.columns.findWhere({ name: 'code'} ).set("renderable", true)
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)

grid.columns.findWhere({ name: 'po_queued'} ).set("renderable", true)
grid.columns.findWhere({ name: 'po_manufacturing'} ).set("renderable", true)
grid.columns.findWhere({ name: 'po_manufactured'} ).set("renderable", true)
grid.columns.findWhere({ name: 'po_placing'} ).set("renderable", true)
grid.columns.findWhere({ name: 'po_placed'} ).set("renderable", true)
}else if(view=='tasks'){

grid.columns.findWhere({ name: 'code'} ).set("renderable", true)
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)

grid.columns.findWhere({ name: 'transactions_queued'} ).set("renderable", true)
grid.columns.findWhere({ name: 'transactions_manufacturing'} ).set("renderable", true)
grid.columns.findWhere({ name: 'transactions_manufactured'} ).set("renderable", true)
grid.columns.findWhere({ name: 'total_transactions'} ).set("renderable", true)


}else if(view=='products'){
grid.columns.findWhere({ name: 'code'} ).set("renderable", true)
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)

grid.columns.findWhere({ name: 'products_queued'} ).set("renderable", true)
grid.columns.findWhere({ name: 'products_manufacturing'} ).set("renderable", true)
grid.columns.findWhere({ name: 'products_manufactured'} ).set("renderable", true)
grid.columns.findWhere({ name: 'products_placing'} ).set("renderable", true)
grid.columns.findWhere({ name: 'products_placed'} ).set("renderable", true)
}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {

});
}

}
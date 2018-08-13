{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 August 2018 at 15:30:15 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
*/*}


var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},
{
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({ })

},
{
name: "label",
label:"{t}Label{/t}",
editable: false,
cell: "string"
},
{
name: "refunds",
label:"{t}Refunds{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refunds'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "refunds_1yb",
label:"&Delta; 1Y",
title:"{t}Invoices same period 1 year back{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refunds_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell,
},
{
name: "invoices",
label:"{t}Invoices{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "invoices_1yb",
label:"&Delta; 1Y",
title:"{t}Refunds same period 1 year back{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoices_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell,
},
{
name: "sales",
label:"{t}Revenue{/t}",
title:"{t}Sales minus refunds{/t}",

editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell,
},
{
name: "sales_1yb",
label:"&Delta; 1Y",
title:"{t}Revenue same period 1 year back{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell,
}



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

grid.columns.findWhere({ name: 'code'} ).set("renderable", false)
grid.columns.findWhere({ name: 'label'} ).set("renderable", false)

grid.columns.findWhere({ name: 'refunds'} ).set("renderable", false)
grid.columns.findWhere({ name: 'refunds_1yb'} ).set("renderable", false)
grid.columns.findWhere({ name: 'invoices'} ).set("renderable", false)
grid.columns.findWhere({ name: 'invoices_1yb'} ).set("renderable", false)

grid.columns.findWhere({ name: 'sales'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sales_1yb'} ).set("renderable", false)



if(view=='overview'){
$('#columns_period').removeClass('hide');
grid.columns.findWhere({ name: 'code'} ).set("renderable", true)
grid.columns.findWhere({ name: 'label'} ).set("renderable", true)

grid.columns.findWhere({ name: 'refunds'} ).set("renderable", true)
grid.columns.findWhere({ name: 'refunds_1yb'} ).set("renderable", true)
grid.columns.findWhere({ name: 'invoices'} ).set("renderable", true)
grid.columns.findWhere({ name: 'invoices_1yb'} ).set("renderable", true)

grid.columns.findWhere({ name: 'sales'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_1yb'} ).set("renderable", true)
}else if(view=='performance'){

}else if(view=='sales'){

}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}

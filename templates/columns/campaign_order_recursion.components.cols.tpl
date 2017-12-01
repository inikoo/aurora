{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 November 2017 at 15:11:20 GMT+7, Bangkok, Thailand
 Copyright (c) 2016, Inikoo

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

}, {
name: "store_key",
label: "",
editable: false,
renderable: false,
cell: "string",
sortType: "toggle",

},
{
name: "status",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},

{
name: "allowance",
label: "{t}Allowance{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

}),

},


{
name: "target",
label: "{t}Target{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({

}),

},

{
name: "description",
label: "{t}Description{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({

}),

},


{
name: "from",
label: "{t}From{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='from'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "to",
label: "{t}To{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='to'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "orders",
label: "{t}Orders{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='orders'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "customers",
label: "{t}Customers{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='customers'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}
]

function change_table_view(view,save_state){

return;

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');

grid.columns.findWhere({ name: 'name'} ).set("renderable", false)
grid.columns.findWhere({ name: 'activity'} ).set("renderable", false)
grid.columns.findWhere({ name: 'location'} ).set("renderable", false)

grid.columns.findWhere({ name: 'invoices'} ).set("renderable", false)
grid.columns.findWhere({ name: 'last_invoice'} ).set("renderable", false)
grid.columns.findWhere({ name: 'contact_since'} ).set("renderable", false)
grid.columns.findWhere({ name: 'failed_logins'} ).set("renderable", false)
grid.columns.findWhere({ name: 'logins'} ).set("renderable", false)
grid.columns.findWhere({ name: 'requests'} ).set("renderable", false)
grid.columns.findWhere({ name: 'company_name'} ).set("renderable", false)
grid.columns.findWhere({ name: 'contact_name'} ).set("renderable", false)
grid.columns.findWhere({ name: 'email'} ).set("renderable", false)
grid.columns.findWhere({ name: 'mobile'} ).set("renderable", false)
grid.columns.findWhere({ name: 'telephone'} ).set("renderable", false)
grid.columns.findWhere({ name: 'total_payments'} ).set("renderable", false)
grid.columns.findWhere({ name: 'account_balance'} ).set("renderable", false)


if(view=='overview'){
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'activity'} ).set("renderable", true)
grid.columns.findWhere({ name: 'location'} ).set("renderable", true)
grid.columns.findWhere({ name: 'invoices'} ).set("renderable", true)
grid.columns.findWhere({ name: 'last_invoice'} ).set("renderable", true)
grid.columns.findWhere({ name: 'contact_since'} ).set("renderable", true)
}else if(view=='weblog'){
grid.columns.findWhere({ name: 'logins'} ).set("renderable", true)
grid.columns.findWhere({ name: 'failed_logins'} ).set("renderable", true)
grid.columns.findWhere({ name: 'requests'} ).set("renderable", true)
}else if(view=='contact'){
grid.columns.findWhere({ name: 'company_name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'contact_name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'email'} ).set("renderable", true)
grid.columns.findWhere({ name: 'mobile'} ).set("renderable", true)
grid.columns.findWhere({ name: 'telephone'} ).set("renderable", true)
}else if(view=='invoices'){
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'last_invoice'} ).set("renderable", true)
grid.columns.findWhere({ name: 'invoices'} ).set("renderable", true)
grid.columns.findWhere({ name: 'total_payments'} ).set("renderable", true)
grid.columns.findWhere({ name: 'account_balance'} ).set("renderable", true)

}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
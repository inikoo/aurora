{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 3 Oct 2019 14:40:36 +0800 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

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

},{
name: "code",
label: "{t}ID{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='code'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ })


},  {
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
{if $sort_key=='name'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
}, {
name: "location",
label: "{t}Location{/t}",
sortType: "toggle",
{if $sort_key=='location'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

editable: false,

cell: "html"
}, {
name: "since",
label: "{t}Since{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='contact_since'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

}, {
name: "last_invoice",
label: "{t}Last invoice{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='last_invoice'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "total_payments",
label: "{t}Payments{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_payments'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell


},

{
name: "invoices",
label: "{t}Invoices{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoices'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}, {
name: "logins",
label: "{t}Logins{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='logins'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "failed_logins",
label: "{t}Fail Logins{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='failed_logins'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "requests",
label: "{t}Pageviews{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='requests'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
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
name: "email",
label: "{t}Email{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='email'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.EmailCell.extend({
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
}, {
name: "total_invoiced_amount",
label: "{t}Invoiced{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_invoiced_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "pending_orders",
label: "{t}Pending{/t}",
title: "{t}Pending orders{/t}",

editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='pending_orders'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}


]

function change_table_view(view,save_state){

$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');

grid.columns.findWhere({ name: 'name'} ).set("renderable", false)
grid.columns.findWhere({ name: 'location'} ).set("renderable", false)

grid.columns.findWhere({ name: 'invoices'} ).set("renderable", false)
grid.columns.findWhere({ name: 'last_invoice'} ).set("renderable", false)
grid.columns.findWhere({ name: 'since'} ).set("renderable", false)
grid.columns.findWhere({ name: 'failed_logins'} ).set("renderable", false)
grid.columns.findWhere({ name: 'logins'} ).set("renderable", false)
grid.columns.findWhere({ name: 'requests'} ).set("renderable", false)
grid.columns.findWhere({ name: 'company_name'} ).set("renderable", false)
grid.columns.findWhere({ name: 'contact_name'} ).set("renderable", false)
grid.columns.findWhere({ name: 'email'} ).set("renderable", false)
grid.columns.findWhere({ name: 'mobile'} ).set("renderable", false)
grid.columns.findWhere({ name: 'telephone'} ).set("renderable", false)
grid.columns.findWhere({ name: 'total_payments'} ).set("renderable", false)
grid.columns.findWhere({ name: 'total_invoiced_amount'} ).set("renderable", false)


if(view=='overview'){
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'location'} ).set("renderable", true)
grid.columns.findWhere({ name: 'invoices'} ).set("renderable", true)
grid.columns.findWhere({ name: 'last_invoice'} ).set("renderable", true)
grid.columns.findWhere({ name: 'since'} ).set("renderable", true)

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
grid.columns.findWhere({ name: 'total_invoiced_amount'} ).set("renderable", true)

}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
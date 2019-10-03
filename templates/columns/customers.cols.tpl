var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},{
name: "formatted_id",
label: "{t}ID{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='formatted_id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

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
name: "activity",
label: "{t}Status{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='activity'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: "string"
}, {
name: "contact_since",
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
name: "total_invoiced_net_amount",
label: "{t}Invoiced Net{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_invoiced_net_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "clients",
label: "{t}Clients{/t}",
renderable:{if $store->get('Store Type')=='Dropshipping'}true{else}false{/if},
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='clients'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},


]

function change_table_view(view,save_state){

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
grid.columns.findWhere({ name: 'total_invoiced_amount'} ).set("renderable", false)
grid.columns.findWhere({ name: 'total_invoiced_net_amount'} ).set("renderable", false)



{if $store->get('Store Type')=='Dropshipping'}
    grid.columns.findWhere({ name: 'clients'} ).set("renderable", false)
{/if}

if(view=='overview'){
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'activity'} ).set("renderable", true)
grid.columns.findWhere({ name: 'location'} ).set("renderable", true)
grid.columns.findWhere({ name: 'invoices'} ).set("renderable", true)
grid.columns.findWhere({ name: 'last_invoice'} ).set("renderable", true)
grid.columns.findWhere({ name: 'contact_since'} ).set("renderable", true)
grid.columns.findWhere({ name: 'total_invoiced_net_amount'} ).set("renderable", true)
{if $store->get('Store Type')=='Dropshipping'}
    grid.columns.findWhere({ name: 'clients'} ).set("renderable", true)
{/if}

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
grid.columns.findWhere({ name: 'total_invoiced_amount'} ).set("renderable", true)
grid.columns.findWhere({ name: 'total_invoiced_net_amount'} ).set("renderable", true)

}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
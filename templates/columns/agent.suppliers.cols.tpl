var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "operations",
renderable: {if ($data['parent']=='agent') }true{else}false{/if},

label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},

{
name: "associated",
label: "",
renderable: {if ($data['parent']=='category') }true{else}false{/if},
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},

{
name: "code",
label: "{t}Code{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='id'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.StringCell.extend({
orderSeparator: '',
events: {
"click": function() {
change_view('supplier{if $data.object=='list'}/list/{$data.key}{/if}/' + this.model.get("id"))
}
},
className: "link"

})

}, {
name: "name",
label: "Name",
sortType: "toggle",
cell: Backgrid.StringCell.extend({
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
name: "last_purchase_order",
label: "{t}Last PO{/t}",
defaultOrder:1,
editable: false,
sortType: "toggle",
{if $sort_key=='last_invoice'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}


cell: Backgrid.StringCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "supplier_parts",
label: "{t}Supplier's parts{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='supplier_parts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "active_supplier_parts",
label: "{t}Active parts{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='active_supplier_parts'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "surplus",
label: "{t}Surplus{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='surplus'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "optimal",
label: "{t}Optimal{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='optimal'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "low",
label: "{t}Low{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='low'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "critical",
label: "{t}Critical{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='critical'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "out_of_stock",
label: "{t}Out of Stock{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='out_of_stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "pending_po",
label: "{t}Pending PO{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='pending_po'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.StringCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "company",
label: "{t}Company{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.StringCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
}, {
name: "contact",
label: "{t}Main contact{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.StringCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
}, {
name: "email",
label: "{t}Email{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.EmailCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
},{
name: "telephone",
label: "{t}Telephone{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.StringCell.extend({
events: {
"dblclick": "enterEditMode"
}
})
},
{
name: "sales",
label: "{t}Sales{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sales_1y",
label: "1YB",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_1y'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright "} ),
headerCell: integerHeaderCell

},
{
name: "sales_year0",
label: new Date().getFullYear(),
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_year0'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sales_year1",
label: new Date().getFullYear()-1,
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_year1'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sales_year2",
label: new Date().getFullYear()-2,
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_year2'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sales_year3",
label: new Date().getFullYear()-3,
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_year3'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sales_year4",
label: new Date().getFullYear()-3,
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_year4'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sales_quarter0",
label: get_quarter_label(0),
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_quarter0'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sales_quarter1",
label: get_quarter_label(1),
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_quarter1'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sales_quarter2",
label: get_quarter_label(2),
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_quarter2'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sales_quarter3",
label: get_quarter_label(3),
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_quarter3'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sales_quarter4",
label: get_quarter_label(4),
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_quarter4'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
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


grid.columns.findWhere({ name: 'name'} ).set("renderable", false)
grid.columns.findWhere({ name: 'location'} ).set("renderable", false)
grid.columns.findWhere({ name: 'last_purchase_order'} ).set("renderable", false)
grid.columns.findWhere({ name: 'active_supplier_parts'} ).set("renderable", false)
grid.columns.findWhere({ name: 'supplier_parts'} ).set("renderable", false)

grid.columns.findWhere({ name: 'surplus'} ).set("renderable", false)
grid.columns.findWhere({ name: 'optimal'} ).set("renderable", false)
grid.columns.findWhere({ name: 'low'} ).set("renderable", false)
grid.columns.findWhere({ name: 'critical'} ).set("renderable", false)
grid.columns.findWhere({ name: 'out_of_stock'} ).set("renderable", false)

grid.columns.findWhere({ name: 'pending_po'} ).set("renderable", false)
grid.columns.findWhere({ name: 'company'} ).set("renderable", false)
grid.columns.findWhere({ name: 'contact'} ).set("renderable", false)
grid.columns.findWhere({ name: 'email'} ).set("renderable", false)
grid.columns.findWhere({ name: 'telephone'} ).set("renderable", false)

grid.columns.findWhere({ name: 'sales'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sales_1y'} ).set("renderable", false)

grid.columns.findWhere({ name: 'sales_year0'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sales_year1'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sales_year2'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sales_year3'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sales_year4'} ).set("renderable", false)

grid.columns.findWhere({ name: 'sales_quarter0'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sales_quarter1'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sales_quarter2'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sales_quarter3'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sales_quarter4'} ).set("renderable", false)


if(view=='overview'){
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)
grid.columns.findWhere({ name: 'location'} ).set("renderable", true)
grid.columns.findWhere({ name: 'last_purchase_order'} ).set("renderable", true)
grid.columns.findWhere({ name: 'active_supplier_parts'} ).set("renderable", true)
grid.columns.findWhere({ name: 'supplier_parts'} ).set("renderable", true)

}else if(view=='weblog'){
grid.columns.findWhere({ name: 'logins'} ).set("renderable", true)
grid.columns.findWhere({ name: 'failed_logins'} ).set("renderable", true)
grid.columns.findWhere({ name: 'requests'} ).set("renderable", true)
}else if(view=='contact'){
grid.columns.findWhere({ name: 'company'} ).set("renderable", true)
grid.columns.findWhere({ name: 'contact'} ).set("renderable", true)
grid.columns.findWhere({ name: 'email'} ).set("renderable", true)
grid.columns.findWhere({ name: 'telephone'} ).set("renderable", true)
}else if(view=='parts'){
grid.columns.findWhere({ name: 'active_supplier_parts'} ).set("renderable", true)
grid.columns.findWhere({ name: 'surplus'} ).set("renderable", true)
grid.columns.findWhere({ name: 'optimal'} ).set("renderable", true)
grid.columns.findWhere({ name: 'low'} ).set("renderable", true)
grid.columns.findWhere({ name: 'critical'} ).set("renderable", true)
grid.columns.findWhere({ name: 'out_of_stock'} ).set("renderable", true)

}else if(view=='sales'){
$('#columns_period').removeClass('hide');
grid.columns.findWhere({ name: 'sales'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_1y'} ).set("renderable", true)

}else if(view=='sales_y'){
grid.columns.findWhere({ name: 'sales_year0'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_year1'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_year2'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_year3'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_year4'} ).set("renderable", true)
}else if(view=='sales_q'){
grid.columns.findWhere({ name: 'sales_quarter0'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_quarter1'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_quarter2'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_quarter3'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_quarter4'} ).set("renderable", true)
}else if(view=='orders'){
grid.columns.findWhere({ name: 'last_purchase_order'} ).set("renderable", true)
grid.columns.findWhere({ name: 'pending_po'} ).set("renderable", true)

}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}
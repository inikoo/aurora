var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},
{
name: "code",
label: "{t}Code{/t}   ",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ })
},

{
name: "store",
label: "{t}Store{/t}",

renderable: {if $data['object']=='part' or  $data['object']=='' or $tab=='part_family.products'   }true{else}false{/if},
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend([])
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
name: "status",
label: "{t}Status{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({} ),

},

{
name: "name",
label: "{t}Name{/t}",
editable: false,
sortType: "toggle",
cell: "html"
},


{
name: "parts",
label: "{t}Parts{/t}",
editable: false,
sortType: "toggle",
cell: "html"
},
{
name: "price",
label: "{t}Price{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell

},
{
name: "margin",
label: "{t}Margin{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "rrp",
label: "{t}RRP{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell

},
{
name: "web_state",
label: "{t}Web state{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "sales_total",
label: "{t}Sales{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_total'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "dispatched_total",
label: "{t}Qty Invoiced{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_total'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "customer_total",
label: "{t}Customers{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='customer_total'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},

{
name: "percentage_repeat_customer_total",
label: "{t}% Repeat customers{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='percentage_repeat_customer_total'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},


{
name: "dc_sales",
label: "{t}Sales{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='money_in'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "dc_sales_1yb",
label: "1YB",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_1y'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright "} ),
headerCell: integerHeaderCell

},
{
name: "qty_invoiced",
label: "{t}Quantity invoiced{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='qty_invoiced'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "qty_invoiced_1yb",
label: "1YB",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='qty_invoiced_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
label: new Date().getFullYear()-4,
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


function change_table_view(view, save_state) {


$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');

close_columns_period_options()
$('#columns_period').addClass('hide');


grid.columns.findWhere({ name: 'status'} ).set("renderable", false)
grid.columns.findWhere({ name: 'price'} ).set("renderable", false)
grid.columns.findWhere({ name: 'margin'} ).set("renderable", false)
grid.columns.findWhere({ name: 'rrp'} ).set("renderable", false)
grid.columns.findWhere({ name: 'name'} ).set("renderable", false)
grid.columns.findWhere({ name: 'parts'} ).set("renderable", false)

grid.columns.findWhere({ name: 'web_state'} ).set("renderable", false)


grid.columns.findWhere({ name: 'dc_sales'} ).set("renderable", false)
grid.columns.findWhere({ name: 'dc_sales_1yb'} ).set("renderable", false)
grid.columns.findWhere({ name: 'qty_invoiced'} ).set("renderable", false)
grid.columns.findWhere({ name: 'qty_invoiced_1yb'} ).set("renderable", false)

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

grid.columns.findWhere({ name: 'sales_total'} ).set("renderable", false)
grid.columns.findWhere({ name: 'dispatched_total'} ).set("renderable", false)
grid.columns.findWhere({ name: 'customer_total'} ).set("renderable", false)
grid.columns.findWhere({ name: 'percentage_repeat_customer_total'} ).set("renderable", false)


if(view=='overview'){
grid.columns.findWhere({ name: 'name'} ).set("renderable", true)

grid.columns.findWhere({ name: 'price'} ).set("renderable", true)
grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
grid.columns.findWhere({ name: 'web_state'} ).set("renderable", true)
grid.columns.findWhere({ name: 'dc_sales'} ).set("renderable", true)
grid.columns.findWhere({ name: 'dc_sales_1yb'} ).set("renderable", true)
}else if(view=='price'){

grid.columns.findWhere({ name: 'parts'} ).set("renderable", true)
grid.columns.findWhere({ name: 'price'} ).set("renderable", true)
grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
grid.columns.findWhere({ name: 'margin'} ).set("renderable", true)
grid.columns.findWhere({ name: 'rrp'} ).set("renderable", true)

}else if(view=='status'){
grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
grid.columns.findWhere({ name: 'active'} ).set("renderable", true)
grid.columns.findWhere({ name: 'suspended'} ).set("renderable", true)
grid.columns.findWhere({ name: 'discontinuing'} ).set("renderable", true)
grid.columns.findWhere({ name: 'discontinued'} ).set("renderable", true)
}else if(view=='sales'){
$('#columns_period').removeClass('hide');
grid.columns.findWhere({ name: 'dc_sales'} ).set("renderable", true)
grid.columns.findWhere({ name: 'dc_sales_1yb'} ).set("renderable", true)
grid.columns.findWhere({ name: 'qty_invoiced'} ).set("renderable", true)
grid.columns.findWhere({ name: 'qty_invoiced_1yb'} ).set("renderable", true)

}else if(view=='performance'){
grid.columns.findWhere({ name: 'sales_total'} ).set("renderable", true)
grid.columns.findWhere({ name: 'dispatched_total'} ).set("renderable", true)
grid.columns.findWhere({ name: 'customer_total'} ).set("renderable", true)
grid.columns.findWhere({ name: 'percentage_repeat_customer_total'} ).set("renderable", true)

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

}else if(view=='parts'){
grid.columns.findWhere({ name: 'parts'} ).set("renderable", false)


}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}


}

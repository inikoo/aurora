var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
},
{
name: "status",
label:'',
html_label:'<i class="fa fa-retweet" aria-hidden="true"></i> ',
title:"{t}Category status{/t}",

editable: false,
cell: Backgrid.HtmlCell.extend({ }),
headerCell: HeaderHtmlCell
},

{
name: "code",
label: "{t}Code{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({

})

},
{
name: "label",
label:"{t}Label{/t}",
editable: false,
cell: "string"
},

{
name: "products",
label:"{t}Products{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='products'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},


{
name: "active",
label:"{t}Active{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='active'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},

{
name: "webpage_state",
label:'',
html_label:'<i class="fa fa-retweet fa-fw" style="opacity:.8" aria-hidden="true"></i><i class="fa fa-globe fa-fw" style="position:relative;left:-25px;opacity:.3" aria-hidden="true"></i> ',
title:"{t}Webpage state{/t}",

editable: false,
cell: Backgrid.HtmlCell.extend({

}),
headerCell: HeaderHtmlCell

},

{
name: "online",
label:'',
html_label:'<i class="fa fa-microphone" aria-hidden="true"></i> <i class="fa fa-cube" aria-hidden="true"></i>',
title:"{t}Online products{/t}",

editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='online'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},

{
name: "out_of_stock",
label:'',
html_label:'<i class="fa fa-microphone" aria-hidden="true"></i> <i class="fa fa-cube" aria-hidden="true"></i> <i class="fa fa-ban error" aria-hidden="true"></i>',

title:"{t}Online out of stock products{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='out_of_stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell

},


{
name: "percentage_out_of_stock",
label:"%",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='percentage_out_of_stock'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ } )

},

{
name: "webpage",
label: "{t}Webpage{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({

})

},


{
name: "suspended",
label:"{t}Suspended{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='suspended'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "discontinuing",
label:"{t}Discontinuing{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='discontinuing'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},
{
name: "discontinued",
label:"{t}Discontinued{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='discontinued'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell

},

{
name: "sales",
label: "{t}Invoiced amount{/t}",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
},
{
name: "sales_1yb",
label: "1YB",
editable: false,

defaultOrder:1,
sortType: "toggle",
{if $sort_key=='sales_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
},
{
name: "remove",
renderable: {if empty($is_family)}true{else}false{/if},

label:'',
html_label: "<i class="far fa-trash-alt"></i>",
editable: false,
sortable:false,

cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: rightHeaderHtmlCell


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


grid.columns.findWhere({ name: 'label'} ).set("renderable", false)
//grid.columns.findWhere({ name: 'status'} ).set("renderable", false)
grid.columns.findWhere({ name: 'products'} ).set("renderable", false)

grid.columns.findWhere({ name: 'active'} ).set("renderable", false)
grid.columns.findWhere({ name: 'suspended'} ).set("renderable", false)
grid.columns.findWhere({ name: 'discontinuing'} ).set("renderable", false)
grid.columns.findWhere({ name: 'discontinued'} ).set("renderable", false)

grid.columns.findWhere({ name: 'sales'} ).set("renderable", false)
grid.columns.findWhere({ name: 'sales_1yb'} ).set("renderable", false)
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

grid.columns.findWhere({ name: 'online'} ).set("renderable", false)
grid.columns.findWhere({ name: 'out_of_stock'} ).set("renderable", false)
grid.columns.findWhere({ name: 'webpage'} ).set("renderable", false)
grid.columns.findWhere({ name: 'percentage_out_of_stock'} ).set("renderable", false)
grid.columns.findWhere({ name: 'webpage_state'} ).set("renderable", false)



if(view=='overview'){
grid.columns.findWhere({ name: 'label'} ).set("renderable", true)
grid.columns.findWhere({ name: 'products'} ).set("renderable", true)
grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
}
if(view=='webpages'){
//grid.columns.findWhere({ name: 'products'} ).set("renderable", true)
//grid.columns.findWhere({ name: 'status'} ).set("renderable", true)

grid.columns.findWhere({ name: 'webpage_state'} ).set("renderable", true)


grid.columns.findWhere({ name: 'online'} ).set("renderable", true)
grid.columns.findWhere({ name: 'out_of_stock'} ).set("renderable", true)
grid.columns.findWhere({ name: 'percentage_out_of_stock'} ).set("renderable", true)

grid.columns.findWhere({ name: 'webpage'} ).set("renderable", true)

}else if(view=='status'){
grid.columns.findWhere({ name: 'status'} ).set("renderable", true)
grid.columns.findWhere({ name: 'active'} ).set("renderable", true)
grid.columns.findWhere({ name: 'suspended'} ).set("renderable", true)
grid.columns.findWhere({ name: 'discontinuing'} ).set("renderable", true)
grid.columns.findWhere({ name: 'discontinued'} ).set("renderable", true)
}else if(view=='sales'){
$('#columns_period').removeClass('hide');
grid.columns.findWhere({ name: 'sales'} ).set("renderable", true)
grid.columns.findWhere({ name: 'sales_1yb'} ).set("renderable", true)
grid.columns.findWhere({ name: 'qty_invoiced'} ).set("renderable", true)
grid.columns.findWhere({ name: 'qty_invoiced_1yb'} ).set("renderable", true)

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

}else if(view=='stock'){


}

if(save_state){
var request = "/ar_state.php?tipo=set_table_view&tab={$tab}&table_view=" + view

$.getJSON(request, function(data) {});
}

}

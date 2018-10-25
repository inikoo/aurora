{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 October 2018 at 19:53:45 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
*/*}

var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},

{
name: "category_code",
label: "{t}Category{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})
},

{
name: "category",
label: "{t}Category{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
})
},

{
name: "customers",
label: "{t}Customers{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='customers'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "refunds",
label: "{t}Refunds{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refunds'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "refunds_delta_1yb",
label: "{t}1Y{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refunds_delta_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "refunds_amount_oc",
label: "{t}Refund amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refunds_amount_oc'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "refunds_amount",
label: "{t}Refund amount{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refunds_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "refunds_amount_oc_delta_1yb",
label: "{t}1Y{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refunds_amount_oc_delta_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "refunds_amount_delta_1yb",
label: "{t}1Y{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='refunds_amount_delta_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
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
},

{
name: "invoices_delta_1yb",
label: "{t}1Y{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='invoices_delta_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "revenue_oc",
label: "{t}Sales{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='revenue_oc'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "revenue_oc_delta_1yb",
label: "{t}1Y{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='revenue_oc_delta_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "profit_oc",
label: "{t}Profit{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='profit_oc'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "profit_oc_delta_1yb",
label: "{t}1Y{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='profit_oc_delta_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "revenue",
label: "{t}Sales{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='revenue'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "revenue_delta_1yb",
label: "{t}1Y{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='revenue_delta_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

{
name: "profit",
label: "{t}Profit{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='profit'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "profit_delta_1yb",
label: "{t}1Y{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='profit_delta_1yb'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}



]

function change_table_view(view,save_state){



$('.view').removeClass('selected');
$('#view_'+view).addClass('selected');




grid.columns.findWhere({ name: 'category_code'} ).set("renderable", false)
grid.columns.findWhere({ name: 'category'} ).set("renderable", false)

grid.columns.findWhere({ name: 'customers'} ).set("renderable", false)

grid.columns.findWhere({ name: 'refunds_amount_oc'} ).set("renderable", false)
grid.columns.findWhere({ name: 'refunds_amount_oc_delta_1yb'} ).set("renderable", false)
grid.columns.findWhere({ name: 'revenue_oc'} ).set("renderable", false)
grid.columns.findWhere({ name: 'revenue_oc_delta_1yb'} ).set("renderable", false)
grid.columns.findWhere({ name: 'profit_oc'} ).set("renderable", false)
grid.columns.findWhere({ name: 'profit_oc_delta_1yb'} ).set("renderable", false)


grid.columns.findWhere({ name: 'refunds_amount'} ).set("renderable", false)
grid.columns.findWhere({ name: 'refunds_amount_delta_1yb'} ).set("renderable", false)
grid.columns.findWhere({ name: 'revenue'} ).set("renderable", false)
grid.columns.findWhere({ name: 'revenue_delta_1yb'} ).set("renderable", false)
grid.columns.findWhere({ name: 'profit'} ).set("renderable", false)
grid.columns.findWhere({ name: 'profit_delta_1yb'} ).set("renderable", false)




if(view=='overview'){
grid.columns.findWhere({ name: 'category'} ).set("renderable", true)

{if $table_state['currency']=='account'}
    grid.columns.findWhere({ name: 'refunds_amount_oc'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'refunds_amount_oc_delta_1yb'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'revenue_oc'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'revenue_oc_delta_1yb'} ).set("renderable", true)


    //grid.columns.findWhere({ name: 'profit_oc'} ).set("renderable", true)
    //grid.columns.findWhere({ name: 'profit_oc_delta_1yb'} ).set("renderable", true)

{else}

    grid.columns.findWhere({ name: 'refunds_amount'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'refunds_amount_delta_1yb'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'revenue'} ).set("renderable", true)
    grid.columns.findWhere({ name: 'revenue_delta_1yb'} ).set("renderable", true)

    //grid.columns.findWhere({ name: 'profit'} ).set("renderable", true)
    //grid.columns.findWhere({ name: 'profit_delta_1yb'} ).set("renderable", true)

{/if}


}

}

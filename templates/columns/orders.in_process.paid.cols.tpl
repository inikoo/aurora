{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2017 at 20:17:20 CEST, Vienna Airport, Austria
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}

var columns= [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},

{
name: "checked",
label:'',
html_label: '',
headerCell: HeaderHtmlCell,
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_30 align_center"
})


},

{
name: "public_id",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

})
},

{
name: "customer_invoices_orders",
label: "{t}Customer invoices{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

})
},
{
name: "date",
label: "{t}Date{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "customer",
label: "{t}Customer{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({})
}, {
name: "payments",
label: "{t}Payment{/t}",
editable: false,
sortable: false,
cell: "html"
}, {
name: "total_amount",
label: "{t}Total{/t}",
editable: false,
defaultOrder:1,
sortType: "toggle",
{if $sort_key=='total_amount'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "actions",
label: "{t}Actions{/t}",
sortable: false,
editable: false,
cell: Backgrid.HtmlCell.extend({})
}


]

function change_table_view(view,save_state){}

{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 July 2018 at 13:24:56 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
*/*}
var columns = [
{
name: "request",
label: "",
editable: false,
renderable: false,
cell: "string"
},
{
name: "date",
label: "{t}Date{/t}",
renderable:true,
sortable: false,
editable: false,
cell: Backgrid.Cell.extend({
className: "aright width_150"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},

{
name: "invoices",
label: "{t}Invoices{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},
{
name: "refunds",
label: "{t}Refunds{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},
{
name: "invoiced_amount",
label: "{t}Invoiced amount{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},
{
name: "refunded_amount",
label: "{t}Refunded amount{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},

{
name: "sales",
label: "{t}Total amount{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},
{
name: "delta_sales_1yb",
label: "Delta {t}1y{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
}

]
function change_table_view(view,save_state){}

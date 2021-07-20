{*/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 July 2021 20:47 Kuala Lumour , Malaysia
 Copyright (c) 2021, Inikoo

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
name: "new_customers",
label: "{t}Registrations{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},
{
name: "delta_new_customers_1yb",
label: "Delta {t}1y{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},
{
name: "customers_with_orders",
label: "{t}Customers who ordered{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},
{
name: "delta_customers_with_orders_1yb",
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
function change_table_view(view,save_state){

}

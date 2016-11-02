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
},{
name: "customers",
label: "{t}Customers{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},{
name: "sales",
label: "{t}Sales{/t}",
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

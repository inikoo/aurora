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
name: "supplier_deliveries",
label: "{t}Supplier deliveries{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},
{
name: "purchased_amount",
label: "{t}Purchased amount{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},
{
name: "deliveries",
label: "{t}Sale deliveries{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},{
name: "dispatched",
label: "{t}Dispatched SKOs{/t}",
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
label: "{t}Sales amount{/t}",
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

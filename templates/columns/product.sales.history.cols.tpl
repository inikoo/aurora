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

    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell,
      sortType: "toggle",
},{
    name: "customers",
    label: "{t}Customers{/t}",
    editable: false,

    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell,
      sortType: "toggle",
},{
    name: "sales",
    label: "{t}Sales{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell,
      sortType: "toggle",
}

]
function change_table_view(view,save_state){}

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
    name: "float_a",
    label: "{$columns_parameters.a.label}",
    editable: false,
        renderable: {$columns_parameters.a.render},

    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell,
      sortType: "toggle",
},
{
    name: "float_b",
    label: "{$columns_parameters.b.label}",
    editable: false,
    renderable: {$columns_parameters.b.render},
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell,
      sortType: "toggle",
},
{
    name: "float_c",
    label: "{$columns_parameters.c.label}",
    editable: false,
    renderable: {$columns_parameters.c.render},
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell,
      sortType: "toggle",
},
{
    name: "float_d",
    label: "{$columns_parameters.d.label}",
    editable: false,
    renderable: {$columns_parameters.d.render},
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell,
      sortType: "toggle",
},{
    name: "int_a",
    label: "{$columns_parameters.int_a.label}",
    editable: false,
    renderable: {$columns_parameters.int_a.render},
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell,
      sortType: "toggle",
},{
    name: "int_b",
    label: "{$columns_parameters.int_b.label}",
    editable: false,
    renderable: {$columns_parameters.int_b.render},
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell,
      sortType: "toggle",
}

]
function change_table_view(view,save_state){}

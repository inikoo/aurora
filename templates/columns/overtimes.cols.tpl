var columns = [{
    name: "id",
    label: "",
    editable: false,
    cell: "integer",
    renderable: false


},{
    name: "status",
    label: "",
    editable: false,
    cell: Backgrid.HtmlCell.extend({
      className: "width_20"
     })
    
},{
    name: "formatted_key",
    label: "{t}Id{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({
      className: ""
     }),
},{
    name: "reference",
    label: "{t}Reference{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({
      className: ""
     }),
},{
    name: "start",
    label: "{t}Start{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
},{
    name: "end",
    label: "{t}End{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
},{
    name: "description",
    label: "{t}Description{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "width_250"
     })
    
},{
    name: "employees",
    label: "{t}Employees{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
} ,{
    name: "granted",
    label: "{t}Granted{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "aright"
     }),
     headerCell: integerHeaderCell
}
]


function change_table_view(view, save_state) {}

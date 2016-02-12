var columns = [{
    name: "id",
    label: "",
    editable: false,
    cell: "integer",
    renderable: false


},{
    name: "date",
    label: "{t}Date{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({
      className: "width_250 aright "
     }),
      headerCell: integerHeaderCell
},{
    name: "author",
    label: "{t}Author{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "width_200 "
     })
    
}, {
    name: "note",
    label: "{t}Note{/t}",
    editable: false,
    cell: "html"
}
]


function change_table_view(view, save_state) {}

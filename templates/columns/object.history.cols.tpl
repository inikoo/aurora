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
      className: "full_date aright"
     }),
      headerCell: integerHeaderCell
},{
    name: "author",
    label: "{t}Author{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
      className: "contact_name"
     })
    
}, {
    name: "note",
    label: "{t}Note{/t}",
    editable: false,
    cell: "html"
}
]


function change_table_view(view, save_state) {}

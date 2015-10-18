var columns = [
 {
    name: "site_key",
    label: "",
    editable: false,
    renderable: false,
    cell: "string"
}, {
    name: "query",
    label: "{t}Query{/t}",
    editable: false,
    cell: Backgrid.StringCell.extend({
       
      
    })
}, {
    name: "results",
    label: "{t}Average number of results{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell
}, {
    name: "date",
    label: "{t}Last searched{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell
}, {
    name: "users",
    label: "{t}Distinct users{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell
}, {
    name: "number",
    label: "{t}Searches{/t}",
    editable: false,
     cell: Backgrid.StringCell.extend({ className: "aright"} ),
        headerCell: integerHeaderCell
}

]


function change_table_view(view, save_state) {}

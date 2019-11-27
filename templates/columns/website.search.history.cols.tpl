var columns = [
{
name: "website_key",
label: "",
editable: false,
renderable: false,
cell: "string"
}, {
name: "query",
label: "{t}Query{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({


})
}, {
name: "results",
label: "{t}Number of results{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "date",
label: "{t}Date{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}, {
name: "user",
label: "{t}User{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},

]


function change_table_view(view, save_state) {}

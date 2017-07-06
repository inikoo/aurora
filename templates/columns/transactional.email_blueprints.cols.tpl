var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},

{
name: "name",
label: "{t}Name{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({


}),
},
{
name: "author",
label: "{t}Created by{/t}",
sortType: "toggle",
editable: false,
cell: Backgrid.HtmlCell.extend({


className: "aright"
}),
headerCell: integerHeaderCell

},

{
name: "date",
label: "{t}Date{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.StringCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
},{
name: "space",
label: "",
editable: false,
sortable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

className: "width_20"
}),
},
 {
name: "image",
label: "{t}Preview{/t}",
editable: false,
sortable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({

className: "width_300"
}),
},



{
name: "operations",
label: "{t}Operations{/t}",
sortable: false,
editable: false,
cell: Backgrid.HtmlCell.extend({


className: "xwidth_150"
}),
}
]


function change_table_view(view, save_state) {}

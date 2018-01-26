var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "visibility",
label: "",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},{
name: "caption",
label: "{t}Caption{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
events: {
"click": function() {
change_view('/{$data['object']}/{$data['key']}/attachment/'+this.model.get("id"))
}
},

className: "link"
}),
},{
name: "type",
label: "{t}Type{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "contact_name"
})

},{
name: "file_type",
label: "{t}Kind{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({

})

}, {
name: "preview",
label: "{t}Preview{/t}",
editable: false,
cell: "html"
}
,{
name: "size",
label: "{t}Size{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
}

,{
name: "download",
label: "",
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_20"
}),
}

]


function change_table_view(view, save_state) {}

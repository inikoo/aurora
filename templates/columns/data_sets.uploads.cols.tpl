var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


}
,{
name: "formatted_id",
label: "{t}Id{/t}",
editable: false,
cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view('/upload/'+this.model.get("id"))
}
},

className: "link"
}),
},{
name: "state",
label: "{t}State{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({

})

},{
name: "date",
label: "{t}Date{/t}",
editable: false,
cell: Backgrid.StringCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
},{
name: "object",
label: "{t}Object{/t}",
editable: false,
cell: Backgrid.HtmlCell.extend({

})

},{
name: "records",
label: "{t}Records{/t}",
editable: false,
cell: Backgrid.StringCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
},{
name: "ok",
label: "{t}Uploaded{/t}",
editable: false,
cell: Backgrid.StringCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
}
,{
name: "warnings",
label: "{t}Warnings{/t}",
editable: false,
cell: Backgrid.StringCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
}
,{
name: "errors",
label: "{t}Errors{/t}",
editable: false,
cell: Backgrid.StringCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
}


]


function change_table_view(view, save_state) {}

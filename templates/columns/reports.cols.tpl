var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string"
}, {
name: "name",
label: "{t}Name{/t}",
editable: false,
cell: Backgrid.Cell.extend({
events: {
"click": function() {
change_view( this.model.get("report_request") )
}
},
className: "link",


})
}, {
name: "section",
label: "{t}Section{/t}",
editable: false,
cell: Backgrid.Cell.extend({
orderSeparator: '',
events: {
"click": function() {
change_view( this.model.get("section_request") )
}
},
className: "link",


})
}

]
function change_table_view(view,save_state){}

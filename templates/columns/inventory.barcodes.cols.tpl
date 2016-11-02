var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},
{
name: "link",
label: "",
editable: false,


cell: Backgrid.HtmlCell.extend({
orderSeparator: '',
events: {
"click": function() {
change_view( 'inventory/barcode/' + this.model.get("id"))

}
},
className: "link"

})

},
{
name: "number",
label: "{t}Number{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.StringCell.extend({
orderSeparator: '',
events: {

},


})

},
{
name: "status",
label: "{t}Status{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

},{
name: "notes",
label: "{t}Notes{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.StringCell.extend({


})

},{
name: "assets",
label: "{t}Parts{/t}",
editable: false,
sortType: "toggle",

cell: "html",

}
]

function change_table_view(view,save_state){}
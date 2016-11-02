var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},
{
name: "associated",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},
{
name: "family",
label: "{t}Family{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.StringCell.extend({
events: {

},
className: " width_150",
})
},
{
name: "code",
label: "{t}Code{/t}   ",
editable: false,
sortType: "toggle",
cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view( {if $data['section']=='part'}'inventory/{$data['parent_key']}/part/{$data['key']}/product/' + this.model.get("id"){else if $data['section']=='category'}'products/{$data['store']->id}/category/{$data['_object']->get('Category Position')}/product/' + this.model.get("id"){else}'products/{$data['parent_key']}/'+this.model.get("id"){/if})
}
},
className: "link width_150",
})
}, {
name: "name",
label: "{t}Name{/t}",
editable: false,
sortType: "toggle",
cell: "string"
},
{
name: "price",
label: "{t}Price{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.StringCell.extend({ className: "aright"} ),

headerCell: integerHeaderCell
}

]


function change_table_view(view, save_state) {}

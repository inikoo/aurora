var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

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
label: "{t}Category{/t}",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
events: {

},
className: " width_150",
})
},

{
name: "reference",
label: "{t}Reference{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.StringCell.extend({
events: {
"click": function() {
change_view('{if $data['parent']=='account'}{else if $data['parent']=='category'}category/{$data['key']}/{else}{$data['parent']}/{$data['parent_key']}/{/if}part/' + this.model.get("id"))
}
},
className: "link"

})

},
{
name: "sko_description",
label: "{t}SKO description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.StringCell.extend({


})

},
{
name: "status",
label: "{t}Status{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({


})

}
]

function change_table_view(view,save_state){}
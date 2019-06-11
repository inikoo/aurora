var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

}, {
name: "image",
label: "",
editable: false,
sortable:false,

cell: Backgrid.HtmlCell.extend({
className: "width_50"
})

},{
name: "reference",
label: "{t}Part{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({ })

},
{
name: "description",
label: "{t}SKO description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({ })

},
{
name: "weight",
label: "{t}SKO weight (Kg){/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({

className: "width_500"
})

},{
name: "status",
label: "{t}Notes{/t}",
editable: false,
sortable:false,
cell: Backgrid.HtmlCell.extend({ })

}

]

function change_table_view(view,save_state){

}
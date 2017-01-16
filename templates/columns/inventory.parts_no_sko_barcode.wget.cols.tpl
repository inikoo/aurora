var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

}, {
name: "stock_status",
label: "",
editable: false,
sortType: "toggle",
cell: Backgrid.HtmlCell.extend({
className: "width_20"
})

},{
name: "reference",
label: "{t}Part{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.StringCell.extend({ })

},
{
name: "description",
label: "{t}Description{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.StringCell.extend({ })

},
{
name: "barcode",
label: "{t}SKO Barcode{/t}",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({

className: "width_500"
})

}

]

function change_table_view(view,save_state){

}
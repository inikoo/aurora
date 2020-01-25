var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},

{
name: "date",
label: "{t}Date{/t}",

editable: false,
cell: Backgrid.HtmlCell.extend({

className: " aright width_150"


}),
headerCell: integerHeaderCell,
sortType: "toggle",
},


{
name: "parts",
label: "{t}Parts{/t}",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({
className: "aright width_150"

}),
headerCell: integerHeaderCell,

},
{
name: "locations",
label: "{t}Locations{/t}",
editable: false,
sortable: false,

cell: Backgrid.HtmlCell.extend({
className: "aright width_150"

}),
headerCell: integerHeaderCell,

},
{
name: "value",
label: "{t}Value{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "commercial_value",
label: "{t}Commercial Value{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "in_po",
label: "{t}In PO{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "in_other",
label: "{t}In Other{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "out_sales",
label: "{t}Out sales{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "out_other",
label: "{t}Out other{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}



]

function change_table_view(view,save_state){}
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
name: "stock",
label: "{t}Stockx{/t}",
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
name: "sold",
label: "{t}Sold{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "in",
label: "{t}In{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "lost",
label: "{t}Lost{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
}

]

function change_table_view(view,save_state){}
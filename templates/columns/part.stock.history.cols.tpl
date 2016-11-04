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
renderable:{if $data['subtab']=='part.stock.history.daily'}true{else}false{/if},

editable: false,
cell: Backgrid.Cell.extend({
className: "aright width_150"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
}, {
name: "year",
label: "{t}Year{/t}",
editable: false,
renderable:{if $data['subtab']=='part.stock.history.annually'}true{else}false{/if},
cell: Backgrid.Cell.extend({
className: "aright width_150"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
}, {
name: "month_year",
label: "{t}Month{/t}",
editable: false,
renderable:{if $data['subtab']=='part.stock.history.monthly'}true{else}false{/if},
cell: Backgrid.Cell.extend({
className: "aright width_150"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
}, {
name: "week_year",
label: "{t}Week{/t}",
editable: false,
renderable:{if $data['subtab']=='part.stock.history.weekly'}true{else}false{/if},
cell: Backgrid.Cell.extend({
className: "aright width_150"
}),
headerCell: integerHeaderCell,
sortType: "toggle",
},


{
name: "stock",
label: "{t}Stock{/t}",
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
var columns = [{
name: "id",
label: "",
editable: false,
cell: "integer",
renderable: false


},{
name: "name",
label: "{if $tipo=='months'}{t}Month{/t}{elseif $tipo=='weeks'}{t}Week{/t}{elseif $tipo=='days'}{t}Date{/t}{/if}",
editable: false,
cell: Backgrid.HtmlCell.extend({
events: {
"click": function() {
change_view('timesheets/{if $tipo=='months'}month{elseif $tipo=='weeks'}week{elseif $tipo=='days'}day{/if}/'  +this.model.get("key"))
}
},
className: "link {if $tipo=='months'}width_100{elseif $tipo=='weeks'}width_50{elseif $tipo=='days'}width_150{/if}"
})


},{
name: "week_starting",
label: "{t}Week starting{/t}",
renderable: {if $tipo=='weeks'}true{else}false{/if},
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "aright width_200"
}),
headerCell: integerHeaderCell


},{
name: "day_of_week",
label: "{t}Day{/t}",
renderable: {if $tipo=='days'}true{else}false{/if},
editable: false,
cell: Backgrid.HtmlCell.extend({
className: "width_150"
}),


},{
name: "days",
label: "{t}Days{/t}",
renderable: {if $tipo=='days'}false{else}true{/if},

editable: false,
cell: Backgrid.StringCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
},{
name: "employees",
label: "{t}Employees{/t}",
editable: false,
cell: Backgrid.StringCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
},{
name: "timesheets",
label: "{t}Timesheets{/t}",
editable: false,
cell: Backgrid.StringCell.extend({
className: "aright"
}),
headerCell: integerHeaderCell
}
]


function change_table_view(view, save_state) {}

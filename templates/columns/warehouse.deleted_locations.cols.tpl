var columns = [
{
name: "id",
label: "",
editable: false,
renderable: false,
cell: "string",

},{
name: "code",
label: "{t}Code{/t}",
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({

})

},{
name: "area",
label: "{t}Area{/t}",
renderable:true,
editable: false,
sortType: "toggle",

cell: Backgrid.HtmlCell.extend({

})

},
{
name: "date",
label: "{t}Deleted date{/t}",
editable: false,
sortType: "toggle",
{if $sort_key=='date'}direction: '{if $sort_order==1}descending{else}ascending{/if}',{/if}
cell: Backgrid.HtmlCell.extend({ className: "aright"} ),
headerCell: integerHeaderCell
},
{
name: "note",
label: "{t}Note{/t}",
editable: false,
sortable: false,
cell: Backgrid.HtmlCell.extend({ className:"width_400" } ),
}
]

function change_table_view(view,save_state){}